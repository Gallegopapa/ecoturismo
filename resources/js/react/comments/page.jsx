import React, { useState, useEffect } from "react";
import { Link, useNavigate } from "react-router-dom";
import { useAuth } from "@/react/context/AuthContext";
import { reviewsService, placesService } from "@/react/services/api";
import "./page.css";
import Header from "@/react/components/Header/Header";
import Header2 from "@/react/components/Header2/Header2";
import Footer from "@/react/components/Footer/Footer";
import usuarioImg from "@/react/components/imagenes/usuario.jpg";

// Mapeo determinístico y local igual que en places/page.jsx
const mapeoImagenesDeterministico = {
  'Lago De La Pradera': '/imagenes/Lago.jpeg',
  'La Laguna Del Otún': '/imagenes/laguna.jpg',
  'Laguna Del Otún': '/imagenes/laguna.jpg',
  'Chorros De Don Lolo': '/imagenes/lolo-2.jpg',
  'Termales de Santa Rosa': '/imagenes/termaales.jpg',
  'Parque Acuático Consota': '/imagenes/consota.jpg',
  'Balneario Los Farallones': '/imagenes/farallones.jpeg',
  'Cascada Los Frailes': '/imagenes/frailes3.jpg',
  'Río San José': '/imagenes/sanjose3.jpg',
  'Rio San Jose': '/imagenes/sanjose3.jpg',
  'Alto Del Nudo': '/imagenes/nudo.jpg',
  'Alto Del Toro': '/imagenes/toro.jpg',
  'La Divisa De Don Juan': '/imagenes/divisa3.jpeg',
  'Cerro Batero': '/imagenes/batero.jpg',
  'Reserva Forestal La Nona': '/imagenes/lanona5.jpg',
  'Reserva Natural Cerro Gobia': '/imagenes/gobia.jpg',
  'Kaukitá Bosque Reserva': '/imagenes/kaukita3.jpg',
  'Kaukita Bosque Reserva': '/imagenes/kaukita3.jpg',
  'Reserva Natural DMI Agualinda': '/imagenes/distritomanejo8.jpg',
  'Parque Nacional Natural Tatamá': '/imagenes/tatama.jpg',
  'Parque Nacional Natural Tatama': '/imagenes/tatama.jpg',
  'Parque Las Araucarias': '/imagenes/araucarias.jpg',
  'Parque Regional Natural Cuchilla de San Juan': '/imagenes/cuchilla.jpg',
  'Parque Natural Regional Santa Emilia': '/imagenes/santaemilia2.jpg',
  'Jardín Botánico UTP': '/imagenes/jardin.jpeg',
  'Jardin Botanico UTP': '/imagenes/jardin.jpeg',
  'Jardín Botánico De Marsella': '/imagenes/jardinmarsella2.jpg',
  'Jardin Botanico De Marsella': '/imagenes/jardinmarsella2.jpg',
};

const mapeoImagenesLocales = {
  'lago de la pradera': '/imagenes/Lago.jpeg',
  'la laguna del otún': '/imagenes/laguna.jpg',
  'laguna del otún': '/imagenes/laguna.jpg',
  'chorros de don lolo': '/imagenes/lolo-2.jpg',
  'termales de santa rosa': '/imagenes/termaales.jpg',
  'parque acuático consota': '/imagenes/consota.jpg',
  'balneario los farallones': '/imagenes/farallones.jpeg',
  'cascada los frailes': '/imagenes/frailes3.jpg',
  'río san josé': '/imagenes/sanjose3.jpg',
  'rio san jose': '/imagenes/sanjose3.jpg',
  'alto del nudo': '/imagenes/nudo.jpg',
  'alto del toro': '/imagenes/toro.jpg',
  'la divisa de don juan': '/imagenes/divisa3.jpeg',
  'cerro batero': '/imagenes/batero.jpg',
  'reserva forestal la nona': '/imagenes/lanona5.jpg',
  'reserva natural cerro gobia': '/imagenes/gobia.jpg',
  'kaukita bosque reserva': '/imagenes/kaukita3.jpg',
  'kaukitá bosque reserva': '/imagenes/kaukita3.jpg',
  'reserva natural dmi agualinda': '/imagenes/distritomanejo8.jpg',
  'parque nacional natural tatamá': '/imagenes/tatama.jpg',
  'parque nacional natural tatama': '/imagenes/tatama.jpg',
  'parque las araucarias': '/imagenes/araucarias.jpg',
  'parque regional natural cuchilla de san juan': '/imagenes/cuchilla.jpg',
  'parque natural regional santa emilia': '/imagenes/santaemilia2.jpg',
  'jardín botánico utp': '/imagenes/jardin.jpeg',
  'jardin botanico utp': '/imagenes/jardin.jpeg',
  'jardín botánico de marsella': '/imagenes/jardinmarsella2.jpg',
  'jardin botanico de marsella': '/imagenes/jardinmarsella2.jpg',
};

