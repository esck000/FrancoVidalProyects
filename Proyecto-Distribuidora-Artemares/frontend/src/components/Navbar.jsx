import { Link, useLocation } from "react-router-dom";
import { useEffect, useRef, useState } from "react";
import "./Navbar.css";

export default function Navbar() {
  const location = useLocation();
  const lastScrollY = useRef(0);
  const [hidden, setHidden] = useState(false);

  const shouldAutoHide =
    location.pathname.startsWith("/productos") ||
    location.pathname.startsWith("/recetas");

  useEffect(() => {
    if (!shouldAutoHide) {
      setHidden(false);
      return;
    }

    const handleScroll = () => {
      const currentY = window.scrollY;

      if (currentY > lastScrollY.current && currentY > 80) {
        // bajando
        setHidden(true);
      } else {
        // subiendo
        setHidden(false);
      }

      lastScrollY.current = currentY;
    };

    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, [shouldAutoHide]);

  return (
    <nav className={`navbar ${hidden ? "navbar--hidden" : ""}`}>
      <div className="navbar-content">
        <div className="logo-container">
          <img
            src="/images/Logosinfondo.jpg"
            alt="Logo Artemares"
            className="logo-img"
          />
        </div>

        <ul className="nav-links">
          <li>
            <Link to="/">Inicio</Link>
          </li>
          <li>
            <Link to="/productos">Catálogo</Link>
          </li>
          <li>
            <Link to="/recetas">Recetas</Link>
          </li>
        </ul>
      </div>
    </nav>
  );
}
