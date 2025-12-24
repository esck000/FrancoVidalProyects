import { useEffect, useMemo, useState } from "react";
import "./Products.css";
import { motion, AnimatePresence } from "framer-motion";
import { useNavigate, useParams } from "react-router-dom";


/* ======================================================
   Utils
====================================================== */

function normalizeText(text) {
  return text
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .toLowerCase();
}

/**
 * Mapea el producto que viene desde la API (CakePHP)
 * al formato que usa el frontend
 */
function mapApiProductToFrontend(p) {
  return {
    id: p.id,
    name: p.name,
    description: p.description ?? "",
    category: p.category?.name ?? "Sin categoría",
    price: Number(p.price),
    unit: p.unit ?? "kg",
    unitQuantity: Number(p.unit_quantity) || 1,

    // Imágenes desde la API
    images: p.images ?? {
      small: null,
      medium: null,
      large: null,
    },

    //  USAR LO QUE VIENE DE LA API
    nutrition: p.nutrition ?? null,

    recipes: Array.isArray(p.recipes)
      ? p.recipes.map((r) => ({
          id: r.id,
          name: r.name,
        }))
      : [],
  };
}

/* ======================================================
   Component
====================================================== */

export default function Products() {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);

  const [search, setSearch] = useState("");
  const [category, setCategory] = useState("Todos");

  const [selectedProduct, setSelectedProduct] = useState(null);
  const [quantities, setQuantities] = useState({});
  const [cart, setCart] = useState({});
  const [isCartOpen, setIsCartOpen] = useState(false);

  const navigate = useNavigate();
  const { id } = useParams();

  /* ======================================================
     Fetch API
  ====================================================== */

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        setLoading(true);

        const res = await fetch("http://localhost:8765/api/products.json");
        if (!res.ok) throw new Error("Error API");

        const json = await res.json();
        const mapped = json.products.map(mapApiProductToFrontend);

        setProducts(mapped);
      } catch (err) {
        console.error("Error cargando productos:", err);
      } finally {
        setLoading(false);
      }
    };

    fetchProducts();
  }, []);

  useEffect(() => {
    if (!id) return;
    if (products.length === 0) return;

    const product = products.find(
      (p) => Number(p.id) === Number(id)
    );

    if (product) {
      setSelectedProduct(product);
    }
  }, [id, products]);


  /* ======================================================
     Carrito
  ====================================================== */
  const handleAddToCart = (productId) => {
    setCart((prev) => ({
      ...prev,
      [productId]: 1,
    }));

    setQuantities((prev) => ({
      ...prev,
      [productId]: 1,
    }));
  };

  const handleUpdateCartItem = (productId, delta) => {
    setCart((prev) => {
      const updated = (prev[productId] ?? 0) + delta;
      if (updated <= 0) {
        const { [productId]: _, ...rest } = prev;
        return rest;
      }
      return { ...prev, [productId]: updated };
    });
  };


  /* ======================================================
     Computed
  ====================================================== */

  const totalItemsInCart = useMemo(
    () => Object.values(cart).reduce((acc, n) => acc + n, 0),
    [cart]
  );

  const cartItems = useMemo(() => {
    return Object.entries(cart)
      .map(([id, quantity]) => {
        const product = products.find((p) => p.id === Number(id));
        if (!product) return null;
        return { product, quantity };
      })
      .filter(Boolean);
  }, [cart, products]);

  const cartTotal = useMemo(
    () =>
      cartItems.reduce(
        (acc, item) => acc + item.product.price * item.quantity,
        0
      ),
    [cartItems]
  );

  const filteredProducts = useMemo(() => {
    const normSearch = normalizeText(search);
    return products.filter((p) => {
      const matchesCategory = category === "Todos" || p.category === category;
      const matchesSearch = normalizeText(p.name).includes(normSearch);
      return matchesCategory && matchesSearch;
    });
  }, [search, category, products]);

  /* ======================================================
     Whatsapp Message
  ====================================================== */
  const generateWhatsappMessage = () => {
    if (cartItems.length === 0) return "";

    let message = "Hola, quisiera hacer el siguiente pedido:\n\n";

    cartItems.forEach(({ product, quantity }) => {
      message += `• ${product.name} / ${product.unitQuantity} ${product.unit} — Cantidad = ${quantity}\n`;
    });

    message += `\nTotal estimado: $${cartTotal.toLocaleString("es-CL")}`;
    message += `\n\nGracias`;

    return encodeURIComponent(message);
  };

