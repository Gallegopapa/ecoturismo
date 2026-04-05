import React, { useState, useEffect } from "react";
import { useNavigate, Link } from "react-router-dom";
import Header2 from "@/react/components/Header2/Header2";
import Footer from "@/react/components/Footer/Footer";
import { useAuth } from "@/react/context/AuthContext";
import { favoritesService, placesService } from "@/react/services/api";
import ReservationModal from "@/react/components/ReservationModal";
import { resolvePlaceImage, getLocalFallbackImage } from "@/react/utils/imageUtils";
import "./lugares.css";

export default function ParquesYMasPage() {
  const navigate = useNavigate();
  const { isAuthenticated, user } = useAuth();
  const [lugares, setLugares] = useState([]);
  const [favoritos, setFavoritos] = useState([]);
  const [popupVisible, setPopupVisible] = useState(false);
  const [loading, setLoading] = useState(true);
  const [message, setMessage] = useState("");
  const [reservationModal, setReservationModal] = useState({ isOpen: false, place: null });

  // Lugares hardcodeados como fallback
  const lugaresFallback = [
    {
      id: 1,
      titulo: "Parque Nacional Natural Tatamá",
      ubicacion: "Pueblo Rico, Risaralda",
      descripcion: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
      imagen: "/imagenes/tatama.jpg",
      mapa: "https://maps.app.goo.gl/hPSphPUBmXGBqeGJ6",
    },
    {
      id: 2,
      titulo: "Parque Las Araucarias",
      ubicacion: "Santa Rosa de Cabal, Risaralda",
      descripcion: "Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
      imagen: "/imagenes/araucarias.jpg",
      mapa: "https://maps.app.goo.gl/SDZUo3UZpzU3YWq28",
    },
    {
      id: 3,
      titulo: "Parque Regional Natural Cuchilla de San Juan",
      ubicacion: "Belén de Umbría, Risaralda",
      descripcion: "Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.",
      imagen: "/imagenes/cuchilla.jpg",
      mapa: "https://maps.app.goo.gl/2uWtBq8BNCCHuCft9",
    },
    {
      id: 4,
      titulo: "Parque Natural Regional Santa Emilia",
      ubicacion: "Belén de Umbría, Risaralda",
      descripcion: "Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
      imagen: "/imagenes/santaemilia2.jpg",
      mapa: "https://maps.app.goo.gl/5G6AXY18b8hAwdfW6",
    },
    {
      id: 5,
      titulo: "Jardín Botánico UTP",
      ubicacion: "Pereira, Risaralda",
      descripcion: "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.",
      imagen: "/imagenes/jardin.jpeg",
      mapa: "https://maps.app.goo.gl/hhkmfB9owU9PcB6Z7",
    },
    {
      id: 6,
      titulo: "Jardín Botánico De Marsella",
      ubicacion: "Marsella, Risaralda",
      descripcion: "Totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt.",
      imagen: "/imagenes/jardinmarsella2.jpg",
      mapa: "https://maps.app.goo.gl/L2ysAcHE6EvuNq3U7",
    },
  ];

  // Cargar lugares y favoritos al iniciar
  useEffect(() => {
    loadPlaces();
    if (isAuthenticated) {
      loadFavorites();
    } else {
      setLoading(false);
    }
  }, [isAuthenticated]);

  const loadPlaces = async () => {
    try {
      setLoading(true);
      // Obtener categoría "parques-y-mas" primero
      const categoriesResponse = await fetch('/api/categories');
      const categories = await categoriesResponse.json();
      const parquesCategory = categories.find(cat => cat.slug === 'parques-y-mas');

      if (parquesCategory) {
        const data = await placesService.getAll({ category_id: parquesCategory.id });
        if (data && data.length > 0) {
          const withImages = data.map((item, index) => {
            const imagenFinal = resolvePlaceImage(item, 'parques-y-mas', index);
            return {
              ...item,
              description: item.description || '',
              name: item.name || item.titulo || item.nombre || '',
              location: item.location || item.ubicacion || '',
              imagen: imagenFinal,
              image: imagenFinal,
            };
          });
          setLugares(withImages);
        } else {
          // Si no hay datos, mostrar lista vacía en lugar de fallback Lorem ipsum
          setLugares([]);
        }
      } else {
        // Si no encuentra la categoría, mostrar lista vacía
        setLugares([]);
      }
    } catch (error) {
      console.error("Error al cargar lugares:", error);
      setLugares([]);
    } finally {
      setLoading(false);
    }
  };

  const loadFavorites = async () => {
    try {
      setLoading(true);
      const data = await favoritesService.getAll();
      setFavoritos(data);
    } catch (error) {
      console.error("Error al cargar favoritos:", error);
      setMessage("Error al cargar favoritos");
    } finally {
      setLoading(false);
    }
  };

  const toggleFavorito = async (lugar) => {
    if (!isAuthenticated) {
      setMessage("Debes iniciar sesión para agregar favoritos");
      setTimeout(() => {
        navigate("/login");
      }, 1500);
      return;
    }

    const isFavorite = favoritos.some(f => f.place_id === lugar.id || f.place?.id === lugar.id);

    try {
      if (isFavorite) {
        // Eliminar de favoritos
        const favorite = favoritos.find(f => f.place_id === lugar.id || f.place?.id === lugar.id);
        const placeId = favorite?.place_id || favorite?.place?.id || lugar.id;
        await favoritesService.remove(placeId);
        setFavoritos(favoritos.filter(f => (f.place_id !== placeId && f.place?.id !== placeId)));
        setMessage("Eliminado de favoritos");
      } else {
        // Agregar a favoritos
        await favoritesService.add(lugar.id);
        await loadFavorites(); // Recargar para obtener el favorito completo
        setMessage("Agregado a favoritos");
      }
      setTimeout(() => setMessage(""), 3000);
    } catch (error) {
      console.error("Error al actualizar favorito:", error);
      setMessage(error.response?.data?.message || "Error al actualizar favorito");
      setTimeout(() => setMessage(""), 3000);
    }
  };

  const eliminarFavorito = async (favoriteId, placeId) => {
    try {
      await favoritesService.remove(placeId);
      setFavoritos(favoritos.filter(f => f.id !== favoriteId));
      setMessage("Eliminado de favoritos");
      setTimeout(() => setMessage(""), 3000);
    } catch (error) {
      console.error("Error al eliminar favorito:", error);
      setMessage("Error al eliminar favorito");
      setTimeout(() => setMessage(""), 3000);
    }
  };

  const isFavorite = (lugarId) => {
    return favoritos.some(f => f.place_id === lugarId || f.place?.id === lugarId);
  };

  if (loading && isAuthenticated) {
    return (
      <div className="page-layout">
        <Header2 />
        <div className="page-content" style={{ marginTop: "100px", textAlign: "center", padding: "50px" }}>
          <p>Cargando favoritos...</p>
        </div>
        <Footer />
      </div>
    );
  }

  return (
    <div className="page-layout">
      <Header2 />
      <div className="page-content contenedorTodo" style={{ marginTop: "100px" }}>
        <h1>Parques y Más</h1>

        {message && (
          <div style={{
            padding: "10px",
            margin: "10px 0",
            backgroundColor: message.includes("Error") ? "#fee" : "#efe",
            color: message.includes("Error") ? "#c00" : "#0a0",
            borderRadius: "5px",
            textAlign: "center"
          }}>
            {message}
          </div>
        )}

        {isAuthenticated && (
          <button
            className="mostrar-favoritos"
            onClick={() => setPopupVisible(true)}
          >
            Favoritos (<span>{favoritos.length}</span>)
          </button>
        )}

        <div className="contenedor">
          <div className="cards">
            {lugares.map((lugar) => (
              <div className="card" key={lugar.id} onClick={() => navigate(`/lugares/${lugar.id}`)} style={{ cursor: 'pointer' }}>
                <img src={lugar.imagen || lugar.image || "/imagenes/placeholder.svg"} alt={lugar.name || lugar.titulo} onError={(e) => { e.target.onerror = null; e.target.src = getLocalFallbackImage(lugar.name || lugar.titulo); }} />
                <h4>{lugar.name || lugar.titulo}</h4>
                {/* Rating debajo del título, pequeño y centrado */}
                <div style={{ fontSize: '0.95em', color: '#888', margin: '4px 0 8px 16px', textAlign: 'left', width: 'auto' }}>
                  {typeof lugar.average_rating !== 'undefined' && typeof lugar.reviews_count !== 'undefined' ? (
                    lugar.reviews_count === 0 || lugar.average_rating === 0 || lugar.average_rating === null ? (
                      <span>Sin reseñas</span>
                    ) : (
                      <span>
                        <span style={{ color: '#ffc107', fontWeight: 'bold', marginRight: 2 }}>★</span>
                        <span style={{ color: '#222', fontWeight: 'bold' }}>{parseFloat(lugar.average_rating).toFixed(1)}</span>
                        {" "}
                        <span style={{ fontSize: '0.95em', color: '#888' }}>({lugar.reviews_count} reseña{lugar.reviews_count === 1 ? '' : 's'})</span>
                      </span>
                    )
                  ) : (
                    <span>Sin reseñas</span>
                  )}
                </div>
                <p className="ubicacion-text">{lugar.location || lugar.ubicacion}</p>
                <p className="descripcion">{lugar.description || lugar.descripcion}</p>

                <div className="card-actions">
                  <div className="action-buttons">
                    <a
                      href="/mapa"
                      className="map-button"
                      title="Ver en mapa"
                      onClick={(e) => e.stopPropagation()}
                    >
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="currentColor" />
                      </svg>
                      <span>Mapa</span>
                    </a>
                    {/* Botón Ver Detalles eliminado, toda la tarjeta es clickeable */}
                  </div>
                  <button
                    className="favorito"
                    onClick={(e) => {
                      e.stopPropagation();
                      e.preventDefault();
                      toggleFavorito(lugar);
                    }}
                    title={isFavorite(lugar.id) ? "Quitar de favoritos" : "Agregar a favoritos"}
                  >
                    {isFavorite(lugar.id) ? "❤️" : "🤍"}
                  </button>
                </div>
              </div>
            ))}
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

        {/* Popup de favoritos */}
        {popupVisible && (
          <div className="popup-overlay" onClick={() => setPopupVisible(false)}>
            <div className="popup-content" onClick={(e) => e.stopPropagation()}>
              <button
                className="cerrar-popup"
                onClick={() => setPopupVisible(false)}
              >
                ✕
              </button>
              <h2>Mis Favoritos</h2>
              {favoritos.length === 0 ? (
                <p className="mensaje-vacio">No has agregado ningún lugar aún.</p>
              ) : (
                <ul className="favoritos-list">
                  {favoritos.map(f => {
                    const placeId = f.place_id || f.place?.id;
                    const placeName = f.place?.name || f.titulo || "Lugar sin nombre";
                    return (
                      <li key={f.id}>
                        {placeName}
                        <button
                          className="eliminar-favorito"
                          onClick={() => eliminarFavorito(f.id, placeId)}
                        >
                          ✕
                        </button>
                      </li>
                    );
                  })}
                </ul>
              )}
            </div>
          </div>
        )}
      </div>

      <Footer />
    </div>
  );
}

