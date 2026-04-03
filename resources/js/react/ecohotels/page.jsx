// Utilidad para mostrar el promedio y cantidad de reseñas igual que en lugares
const renderEcohotelRating = (ecohotel) => {
  // Si hay array de reseñas, calcular promedio y cantidad
  if (Array.isArray(ecohotel.reviews) && ecohotel.reviews.length > 0) {
    const total = ecohotel.reviews.reduce((sum, r) => sum + (Number(r.rating) || 0), 0);
    const avg = total / ecohotel.reviews.length;
    return (
      <span>
        <span style={{ color: '#ffc107', fontWeight: 'bold', marginRight: 2 }}>★</span>
        <span style={{ color: '#222', fontWeight: 'bold' }}>{avg.toFixed(1)}</span>
        {" "}
        <span style={{ fontSize: '0.95em', color: '#888' }}>({ecohotel.reviews.length} reseña{ecohotel.reviews.length === 1 ? '' : 's'})</span>
      </span>
    );
  }
  // Si existen los campos del backend, usarlos
  if (typeof ecohotel.average_rating !== 'undefined' && typeof ecohotel.reviews_count !== 'undefined') {
    if (ecohotel.reviews_count === 0 || ecohotel.average_rating === 0 || ecohotel.average_rating === null) {
      return <span>Sin reseñas</span>;
    }
    return (
      <span>
        <span style={{ color: '#ffc107', fontWeight: 'bold', marginRight: 2 }}>★</span>
        <span style={{ color: '#222', fontWeight: 'bold' }}>{parseFloat(ecohotel.average_rating).toFixed(1)}</span>
        {" "}
        <span style={{ fontSize: '0.95em', color: '#888' }}>({ecohotel.reviews_count} reseña{ecohotel.reviews_count === 1 ? '' : 's'})</span>
      </span>
    );
  }
  return <span>Sin reseñas</span>;
};
import React, { useState, useEffect } from "react";
import { Link, useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import Header from "../components/Header/Header";
import Header2 from "../components/Header2/Header2";
import Footer from "../components/Footer/Footer";
import "./page.css";

const EcohotelsPage = () => {
  const navigate = useNavigate();
  const { isAuthenticated, user } = useAuth();
  const [ecohotels, setEcohotels] = useState([]);
  const [loading, setLoading] = useState(true);
  const [message, setMessage] = useState("");
  // Eliminado filtro de categorías

  useEffect(() => {
    loadEcohotels();
  }, []);

  const loadEcohotels = async () => {
    try {
      setLoading(true);
      const response = await fetch("/api/ecohotels");
      const data = await response.json();
      setEcohotels(Array.isArray(data) ? data : []);
      setLoading(false);
    } catch (error) {
      console.error("Error al cargar ecohoteles:", error);
      setMessage("Error al cargar ecohoteles");
      setLoading(false);
    }
  };

  // Eliminado filtro de categorías, mostrar todos los ecohoteles
  const ecohotelesFiltrados = ecohotels;

  if (loading) {
    return (
      <div className="page-layout">
        {isAuthenticated ? <Header2 /> : <Header />}
        <div className="page-content">
          <div className="loading">Cargando Ecohoteles...</div>
        </div>
        <Footer />
      </div>
    );
  }

  return (
    <div className="page-layout">
      {isAuthenticated ? <Header2 /> : <Header />}

      <main className="page-content">
        <div className="lugares-container">
          <div className="lugares-header">
            <h1>Ecohoteles</h1>
            <p>Descubre alojamientos ecológicos en Risaralda</p>
          </div>

          {message && (
            <div className={`message ${message.toLowerCase().includes("error") ? "error" : "success"}`}>
              {message}
            </div>
          )}

          {/* Filtros por categoría eliminados */}

          {/* Grid de ecohoteles */}
          <div className="lugares-grid">
            {ecohotelesFiltrados.length === 0 ? (
              <div className="no-results">
                <p>No se encontraron ecohoteles</p>
              </div>
            ) : (
              ecohotelesFiltrados.map((ecohotel) => (
                <div key={ecohotel.id} className="lugar-card">
                  <Link to={`/ecohoteles/${ecohotel.id}`}>
                    <div className="lugar-image">
                      <img
                        src={ecohotel.image || "/imagenes/placeholder.jpg"}
                        alt={ecohotel.name}
                        onError={(e) => {
                          e.target.src = "/imagenes/placeholder.jpg";
                        }}
                      />
                    </div>
                    <div className="lugar-info">
                      <h3>{ecohotel.name}</h3>
                      {/* Promedio de reseñas igual que en lugares */}
                      <div style={{ fontSize: '0.95em', color: '#888', margin: '4px 0 8px 16px', textAlign: 'left', width: 'auto' }}>
                        {renderEcohotelRating(ecohotel)}
                      </div>
                      {ecohotel.location && (
                        <p className="lugar-location">📍 {ecohotel.location}</p>
                      )}
                      {ecohotel.telefono && (
                        <p className="lugar-phone">📞 {ecohotel.telefono}</p>
                      )}
                      {ecohotel.categories && ecohotel.categories.length > 0 && (
                        <div className="lugar-categories">
                          {ecohotel.categories.map((cat) => (
                            <span key={cat.id} className="category-tag">
                              {cat.name}
                            </span>
                          ))}
                        </div>
                      )}
                    </div>
                  </Link>
                </div>
              ))
            )}
          </div>
        </div>
      </main>

      <Footer />
    </div>
  );
};

export default EcohotelsPage;
