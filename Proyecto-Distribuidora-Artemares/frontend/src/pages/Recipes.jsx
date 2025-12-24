import { useEffect, useMemo, useState } from "react";
import { motion, AnimatePresence } from "framer-motion";
import "./Recipes.css";
import { useNavigate, useParams } from "react-router-dom";

/* ======================================================
   Utils
====================================================== */

function normalizeText(text = "") {
  return text
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .toLowerCase();
}

/**
 * Mapea receta desde la API al formato frontend
 */
function mapApiRecipeToFrontend(r) {
  return {
    id: r.id,
    name: r.name,
    description: r.description ?? "",

    // ingredientes como texto o array de strings
    ingredients:
      typeof r.ingredients === "string" && r.ingredients.trim() !== ""
        ? r.ingredients
            .split(",")
            .map(i => i.trim())
            .join(", ")
        : "",


    // productos relacionados reales
    products: r.products ?? [],

    images: r.images ?? {
      small: null,
      medium: null,
      large: null,
    },
  };
}


/* ======================================================
   Component
====================================================== */

export default function Recipes({ products = [], openProductModal }) {
  const [recipes, setRecipes] = useState([]);
  const [loading, setLoading] = useState(true);

  const [search, setSearch] = useState("");
  const [filter, setFilter] = useState("Todos");
  const [selectedRecipe, setSelectedRecipe] = useState(null);

  const navigate = useNavigate();
  const { id } = useParams();


  /* ======================================================
     Fetch API
  ====================================================== */

  useEffect(() => {
    const fetchRecipes = async () => {
      try {
        setLoading(true);

        const res = await fetch("http://localhost:8765/api/recipes.json");
        if (!res.ok) throw new Error("Error API recetas");

        const json = await res.json();
        const mapped = json.recipes.map(mapApiRecipeToFrontend);

        setRecipes(mapped);
      } catch (err) {
        console.error("Error cargando recetas:", err);
      } finally {
        setLoading(false);
      }
    };

    fetchRecipes();
  }, []);

  useEffect(() => {
    if (!id) return;
    if (recipes.length === 0) return;

    const recipe = recipes.find(
      (r) => Number(r.id) === Number(id)
    );

    if (recipe) {
      setSelectedRecipe(recipe);
    }
  }, [id, recipes]);

  /* ======================================================
     Filtros
  ====================================================== */

  const filteredRecipes = useMemo(() => {
    const s = normalizeText(search);

    return recipes.filter((r) => {
      const matchesSearch = normalizeText(r.name).includes(s);

      const matchesFilter =
        filter === "Todos" ||
        r.products.some((p) => String(p.id) === String(filter));

      return matchesSearch && matchesFilter;
    });
  }, [search, filter, recipes]);

  /* ======================================================
     Render
  ====================================================== */

  return (
    <div className="recipes-page">
      {/* ================= HEADER ================= */}

      <div className="recipes-header">
        <div className="recipes-header-main">
          <div>
          <h1>Recetas Artemares</h1>
              <p className="subtitle">
               Aprende a preparar platos deliciosos usando nuestros productos del mar.
              </p>
            </div>
        </div>
        <div className="recipes-filters">
          <input
            type="text"
            placeholder="Buscar receta..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
          />
        </div>
      </div>

      {/* ================= GRID ================= */}

      {loading ? (
        <div className="grid-loader">
          Cargando recetas…
        </div>
      ) : (
        <div className="recipes-grid">
          {filteredRecipes.map((r) => (
            <motion.div
              key={r.id}
              className="recipe-card"
              whileHover={{ y: -4, scale: 1.01 }}
              transition={{ duration: 0.2 }}
              onClick={() => navigate(`/recetas/${r.id}`)}
            >
              {/* 🔥 CONTENEDOR DE IMAGEN */}
              <div className="recipe-image">
                <img
                  src={r.images?.medium || "/images/placeholder-recipe.jpg"}
                  alt={r.name}
                  loading="lazy"
                />
              </div>

              <div className="recipe-body">
                <h3>{r.name}</h3>
              </div>
              <div className="recipe-actions">
                  <button className="recipe-btn">Ver receta</button>
              </div>
            </motion.div>
          ))}
        </div>
      )}

      {/* ================= MODAL ================= */}

      <AnimatePresence>
        {selectedRecipe && (
          <motion.div
            className="recipe-modal-overlay"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={() => {
              setSelectedRecipe(null);
              navigate("/recetas");
            }}

          >
            <motion.div
              className="recipe-modal"
              initial={{ scale: 0.92, y: 20 }}
              animate={{ scale: 1, y: 0 }}
              exit={{ scale: 0.92, y: 20 }}
              transition={{ duration: 0.25 }}
              onClick={(e) => e.stopPropagation()}
            >
              <button
                className="recipe-close-btn"
                onClick={() => {
                  setSelectedRecipe(null);
                  navigate("/recetas");
                }}

              >
                ✕
              </button>

              <div className="recipe-modal-layout">
                {/* IMAGEN */}
                <div className="recipe-modal-image">
                  <img
                    src={
                      selectedRecipe.images?.large ||
                      selectedRecipe.images?.medium ||
                      selectedRecipe.images?.small
                    }
                    alt={selectedRecipe.name}
                  />
                </div>

                {/* INFO */}
                <div className="recipe-modal-info">

                  <h2 className="recipe-title">{selectedRecipe.name}</h2>

                  <p className="recipe-detail-desc">
                    {selectedRecipe.description}
                  </p>

                  {/* INGREDIENTES TEXTO */}
                  {selectedRecipe.ingredients && (
                    <div className="recipe-section">
                      <h3 className="recipe-section-title">Ingredientes</h3>
                      <p className="recipe-ingredients-text">
                        {selectedRecipe.ingredients}
                      </p>
                    </div>
                  )}

                  {/* PRODUCTOS RELACIONADOS */}
                  {selectedRecipe.products?.length > 0 && (
                    <div className="recipe-section">
                      <h3 className="recipe-section-title">Productos relacionados</h3>

                      <ul className="related-products-list">
                        {selectedRecipe.products.map((p) => (
                          <li
                            key={p.id}
                            className="related-product-item-product-link"
                            onClick={() => {
                              setSelectedRecipe(null);
                              navigate(`/productos/${p.id}`);
                            }}
                          >
                            {p.name}
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

    </div>
  );
}