const handleSendWhatsapp = () => {
  if (cartItems.length === 0) return;

  const phoneNumber = "56994142079";
  const message = generateWhatsappMessage();

  window.open(
    `https://wa.me/${phoneNumber}?text=${message}`,
    "_blank"
  );
};


  /* ======================================================
     Render
  ====================================================== */

  return (
    <div className="products-page">
      <div className="products-header">
        <div className="products-header-main">
          <div>
            <h1>Nuestros productos del mar</h1>
            <p className="header-subtitle">
              Explora nuestro catálogo y agrega productos a tu carrito.
            </p>
          </div>
        </div>

        <div className="products-filters">
          <input
            type="text"
            className="search-input"
            placeholder="Buscar por nombre..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
          />

          <select
            className="filter-select"
            value={category}
            onChange={(e) => setCategory(e.target.value)}
          >
            <option value="Todos">Todas las categorías</option>
            {[...new Set(products.map((p) => p.category))].map((cat) => (
              <option key={cat} value={cat}>
                {cat}
              </option>
            ))}
          </select>
        </div>
      </div>

      {/* GRID */}
      {loading ? (
        <div className="grid-loader">Cargando productos...</div>
      ) : (
        <div className="products-grid">
          {filteredProducts.map((product) => {
            const qty = quantities[product.id] ?? 1;
            const thumbSrc =
              product.images?.medium || "/images/placeholder-product.jpg";

            return (
              <motion.div
                key={product.id}
                className="product-card"
                whileHover={{ y: -4, scale: 1.01 }}
                onClick={() => navigate(`/productos/${product.id}`)}
              >
                <div className="product-thumb">
                  <img src={thumbSrc} alt={product.name} loading="lazy" />
                </div>

                <div className="product-body">
                  <h3>{product.name}</h3>
                  <p className="product-price">
                    ${product.price.toLocaleString("es-CL")}
                    <span className="unit">
                      {" "}
                      / {product.unitQuantity} {product.unit}
                    </span>
                  </p>
                  <span className="tag">{product.category}</span>
                </div>

                <div
                  className="product-actions"
                  onClick={(e) => e.stopPropagation()}
                >
                  {cart[product.id] ? (
                    /* ===== YA EN PEDIDO ===== */
                    <div className="qty-control">
                      <button
                        onClick={() => handleUpdateCartItem(product.id, -1)}
                      >
                        -
                      </button>

                      <input
                        type="number"
                        min="1"
                        value={cart[product.id]}
                        readOnly
                      />

                      <button
                        onClick={() => handleUpdateCartItem(product.id, 1)}
                      >
                        +
                      </button>
                    </div>
                  ) : (
                    /* ===== NO ESTÁ EN PEDIDO ===== */
                    <button
                      className="add-cart-btn"
                      onClick={() => handleAddToCart(product.id)}
                    >
                      Agregar
                    </button>
                  )}
                </div>
              </motion.div>
            );
          })}
        </div>
      )}

      {/* MODAL */}
      <AnimatePresence>
        {selectedProduct && (
          <motion.div
            className="product-modal"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={() => {
              setSelectedProduct(null);
              navigate("/productos");
            }}

          >
            <motion.div
              className="modal-content"
              initial={{ scale: 0.95, y: 20 }}
              animate={{ scale: 1, y: 0 }}
              exit={{ scale: 0.95, y: 20 }}
              transition={{ duration: 0.2 }}
              onClick={(e) => e.stopPropagation()}
            >
              <button
                className="close-btn"
                onClick={() => {
                  setSelectedProduct(null);
                  navigate("/productos");
                }}
              >
                ✕
              </button>

              <div className="modal-layout">
                {/* ================= IMAGEN ================= */}
                <div className="modal-image">
                  <img
                    src={
                      selectedProduct.images?.large ||
                      selectedProduct.images?.medium ||
                      selectedProduct.images?.small
                    }
                    alt={selectedProduct.name}
                  />
                </div>

                {/* ================= INFO ================= */}
                <div className="modal-info">
                  {/* Categoría */}
                  {selectedProduct.category && (
                    <span className="tag">{selectedProduct.category}</span>
                  )}

                  <h2>{selectedProduct.name}</h2>

                  {/* Precio */}
                  <p className="modal-price">
                    ${selectedProduct.price.toLocaleString("es-CL")}
                    <span>
                      {" "}
                      / {selectedProduct.unitQuantity} {selectedProduct.unit}
                    </span>
                  </p>

                  {/* Descripción */}
                  <p className="modal-description">
                    {selectedProduct.description
                      ? selectedProduct.description
                      : "Producto del mar seleccionado y preparado bajo los estándares de calidad Artemares."}
                  </p>

                  {/* ================= NUTRICIÓN ================= */}
                  {selectedProduct.nutrition && (
                    <div className="nutrition-block">
                      <h3>Información nutricional (por 100 g)</h3>

                      <div className="nutrition-grid">
                        {selectedProduct.nutrition.calories != null && (
                          <div>
                            <span>Calorías</span>
                            <strong>
                              {selectedProduct.nutrition.calories} kcal
                            </strong>
                          </div>
                        )}

                        {selectedProduct.nutrition.protein != null && (
                          <div>
                            <span>Proteínas</span>
                            <strong>
                              {selectedProduct.nutrition.protein} g
                            </strong>
                          </div>
                        )}

                        {selectedProduct.nutrition.carbs != null && (
                          <div>
                            <span>Carbohidratos</span>
                            <strong>{selectedProduct.nutrition.carbs} g</strong>
                          </div>
                        )}

                        {selectedProduct.nutrition.fat != null && (
                          <div>
                            <span>Grasas</span>
                            <strong>{selectedProduct.nutrition.fat} g</strong>
                          </div>
                        )}

                        {selectedProduct.nutrition.sodium != null && (
                          <div>
                            <span>Sodio</span>
                            <strong>
                              {selectedProduct.nutrition.sodium} mg
                            </strong>
                          </div>
                        )}
                      </div>
                    </div>
                  )}

                  {/* ================= ACCIONES ================= */}
                  <div className="modal-actions">
                    {cart[selectedProduct.id] ? (
                      /* ===== YA EN PEDIDO ===== */
                      <div className="qty-control">
                        <button
                          onClick={() =>
                            handleUpdateCartItem(selectedProduct.id, -1)
                          }
                        >
                          −
                        </button>

                        <input
                          type="number"
                          value={cart[selectedProduct.id]}
                          readOnly
                        />

                        <button
                          onClick={() =>
                            handleUpdateCartItem(selectedProduct.id, 1)
                          }
                        >
                          +
                        </button>
                      </div>
                    ) : (
                      /* ===== NO ESTÁ EN PEDIDO ===== */
                      <button
                        className="add-cart-btn primary"
                        onClick={() => handleAddToCart(selectedProduct.id)}
                      >
                        Agregar al pedido
                      </button>
                    )}
                  </div>

                  {/* ================= RECETAS ================= */}
                  {selectedProduct.recipes?.length > 0 && (
                    <div className="recipes-block">
                      <h3>Recetas sugeridas</h3>
                      <ul>
                        {selectedProduct.recipes.map((r) => (
                          <li
                            key={r.id}
                            className="recipe-link"
                            onClick={() => navigate(`/recetas/${r.id}`)}
                          >
                            {r.name}
                          </li>
                        ))}
                      </ul>
                    </div>
                  )}
                </div>
              </div>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>

      {/* CARRITO */}
      <AnimatePresence>
        {isCartOpen && (
          <motion.div
            className="cart-overlay"
            onClick={() => setIsCartOpen(false)}
          >
            <motion.div
              className="cart-panel"
              onClick={(e) => e.stopPropagation()}
            >
              {/* HEADER */}
              <div className="cart-header">
                <h2>Tu pedido</h2>
                <button
                  className="cart-close"
                  onClick={() => setIsCartOpen(false)}
                >
                  ✕
                </button>
              </div>

              {/* BODY */}
              <div className="cart-body">
                {cartItems.length === 0 ? (
                  <div className="cart-empty">
                    <p>Pedido vacío</p> 
                    <span>Agrega productos para comenzar</span>
                  </div>
                ) : (
                  cartItems.map(({ product, quantity }) => (
                    <div className="cart-item" key={product.id}>
  
                      {/* IMAGEN SMALL */}
                      <div className="cart-item-thumb">
                        <img
                          src={
                            product.images?.small ||
                            product.images?.medium ||
                            "/images/placeholder-product.jpg"
                          }
                          alt={product.name}
                          loading="lazy"
                        />
                      </div>

                      {/* INFO */}
                      <div className="cart-item-info">
                        <span className="cart-item-name">{product.name}</span>
                        <span className="cart-item-price">
                          ${product.price.toLocaleString("es-CL")} /{" "}
                          {product.unitQuantity} {product.unit}
                        </span>
                      </div>

                      {/* CANTIDAD */}
                      <div className="qty-control small">
                        <button onClick={() => handleUpdateCartItem(product.id, -1)}>−</button>
                        <input type="number" value={quantity} readOnly />
                        <button onClick={() => handleUpdateCartItem(product.id, 1)}>+</button>
                      </div>
                    </div>

                  ))
                )}
              </div>

              {/* FOOTER */}
              <div className="cart-footer">
                <div className="cart-total">
                  <span>Total</span>
                  <strong>${cartTotal.toLocaleString("es-CL")}</strong>
                </div>

                <button
                  className="whatsapp-btn"
                  onClick={handleSendWhatsapp}
                  disabled={cartItems.length === 0}
                >
                  Enviar pedido por WhatsApp
                </button>
              </div>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>
      {/* ===== BOTÓN FLOTANTE DE PEDIDO ===== */}
      {!isCartOpen && (
        <button
          className="floating-cart-btn"
          onClick={() => setIsCartOpen(true)}
        > 
          🛒 Pedido ({totalItemsInCart})
        </button>
      )}
    </div>
  );
}