const CommentsPage = () => {
  const navigate = useNavigate();
  const { user, isAuthenticated } = useAuth();
  const [reviews, setReviews] = useState([]);
  const [places, setPlaces] = useState([]);
  const [loading, setLoading] = useState(true);
  const [message, setMessage] = useState("");
  const [formData, setFormData] = useState({
    place_id: "",
    rating: 5,
    comment: "",
  });
  const [submitting, setSubmitting] = useState(false);

  const getReviewRating = (review) => {
    const raw = review?.rating ?? review?.calificacion ?? review?.puntuacion ?? 0;
    const parsed = Number(raw);
    if (!Number.isFinite(parsed)) {
      return 0;
    }
    return Math.max(0, Math.min(5, Math.round(parsed)));
  };

  const normalizeRatingValue = (value, fallback = 5) => {
    const parsed = Number(value);
    if (!Number.isFinite(parsed)) {
      return fallback;
    }
    return Math.max(1, Math.min(5, Math.round(parsed)));
  };

  const buildReviewPayload = (data) => {
    const normalizedRating = normalizeRatingValue(data?.rating);
    return {
      ...data,
      rating: normalizedRating,
      calificacion: normalizedRating,
      puntuacion: normalizedRating,
    };
  };

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    try {
      setLoading(true);
      const [reviewsData, placesData] = await Promise.all([
        reviewsService.getAll(),
        placesService.getAll(),
      ]);
      setReviews(reviewsData || []);
      setPlaces(placesData || []);
    } catch (error) {
      console.error("Error al cargar datos:", error);
      setMessage("Error al cargar las reseñas");
    } finally {
      setLoading(false);
    }
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: name === 'rating' ? parseInt(value) : value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    if (!isAuthenticated) {
      setMessage("Debes iniciar sesión para dejar una reseña");
      setTimeout(() => {
        navigate("/login");
      }, 2000);
      return;
    }

    if (!formData.place_id) {
      setMessage("Por favor selecciona un lugar");
      return;
    }

    if (!formData.comment.trim()) {
      setMessage("Por favor escribe un comentario");
      return;
    }

    setSubmitting(true);
    setMessage("");

    try {
      await reviewsService.create(buildReviewPayload(formData));
      setMessage("✅ Reseña creada correctamente");
      setFormData({
        place_id: "",
        rating: 5,
        comment: "",
      });
      await loadData();
      // Emitir evento global para que otros componentes recarguen ratings
      window.dispatchEvent(new Event('review:created'));
      setTimeout(() => setMessage(""), 3000);
    } catch (error) {
      console.error("Error al crear reseña:", error);
      const errorMessage = error.response?.data?.message 
        || error.response?.data?.errors?.place_id?.[0]
        || error.response?.data?.errors?.rating?.[0]
        || error.response?.data?.errors?.comment?.[0]
        || "Error al crear la reseña";
      setMessage(`❌ ${errorMessage}`);
      setTimeout(() => setMessage(""), 5000);
    } finally {
      setSubmitting(false);
    }
  };

  const renderStars = (rating) => {
    return [1, 2, 3, 4, 5].map((n) => (
      <i key={n} style={{ color: n <= rating ? '#ffc107' : '#ddd' }}>
        ★
      </i>
    ));
  };

  return (
    <div className="page-layout">
      {isAuthenticated && user ? <Header2 /> : <Header />}

      <div className="page-content contenedorTodo">
        {/* Sección de título */}
        <section className="review" id="review">
          <div className="middle-text">
            <h4>Nuestros Clientes</h4>
            <h2>Reseñas y Comentarios</h2>
          </div>
        </section>

        {message && (
          <div style={{
            padding: "12px 20px",
            margin: "20px 0",
            backgroundColor: message.includes("✅") ? "#d4edda" : "#f8d7da",
            color: message.includes("✅") ? "#155724" : "#721c24",
            borderRadius: "8px",
            textAlign: "center",
            fontWeight: "500",
            animation: "slideDown 0.3s ease"
          }}>
            {message}
          </div>
        )}

        {/* Tarjetas de comentarios en un solo bloque */}
        <section className="review-content" style={{padding: '2rem 0'}}>
          <div style={{display:'flex',flexWrap:'wrap',gap:'2.5rem',justifyContent:'flex-start'}}>
            {reviews.length === 0 ? (
              <div style={{textAlign: "center", padding: "20px", color: "#666"}}>
                <p>No hay reseñas.</p>
              </div>
            ) : (
              reviews.map((review) => {
                // Determinar el nombre y ubicación del lugar/ecohotel si existe
                let nombreLugar = '';
                let ubicacionLugar = '';
                let imagenLugar = '';
                if (review.place || (review.reviewable && review.reviewable_type === 'App\\Models\\Place')) {
                  const place = review.place || (review.reviewable_type === 'App\\Models\\Place' ? review.reviewable : null);
                  if (place) {
                    nombreLugar = place.name || '';
                    ubicacionLugar = place.location || '';
                    // Imagen lógica igual que antes
                    const normalizarNombre = (str) => {
                      if (!str) return '';
                      return str
                        .toLowerCase()
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '')
                        .replace(/\s+/g, ' ')
                        .trim();
                    };
                    const nombreOriginal = place.name || '';
                    const nombreLugarNorm = normalizarNombre(nombreOriginal);
                    if (place.image && (
                      place.image.includes('/storage/places/') ||
                      place.image.startsWith('/storage/') ||
                      place.image.includes('storage/places') ||
                      place.image.startsWith('/imagenes/') ||
                      (place.image.startsWith('http') && (place.image.includes('/storage/places/') || place.image.includes('/imagenes/')))
                    )) {
                      imagenLugar = place.image;
                    } else {
                      imagenLugar = mapeoImagenesDeterministico[nombreOriginal]
                        || mapeoImagenesLocales[nombreLugarNorm]
                        || '/imagenes/placeholder.svg';
                    }
                  }
                } else if (review.ecohotel || (review.reviewable && review.reviewable_type === 'App\\Models\\Ecohotel')) {
                  const ecohotel = review.ecohotel || (review.reviewable_type === 'App\\Models\\Ecohotel' ? review.reviewable : null);
                  if (ecohotel) {
                    nombreLugar = ecohotel.name || '';
                    ubicacionLugar = ecohotel.location || '';
                    imagenLugar = ecohotel.image || '/imagenes/placeholder.svg';
                  }
                }
                return (
                  <div key={review.id} style={{background:'#fff',borderRadius:'18px',boxShadow:'0 2px 12px #0001',padding:'2rem 1.5rem 1.5rem 1.5rem',width:340,maxWidth:'95vw',marginBottom:'1.5rem',display:'flex',flexDirection:'column',alignItems:'flex-start',position:'relative'}}>
                    <div style={{display:'flex',alignItems:'center',marginBottom:'1rem',gap:'1rem'}}>
                      <img src={imagenLugar || '/imagenes/placeholder.svg'} alt={nombreLugar} style={{width:60,height:60,objectFit:'cover',borderRadius:'50%',border:'2px solid #eee',background:'#fafafa'}} onError={e => { e.target.src = '/imagenes/placeholder.svg'; }} />
                      <div>
                        <div style={{fontWeight:700,fontSize:'1.1rem',color:'#222',marginBottom:'2px'}}>
                          {(() => {
                            if (review.place || (review.reviewable && review.reviewable_type === 'App\\Models\\Place')) {
                              const place = review.place || (review.reviewable_type === 'App\\Models\\Place' ? review.reviewable : null);
                              return place ? (
                                <Link to={`/lugares/${place.id}`} style={{color:'#1976d2',textDecoration:'underline',cursor:'pointer'}}>{nombreLugar}</Link>
                              ) : <span style={{color:'#bbb'}}>Sin lugar</span>;
                            } else if (review.ecohotel || (review.reviewable && review.reviewable_type === 'App\\Models\\Ecohotel')) {
                              const ecohotel = review.ecohotel || (review.reviewable_type === 'App\\Models\\Ecohotel' ? review.reviewable : null);
                              return ecohotel ? (
                                <Link to={`/ecohoteles/${ecohotel.id}`} style={{color:'#1976d2',textDecoration:'underline',cursor:'pointer'}}>{nombreLugar}</Link>
                              ) : <span style={{color:'#bbb'}}>Sin ecohotel</span>;
                            } else {
                              return <span style={{color:'#bbb'}}>Sin lugar</span>;
                            }
                          })()}
                        </div>
                        {ubicacionLugar && (
                          <div style={{fontSize:'0.95rem',color:'#43a047',marginBottom:'2px'}}>
                            <span role="img" aria-label="ubicación">📍</span> {ubicacionLugar}
                          </div>
                        )}
                      </div>
                    </div>
                    <div style={{display:'flex',alignItems:'center',gap:'1rem',marginBottom:'0.5rem'}}>
                      <img src={review.usuario?.foto_perfil || usuarioImg} alt={review.usuario?.name || "Usuario"} style={{width:48,height:48,minWidth:48,minHeight:48,aspectRatio:'1 / 1',borderRadius:'50%',objectFit:'cover',display:'block',flexShrink:0,border:'2px solid #e0e0e0',background:'#f5f5f5'}} onError={e => { e.target.src = usuarioImg; }} />
                      <div>
                        <div style={{fontWeight:600,fontSize:'1rem',color:'#222'}}>{review.usuario?.name || "Usuario"}</div>
                        <div style={{fontSize:'0.98rem',color:'#888'}}>{review.comment || "Sin comentario"}</div>
                      </div>
                    </div>
                    <div style={{display:'flex',alignItems:'center',gap:'0.5rem',marginBottom:'0.5rem'}}>
                      <div>{renderStars(getReviewRating(review))}</div>
                      {review.fecha_comentario && (
                        <span style={{fontSize:'0.92rem',color:'#888',marginLeft:'0.5rem'}}>
                          {new Date(review.fecha_comentario).toLocaleDateString('es-ES', {year: 'numeric',month: 'long',day: 'numeric'})}
                        </span>
                      )}
                    </div>
                    {isAuthenticated && user && review.usuario && review.usuario.id === user.id && (
                      <div style={{display:'flex',gap:'0.5rem',marginTop:'0.5rem'}}>
                        <button style={{background:'#1976d2',color:'#fff',border:'none',borderRadius:'6px',padding:'8px 18px',fontWeight:600,cursor:'pointer',fontSize:'1rem'}}>
                          Editar
                        </button>
                        <button style={{background:'#e53935',color:'#fff',border:'none',borderRadius:'6px',padding:'8px 18px',fontWeight:600,cursor:'pointer',fontSize:'1rem'}}>
                          Eliminar
                        </button>
                      </div>
                    )}
                  </div>
                );
              })
            )}
          </div>
        </section>

        {/* Formulario de comentarios */}
        <form className="comentaarios" onSubmit={handleSubmit}>
          <h3 style={{ marginBottom: "20px", color: "#2c3e50" }}>
            {isAuthenticated ? "Deja tu reseña" : "Inicia sesión para dejar una reseña"}
          </h3>
          
          {isAuthenticated && (
            <>
              <div style={{ marginBottom: "15px" }}>
                <label htmlFor="place_id" style={{ 
                  display: "block", 
                  marginBottom: "8px", 
                  fontWeight: "600",
                  color: "#2c3e50"
                }}>
                  Selecciona un lugar <span style={{ color: "#e74c3c" }}>*</span>
                </label>
                <select
                  id="place_id"
                  name="place_id"
                  value={formData.place_id}
                  onChange={handleInputChange}
                  required
                  disabled={submitting}
                  style={{
                    width: "100%",
                    padding: "12px",
                    border: "2px solid #e0e0e0",
                    borderRadius: "10px",
                    fontSize: "1rem",
                    background: "#fff"
                  }}
                >
                  <option value="">-- Selecciona un lugar --</option>
                  {places.map(place => (
                    <option key={place.id} value={place.id}>
                      {place.name} {place.location ? `- ${place.location}` : ''}
                    </option>
                  ))}
                </select>
              </div>

              <div style={{ marginBottom: "15px" }}>
                <label style={{ 
                  display: "block", 
                  marginBottom: "8px", 
                  fontWeight: "600",
                  color: "#2c3e50"
                }}>
                  Calificación <span style={{ color: "#e74c3c" }}>*</span>
                </label>
                <div style={{ display: "flex", gap: "10px", alignItems: "center" }}>
                  {[1, 2, 3, 4, 5].map((n) => (
                    <label key={n} style={{ cursor: "pointer" }}>
                      <input
                        type="radio"
                        name="rating"
                        value={n}
                        checked={formData.rating === n}
                        onChange={handleInputChange}
                        disabled={submitting}
                        style={{ display: "none" }}
                      />
                      <i style={{ 
                        fontSize: "2rem", 
                        color: formData.rating >= n ? "#ffc107" : "#ddd",
                        transition: "color 0.2s"
                      }}>
                        ★
                      </i>
                    </label>
                  ))}
                  <span style={{ marginLeft: "10px", color: "#666" }}>
                    {formData.rating} estrella{formData.rating !== 1 ? 's' : ''}
                  </span>
                </div>
              </div>
            </>
          )}

          <div className="input-container textarea focus">
            <textarea
              name="comment"
              className="input"
              placeholder={isAuthenticated ? "Déjanos tu comentario:" : "Inicia sesión para dejar un comentario"}
              value={formData.comment}
              onChange={handleInputChange}
              required={isAuthenticated}
              disabled={submitting || !isAuthenticated}
              maxLength={500}
            ></textarea>
            {isAuthenticated && (
              <div style={{ 
                textAlign: "right", 
                fontSize: "0.85rem", 
                color: formData.comment.length > 500 ? '#d7263c' : '#999',
                marginTop: "5px"
              }}>
                {formData.comment.length}/500 caracteres
              </div>
            )}
          </div>
          
          <div className="buttons-container">
            <Link to="/">
              <button type="button" className="volver" disabled={submitting}>
                Volver
              </button>
            </Link>
            <button
              type="submit"
              className="btn"
              disabled={submitting}
              aria-disabled={submitting}
            >
              {submitting ? (
                <>
                  <span className="spinner" aria-hidden="true" /> Enviando...
                </>
              ) : (
                (isAuthenticated ? "Enviar" : "Iniciar Sesión")
              )}
            </button>
          </div>
        </form>
      </div>

      <Footer />
    </div>
  );
};

export default CommentsPage;
