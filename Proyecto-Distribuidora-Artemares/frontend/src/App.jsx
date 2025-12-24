import { BrowserRouter as Router, Routes, Route, useLocation } from "react-router-dom";
import Navbar from "./components/Navbar";
import Footer from "./components/Footer";
import Home from "./pages/Home";
import Products from "./pages/Products";
import Recipes from "./pages/Recipes";
import ScrollToTop from "./components/ScrollToTop"; // NUEVO

function AppContent() {
  const location = useLocation();
  const hideFooter = location.pathname === "/"; // Footer oculto solo en Home

  return (
    <div className="app-container">
      <Navbar />

      <main className="main-content">
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/productos" element={<Products />} />
          <Route path="/productos/:id" element={<Products />} />
          <Route path="/recetas" element={<Recipes />} />
          <Route path="/recetas/:id" element={<Recipes />} />
        </Routes>
      </main>

      {!hideFooter && <Footer />}
    </div>
  );
}

export default function App() {
  return (
    <Router>
      <ScrollToTop />  
      <AppContent />
    </Router>
  );
}
