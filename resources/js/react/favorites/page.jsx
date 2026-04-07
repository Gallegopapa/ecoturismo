import React, { useState, useEffect } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { favoritesService } from '../services/api';
import Header2 from '../components/Header2/Header2';
import Footer from '../components/Footer/Footer';
import ReservationModal from '../components/ReservationModal';
import { resolvePlaceImage } from '../utils/imageUtils';
import '../places2/paraisosAcuaticos/lugares.css';
import './page.css';

const FavoritesPage = () => {
  const navigate = useNavigate();
  const { isAuthenticated, loading: authLoading } = useAuth();
  const [favorites, setFavorites] = useState([]);
  const [loading, setLoading] = useState(true);
  const [message, setMessage] = useState('');
  const [reservationModal, setReservationModal] = useState({ isOpen: false, place: null });

  useEffect(() => {
    if (!authLoading && !isAuthenticated) {
      navigate('/login', { replace: true });
    }
  }, [isAuthenticated, authLoading, navigate]);

  useEffect(() => {
    if (isAuthenticated) {
      loadFavorites();
    }
  }, [isAuthenticated]);

  const loadFavorites = async () => {
    try {
      setLoading(true);
      const data = await favoritesService.getAll();
      let favoritesData = Array.isArray(data) ? data : [];
      
      // Usar resolvePlaceImage para resolver la imagen de cada lugar
      favoritesData = favoritesData.map((favorite) => {
        const place = favorite.place || {};
        const imagenFinal = resolvePlaceImage(place);
        
        return {
          ...favorite,
          place: {
            ...place,
            imagen: imagenFinal,
          }
        };
      });
      
      setFavorites(favoritesData);
    } catch (error) {
      console.error('Error al cargar favoritos:', error);
      setMessage('Error al cargar los favoritos');
    } finally {
      setLoading(false);
    }
  };

  const handleRemove = async (favoriteId, placeId) => {
    try {
      await favoritesService.remove(placeId);
      setMessage('Eliminado de favoritos');
      await loadFavorites();
      setTimeout(() => setMessage(''), 3000);
    } catch (error) {
      console.error('Error al eliminar favorito:', error);
      setMessage('Error al eliminar favorito');
      setTimeout(() => setMessage(''), 3000);
    }
  };

  if (authLoading || loading) {
    return (
      <div className="page-layout">
        <Header2 />
        <div className="page-content" style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', marginTop: '100px' }}>
          <p>Cargando favoritos...</p>
        </div>
        <Footer />
      </div>
    );
  }

  if (!isAuthenticated) {
    return null;
  }

  return (
    <div className="page-layout">
      <Header2 />
      <div className="page-content favorites-container" style={{ marginTop: '100px' }}>
        <h1>Mis Favoritos</h1>

        {message && (
          <div style={{
            padding: "12px 20px",
            margin: "20px 0",
            backgroundColor: message.includes("✅") ? "#d4edda" : "#f8d7da",
            color: message.includes("✅") ? "#155724" : "#721c24",
            borderRadius: "8px",
            textAlign: "center",
            fontWeight: "500"
          }}>
            {message}
          </div>
        )}

        {favorites.length === 0 ? (
          <div style={{ 
            textAlign: 'center', 
            padding: '60px 20px',
            background: '#f8f9fa',
            borderRadius: '12px',
            marginTop: '30px'
          }}>
            <p style={{ fontSize: '1.2em', color: '#666', marginBottom: '20px' }}>
              No tienes lugares favoritos aún
            </p>
            <button 
              onClick={() => navigate('/lugares')}
              style={{
                padding: '12px 24px',
                background: '#2ecc71',
                color: 'white',
                border: 'none',
                borderRadius: '8px',
                fontSize: '1rem',
                cursor: 'pointer',
                fontWeight: '600'
              }}
            >
              Explorar Lugares
            </button>
          </div>
        ) : (
          <div className="contenedor">
            <div className="cards">
              {favorites.map((favorite) => {
                const place = favorite.place || {};
                return (
                  <div className="card" key={favorite.id}>
                    <img 
                      src={place.imagen || place.image || "/imagenes/placeholder.svg"} 
                      alt={place.name || "Lugar"}
                      onError={(e) => {
                        e.target.src = "/imagenes/placeholder.svg";
                      }}
                    />
                    <h4>{place.name || "Lugar sin nombre"}</h4>
                    <p className="ubicacion-text">{place.location || "Sin ubicación"}</p>
                    <p className="descripcion">
                      {place.description ? place.description : "Sin descripción disponible"}
                    </p>

                    <div className="card-actions">
                      <div className="action-buttons">
                        <a 
                          href="/mapa" 
                          className="map-button"
                          title="Ver en mapa"
                        >
                          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="currentColor"/>
                          </svg>
                          <span>Mapa</span>
                        </a>
                      </div>
                      <button 
                        className="favorito" 
                        onClick={() => handleRemove(favorite.id, place.id || favorite.place_id)}
                        title="Quitar de favoritos"
                      >
                        ❤️
                      </button>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
        )}

        <div style={{ textAlign: 'center', marginTop: '30px' }}>
          <Link to="/lugares">
            <button className="volver2">Volver a Lugares</button>
          </Link>
        </div>
      </div>

      {/* Modal de reserva */}
      {reservationModal.isOpen && reservationModal.place && (
        <ReservationModal
          place={reservationModal.place}
          isOpen={reservationModal.isOpen}
          onClose={() => setReservationModal({ isOpen: false, place: null })}
          onSuccess={(reservation) => {
            setMessage(`Reserva creada para ${reservationModal.place.name}`);
            setTimeout(() => setMessage(""), 3000);
          }}
        />
      )}

      <Footer />
    </div>
  );
};

export default FavoritesPage;
