import React, { useState, useEffect } from "react";
import { useNavigate, Link } from "react-router-dom";
import Header2 from "@/react/components/Header2/Header2";
import Footer from "@/react/components/Footer/Footer";
import { useAuth } from "@/react/context/AuthContext";
import { favoritesService, placesService } from "@/react/services/api";
import ReservationModal from "@/react/components/ReservationModal";
import { resolvePlaceImage, getLocalFallbackImage } from "@/react/utils/imageUtils";
import "./lugares.css";

export default function LugaresMontanososPage() {
  const navigate = useNavigate();
  const { isAuthenticated } = useAuth();
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
      titulo: "Alto Del Nudo",
      ubicacion: "Pereira, Risaralda",
      descripcion: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.",
      imagen: "/imagenes/nudo.jpg",
      mapa: "https://maps.app.goo.gl/f3w9DC9zRFUMDEzv9",
    },
    {
      id: 2,
      titulo: "Alto Del Toro",
      ubicacion: "Pereira, Risaralda",
      descripcion: "Quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.",
      imagen: "/imagenes/toro.jpg",
      mapa: "https://maps.app.goo.gl/DyrpMApsB3Mz1hmV6",
    },
    {
      id: 3,
      titulo: "La Divisa De Don Juan",
      ubicacion: "Vía Altagracia, Altagracia, Pereira, Risaralda",
      descripcion: "Cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
      imagen: "/imagenes/divisa3.jpeg",
      mapa: "https://maps.app.goo.gl/7seGQZ2LHdAMoNqJ6",
    },
    {
      id: 4,
      titulo: "Cerro Batero",
      ubicacion: "Quinchía, Risaralda",
      descripcion: "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore.",
      imagen: "/imagenes/batero.jpg",
      mapa: "https://maps.app.goo.gl/q6mCEfzAjGfJkuh56",
    },
    {
      id: 5,
      titulo: "Reserva Forestal La Nona",
      ubicacion: "Marsella, Risaralda",
      descripcion: "Veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit.",
      imagen: "/imagenes/lanona5.jpg",
      mapa: "https://maps.app.goo.gl/XacC2ScWUKbcgutv8",
    },
    {
      id: 6,
      titulo: "Reserva Natural Cerro Gobia",
      ubicacion: "Quinchía, Risaralda",
      descripcion: "Sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet.",
      imagen: "/imagenes/gobia.jpg",
      mapa: "https://maps.app.goo.gl/8BF3SXF4RTpRbVxeA",
    },
    {
      id: 7,
      titulo: "Kaukitá Bosque Reserva",
      ubicacion: "Pereira, Risaralda",
      descripcion: "Consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.",
      imagen: "/imagenes/kaukita3.jpg",
      mapa: "https://maps.app.goo.gl/K3C93FAURYARtAvv6",
    },
    {
      id: 8,
      titulo: "Reserva Natural DMI Agualinda",
      ubicacion: "Apía, Risaralda",
      descripcion: "Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur.",
      imagen: "/imagenes/distritomanejo8.jpg",
      mapa: "https://maps.app.goo.gl/UNc9cTccV6LuGySU7",
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
      // Obtener categoría "lugares-montanosos" primero
      const categoriesResponse = await fetch('/api/categories');
      const categories = await categoriesResponse.json();
      const montanososCategory = categories.find(cat => cat.slug === 'lugares-montanosos');

      if (montanososCategory) {
        const data = await placesService.getAll({ category_id: montanososCategory.id });
        if (data && data.length > 0) {
          const withImages = data.map((item, index) => {
            const imagenFinal = resolvePlaceImage(item, 'lugares-montanosos', index);
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
        const favorite = favoritos.find(f => f.place_id === lugar.id || f.place?.id === lugar.id);
        const placeId = favorite?.place_id || favorite?.place?.id || lugar.id;
        await favoritesService.remove(placeId);
        setFavoritos(favoritos.filter(f => (f.place_id !== placeId && f.place?.id !== placeId)));
        setMessage("Eliminado de favoritos");
      } else {
        await favoritesService.add(lugar.id);
        await loadFavorites();
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
        <h1>Lugares Montañosos</h1>

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
