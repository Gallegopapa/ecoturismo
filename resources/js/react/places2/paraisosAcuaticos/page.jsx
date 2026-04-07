import React, { useState, useEffect } from "react";
import { useNavigate, Link } from "react-router-dom";
import Header2 from "@/react/components/Header2/Header2";
import Footer from "@/react/components/Footer/Footer";
import { useAuth } from "@/react/context/AuthContext";
import { favoritesService, placesService } from "@/react/services/api";
import ReservationModal from "@/react/components/ReservationModal";
import { resolvePlaceImage, getLocalFallbackImage } from "@/react/utils/imageUtils";
import "./lugares.css";

export default function ParaisosAcuaticosPage() {
  const navigate = useNavigate();
  const { isAuthenticated } = useAuth();
  const [lugares, setLugares] = useState([]);
  const [favoritos, setFavoritos] = useState([]);
  const [popupVisible, setPopupVisible] = useState(false);
  const [loading, setLoading] = useState(true);
  const [message, setMessage] = useState("");
  const [updatingFavorites, setUpdatingFavorites] = useState({});
  const [reservationModal, setReservationModal] = useState({ isOpen: false, place: null });

  // Lugares hardcodeados como fallback (si no hay en BD)
  const lugaresFallback = [
    {
      id: 1,
      nombre: "Lago De La Pradera",
      ubicacion: "La Pradera - Dosquebradas, Risaralda",
      descripcion: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.",
      imagen: "/imagenes/Lago.jpeg",
      mapa: "https://maps.app.goo.gl/M6RgB1GUYqJwGdGfA",
    },
    {
      id: 2,
      nombre: "La Laguna Del Otún",
      ubicacion: "Pereira, Santa Rosa, Risaralda",
      descripcion: "Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.",
      imagen: "/imagenes/laguna.jpg",
      mapa: "https://maps.app.goo.gl/ndHDFrHHQYfNt8n19",
    },
    {
      id: 3,
      nombre: "Chorros De Don Lolo",
      ubicacion: "Santa Rosa, Risaralda",
      descripcion: "Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error.",
      imagen: "/imagenes/lolo-2.jpg",
      mapa: "https://maps.app.goo.gl/iraGYyGvchLDCFaj8",
    },
    {
      id: 4,
      nombre: "Termales de Santa Rosa",
      ubicacion: "Santa Rosa, Risaralda",
      descripcion: "Sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt.",
      imagen: "/imagenes/termaales.jpg",
      mapa: "https://maps.app.goo.gl/zTkAVYrmFBmFvJCv7",
    },
    {
      id: 5,
      nombre: "Parque Acuático Consota",
      ubicacion: "Pereira - Cerritos, Risaralda",
      descripcion: "Explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.",
      imagen: "/imagenes/consota.jpg",
      mapa: "https://maps.app.goo.gl/Xe4dhpqnBSzML98b8",
    },
    {
      id: 6,
      nombre: "Balneario Los Farallones",
      ubicacion: "La Virginia, Risaralda",
      descripcion: "Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore.",
      imagen: "/imagenes/farallones.jpeg",
      mapa: "https://maps.app.goo.gl/XbZoEF6SsNpzKCL88",
    },
    {
      id: 7,
      nombre: "Cascada Los Frailes",
      ubicacion: "La Florida - Pereira, Risaralda",
      descripcion: "Et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi.",
      imagen: "/imagenes/frailes3.jpg",
      mapa: "https://maps.app.goo.gl/PhcdF9sCzFxKAx3p7",
    },
    {
      id: 8,
      nombre: "Río San José",
      ubicacion: "Cordillera Central - Pereira, Risaralda",
      descripcion: "Consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas.",
      imagen: "/imagenes/sanjose3.jpg",
      mapa: "https://maps.app.goo.gl/LmncErfzPCRCvGvUA",
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

      // Obtener categoría "paraisos-acuaticos" primero
      const categoriesResponse = await fetch('/api/categories');
      const categories = await categoriesResponse.json();
      const acuaticosCategory = categories.find(cat => cat.slug === 'paraisos-acuaticos');

      if (acuaticosCategory) {
        const data = await placesService.getAll({ category_id: acuaticosCategory.id });
        if (data && data.length > 0) {
          const withImages = data.map((item, index) => {
            const imagenFinal = resolvePlaceImage(item, 'paraisos-acuaticos', index);
            return {
              ...item,
              // SÓLO usar description de la API, nunca fallback
              description: item.description || '',
              // Campos normalizados
              name: item.name || item.nombre || item.titulo || '',
              location: item.location || item.ubicacion || '',
              // Imagen final con fallback a placeholder
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

    const placeId = lugar.id;
    const isFavorite = favoritos.some(f => f.place_id === placeId || f.place?.id === placeId);

    // Actualización optimista (cambiar UI inmediatamente)
    setUpdatingFavorites({ ...updatingFavorites, [placeId]: true });

    // Crear una copia del estado actual para revertir si falla
    const previousFavorites = [...favoritos];

    try {
      if (isFavorite) {
        // Eliminar de favoritos - Actualizar estado INMEDIATAMENTE
        const favorite = favoritos.find(f => f.place_id === placeId || f.place?.id === placeId);
        const favoritePlaceId = favorite?.place_id || favorite?.place?.id || placeId;

        // Actualizar estado inmediatamente (optimistic update)
        setFavoritos(prev => prev.filter(f => (f.place_id !== favoritePlaceId && f.place?.id !== favoritePlaceId)));
        setMessage("Eliminado de favoritos");

        // Luego hacer la petición al servidor
        await favoritesService.remove(favoritePlaceId);
      } else {
        // Agregar a favoritos - Actualizar estado INMEDIATAMENTE
        const newFavorite = {
          id: Date.now(), // ID temporal
          place_id: placeId,
          place: {
            id: placeId,
            name: lugar.name || lugar.nombre,
          }
        };

        // Actualizar estado inmediatamente (optimistic update)
        setFavoritos(prev => [...prev, newFavorite]);
        setMessage("Agregado a favoritos");

        // Luego hacer la petición al servidor
        await favoritesService.add(placeId);

        // Recargar favoritos para obtener el objeto completo del servidor
        const updatedFavorites = await favoritesService.getAll();
        setFavoritos(updatedFavorites);
      }
      setTimeout(() => setMessage(""), 3000);
    } catch (error) {
      console.error("Error al actualizar favorito:", error);

      // Revertir cambio si falla
      setFavoritos(previousFavorites);

      setMessage(error.response?.data?.message || "Error al actualizar favorito");
      setTimeout(() => setMessage(""), 3000);
    } finally {
      setUpdatingFavorites({ ...updatingFavorites, [placeId]: false });
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
        <h1>Lugares Acuáticos</h1>

        {message && (
          <div style={{
            padding: "12px 20px",
            margin: "10px 0",
            backgroundColor: message.includes("Error") ? "#fee" : "#efe",
            color: message.includes("Error") ? "#c00" : "#0a0",
            borderRadius: "8px",
            textAlign: "center",
            fontWeight: "500",
            boxShadow: "0 2px 4px rgba(0,0,0,0.1)",
            animation: "slideDown 0.3s ease"
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
                <img src={lugar.imagen || lugar.image || "/imagenes/placeholder.svg"} alt={lugar.name || lugar.nombre} onError={(e) => { e.target.onerror = null; e.target.src = getLocalFallbackImage(lugar.name || lugar.nombre); }} />
                <h4>{lugar.name || lugar.nombre}</h4>
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
                    disabled={updatingFavorites[lugar.id]}
                    title={isFavorite(lugar.id) ? "Quitar de favoritos" : "Agregar a favoritos"}
                    style={{
                      opacity: updatingFavorites[lugar.id] ? 0.6 : 1,
                      cursor: updatingFavorites[lugar.id] ? "wait" : "pointer",
                      transition: "all 0.3s ease",
                      transform: updatingFavorites[lugar.id] ? "scale(0.9)" : "scale(1)"
                    }}
                  >
                    {updatingFavorites[lugar.id] ? "⏳" : (isFavorite(lugar.id) ? "❤️" : "🤍")}
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
                    const placeName = f.place?.name || f.nombre || "Lugar sin nombre";
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
