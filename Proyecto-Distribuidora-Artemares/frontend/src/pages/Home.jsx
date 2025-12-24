// src/pages/Home.jsx
import { motion } from "framer-motion";
import "./Home.css";
import { Button } from "../components/Button";

export default function Home() {
  // Scroll simple: baja hasta el inicio de la sección (sin offset)
  const scrollToSection = (id) => {
    const el = document.getElementById(id);
    if (!el) return;

    const navbar = document.querySelector(".navbar");
    const navHeight = 101;

    const y = el.offsetTop - navHeight;

    window.scrollTo({
      top: y,
      behavior: "smooth",
    });
  };

  return (
    <>
      {/* ================= HERO ================= */}
      <section className="hero-video">
        <video className="hero-video__bg" autoPlay loop muted playsInline>
          <source src="/videos/video-3.mp4" type="video/mp4" />
        </video>

        <div className="hero-video__overlay" />

        <motion.div
          className="hero-video__content"
          initial={{ opacity: 0, y: 25 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 1, ease: "easeOut" }}
        >
          <span className="hero-kicker">CALIDAD · FRESCURA · CONFIANZA</span>

          <h1 className="hero-title">
            Productos del mar seleccionados
            <br />
            para tu cocina
          </h1>

          <p className="hero-subtitle">
            Frescura garantizada, trazabilidad completa y entregas confiables
            para restaurantes, hoteles y hogares.
          </p>

          <div className="hero-cta">
            <Button
              buttonStyle="btn--primary"
              buttonSize="btn--medium"
              onClick={() => scrollToSection("about")}
            >
              Conoce más
            </Button>
          </div>
        </motion.div>
      </section>

      {/* ================= QUIÉNES SOMOS ================= */}
      <section id="about" className="section section--about">
        <motion.div
          className="section-inner section-grid"
          initial={{ opacity: 0, y: 35 }}
          whileInView={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.8, ease: "easeOut" }}
          viewport={{ once: true }}
        >
          <div className="section-media">
            <img
              src="/images/Mariscos1.jpg"
              alt="Productos del mar de Artemares"
            />
          </div>

          <div className="section-text">
            <p className="section-kicker">Nuestra empresa</p>
            <h2>Distribuidora Artemares</h2>

            <p>
              Nos especializamos en la distribución de pescados y mariscos
              frescos, trabajando con proveedores locales y asegurando una
              cadena de frío rigurosa en cada etapa.
            </p>

            <p>
              Somos reconocidos por nuestra puntualidad, comunicación directa y
              servicio personalizado.
            </p>

            <ul className="section-list">
              <li>Selección profesional de productos del mar.</li>
              <li>Cadena de frío garantizada.</li>
              <li>Atención rápida y cercana.</li>
            </ul>
          </div>
        </motion.div>

        {/* Flecha para seguir a INFO (misma animación de scroll que el botón) */}
        <div className="scroll-next" onClick={() => scrollToSection("info")}>
          <span></span>
        </div>
      </section>

      {/* ================= INFO ================= */}
      <section id="info" className="section section--info">
        <div className="section-inner section-grid section-grid--two">
          <motion.div
            className="info-card"
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.7, ease: "easeOut" }}
            viewport={{ once: true }}
          >
            <p className="section-kicker">¿Dónde nos encuentras?</p>
            <h3>Nuestra ubicación</h3>
            <p className="info-text">
              Operamos desde <strong>Penco, Región del Biobío</strong> especificamente en <strong>Sargento Candelaria 60</strong>, con
              entregas programadas para restaurantes y hogares.
            </p>
            <p className="info-text">
              Asimismo, ofrecemos abastecimiento continuo para clientes
              frecuentes.
            </p>
          </motion.div>

          <motion.div
            className="info-card"
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.7, delay: 0.15, ease: "easeOut" }}
            viewport={{ once: true }}
          >
            <p className="section-kicker">Contáctanos</p>
            <h3>Comunícate con nosotros</h3>

            <div className="contact-lines">
              <p>
                <strong>Teléfono:</strong> +56 9 5439 9106
              </p>
              <p>
                <strong>Cobertura:</strong> Tomé, Penco, Lirquén y Concepción.
              </p>
              <p>
                <strong>Horario:</strong> Lunes a domingo · 09:00 a 19:00
              </p>
            </div>

            <p className="info-text">
              Resolvemos tus consultas rápidamente y coordinamos tu pedido sin
              complicaciones.
            </p>
          </motion.div>
        </div>
      </section>
    </>
  );
}
