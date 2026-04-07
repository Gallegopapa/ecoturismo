import React, { useState, useEffect, useRef, useCallback, useMemo } from "react";
import { Link, useNavigate, Navigate } from "react-router-dom";
import { useAuth } from "@/react/context/AuthContext";
import { reviewsService, placesService } from "@/react/services/api";
import "./page.css";
import Header2 from "@/react/components/Header2/Header2";
import Footer from "@/react/components/Footer/Footer";
import usuarioImg from "@/react/components/imagenes/usuario.jpg";

const Comments2Page = () => {
  const navigate = useNavigate();
  const { user, isAuthenticated, loading: authLoading } = useAuth();
  const [reviews, setReviews] = useState([]);
  const [places, setPlaces] = useState([]);
  const [loading, setLoading] = useState(true);
  const [message, setMessage] = useState("");
  const [formError, setFormError] = useState("");
  const [formData, setFormData] = useState({
    place_id: "",
    rating: 5,
    comment: "",
  });
  const [submitting, setSubmitting] = useState(false);
  const [editingReviewId, setEditingReviewId] = useState(null);
  const [editForm, setEditForm] = useState({
    place_id: "",
    rating: 5,
    comment: "",
  });
  const textareaRef = useRef(null);

  const getReviewRating = useCallback((review) => {
    const raw = review?.rating ?? review?.calificacion ?? review?.puntuacion ?? 0;
    const parsed = Number(raw);
    if (!Number.isFinite(parsed)) {
      return 0;
    }
    return Math.max(0, Math.min(5, Math.round(parsed)));
  }, []);

  const normalizeRatingValue = useCallback((value, fallback = 5) => {
    const parsed = Number(value);
    if (!Number.isFinite(parsed)) {
      return fallback;
    }
    return Math.max(1, Math.min(5, Math.round(parsed)));
  }, []);

  const buildReviewPayload = useCallback((data) => {
    const normalizedRating = normalizeRatingValue(data?.rating);
    return {
      ...data,
      rating: normalizedRating,
      calificacion: normalizedRating,
      puntuacion: normalizedRating,
    };
  }, [normalizeRatingValue]);

  useEffect(() => {
    if (authLoading) {
      return;
    }

    if (!isAuthenticated) {
      setLoading(false);
      return;
    }

    loadData();
  }, [isAuthenticated, authLoading]);

  const loadData = useCallback(async () => {
    try {
      setLoading(true);
      const [reviewsResult, placesResult] = await Promise.allSettled([
        reviewsService.getAll(),
        placesService.getOptions(),
      ]);

      const nextReviews =
        reviewsResult.status === "fulfilled" && Array.isArray(reviewsResult.value)
          ? reviewsResult.value
          : [];

      const rawPlaces =
        placesResult.status === "fulfilled" && Array.isArray(placesResult.value)
          ? placesResult.value
          : [];

      const normalizedPlaces = rawPlaces
        .map((place) => ({
          id: place?.id ?? place?.place_id ?? "",
          name: place?.name ?? place?.nombre ?? "",
          location: place?.location ?? place?.ubicacion ?? "",
        }))
        .filter((place) => place.id && place.name);

      const fallbackPlacesFromReviews = Array.from(
        new Map(
          nextReviews
            .filter((review) => review?.place?.id && review?.place?.name)
            .map((review) => [
              review.place.id,
              {
                id: review.place.id,
                name: review.place.name,
                location: review.place.location ?? "",
              },
            ])
        ).values()
      );

      setReviews(nextReviews);
      setPlaces(normalizedPlaces.length > 0 ? normalizedPlaces : fallbackPlacesFromReviews);

      if (placesResult.status === "rejected") {
        console.error("Error al cargar lugares:", placesResult.reason);
        setFormError("No se pudieron cargar los lugares. Intenta recargar la página.");
      }

      if (reviewsResult.status === "rejected") {
        console.error("Error al cargar reseñas:", reviewsResult.reason);
        if (placesResult.status !== "rejected") {
          setMessage("No se pudieron cargar algunas reseñas, pero puedes crear una nueva.");
        } else {
          setMessage("Error al cargar las reseñas");
        }
      }
    } catch (error) {
      console.error("Error al cargar datos:", error);
      setMessage("Error al cargar las reseñas");
    } finally {
      setLoading(false);
    }
  }, [isAuthenticated]);

  const handleInputChange = useCallback((e) => {
    const { name, value } = e.target;
    setFormError("");
    setFormData(prev => ({
      ...prev,
      [name]: name === 'rating' ? parseInt(value) : value
    }));
  }, []);

  const handleSubmit = useCallback(async (e) => {
    e.preventDefault();
    
    if (!formData.place_id) {
      setFormError("Por favor selecciona un lugar");
      return;
    }

    if (!formData.comment.trim()) {
      setFormError("Por favor escribe un comentario");
      return;
    }

    setSubmitting(true);
    setMessage("");
    setFormError("");

    try {
      await reviewsService.create(buildReviewPayload(formData));
      setMessage("✅ Reseña creada correctamente");
      setFormError("");
      setFormData({
        place_id: "",
        rating: 5,
        comment: "",
      });
      await loadData();
      setTimeout(() => setMessage(""), 3000);
    } catch (error) {
      console.error("Error al crear reseña:", error);
      const errorMessage = error.response?.data?.message 
        || error.response?.data?.errors?.place_id?.[0]
        || error.response?.data?.errors?.rating?.[0]
        || error.response?.data?.errors?.comment?.[0]
        || "Error al crear la reseña";
      setFormError(errorMessage);
    } finally {
      setSubmitting(false);
    }
  }, [buildReviewPayload, formData, loadData]);

  const startEdit = useCallback((review) => {
    setEditingReviewId(review.id);
    setEditForm({
      place_id: review.place_id || review.place?.id || "",
      rating: getReviewRating(review) || 5,
      comment: review.comment || "",
    });
    setFormError("");
    setMessage("");
    setTimeout(() => {
      if (textareaRef.current) {
        textareaRef.current.scrollIntoView({ behavior: "smooth", block: "center" });
        textareaRef.current.focus();
        }
      }, 0);
      }, [getReviewRating]);

    const handleEditChange = useCallback((e) => {
    const { name, value } = e.target;
    setFormError("");
    setEditForm(prev => ({
      ...prev,
      [name]: name === 'rating' ? parseInt(value) : value,
    }));
  }, []);

  const handleEditSubmit = useCallback(async (e) => {
    e.preventDefault();
    if (!editingReviewId) return;

    if (!editForm.place_id) {
      setFormError("Por favor selecciona un lugar");
      return;
    }

    if (!editForm.comment.trim()) {
      setFormError("Por favor escribe un comentario");
      return;
    }

    setSubmitting(true);
    setMessage("");
    setFormError("");

    try {
      await reviewsService.update(editingReviewId, buildReviewPayload({
        place_id: editForm.place_id,
        rating: editForm.rating,
        comment: editForm.comment,
      }));
      setMessage("✅ Reseña actualizada correctamente");
      setFormError("");
      setEditingReviewId(null);
      await loadData();
      setTimeout(() => setMessage(""), 3000);
    } catch (error) {
      console.error("Error al actualizar reseña:", error);
      const errorMessage = error.response?.data?.message 
        || error.response?.data?.errors?.place_id?.[0]
        || error.response?.data?.errors?.rating?.[0]
        || error.response?.data?.errors?.comment?.[0]
        || "Error al actualizar la reseña";
      setFormError(errorMessage);
    } finally {
      setSubmitting(false);
    }
  }, [buildReviewPayload, editForm, editingReviewId, loadData]);

  const handleDelete = useCallback(async (reviewId) => {
    if (!window.confirm("¿Estás seguro de eliminar esta reseña?")) {
      return;
    }

    try {
      await reviewsService.delete(reviewId);
      setMessage("✅ Reseña eliminada correctamente");
      await loadData();
      setTimeout(() => setMessage(""), 3000);
    } catch (error) {
      console.error("Error al eliminar reseña:", error);
      const errorMessage = error.response?.data?.message || "Error al eliminar la reseña";
      setMessage(`❌ ${errorMessage}`);
      setTimeout(() => setMessage(""), 3000);
    }
  }, [loadData]);

  const renderStars = useMemo(() => (rating) => {
    return [1, 2, 3, 4, 5].map((n) => (
      <i key={n} className={n <= rating ? 'star-filled' : 'star-empty'}>★</i>
    ));
  }, []);

  if (authLoading || (isAuthenticated && loading)) {
    return (
      <div className="page-layout">
        <Header2 />
        <div className="page-content" style={{ display: 'flex', justifyContent: 'center', alignItems: 'center' }}>
          <p>Cargando...</p>
        </div>
        <Footer />
      </div>
    );
  }

  if (!isAuthenticated || !user) {
    return <Navigate to="/login" replace />;
  }

  const activeForm = editingReviewId ? editForm : formData;
  const activeRating = editingReviewId ? editForm.rating : formData.rating;

  return (
    <div className="page-layout">
      <Header2 />

      <div className="page-content contenedorTodo">
        {/* Sección de título */}
        <section className="review" id="review">
          <div className="middle-text">
            <h4>Nuestros Clientes</h4>
            <h2>Reseñas y Comentarios</h2>
          </div>
        </section>

        {message && (
          <div className={`msg-banner ${message.includes("✅") ? "msg-banner--ok" : "msg-banner--err"}`}>
            {message}
          </div>
        )}

        {/* Sección de reseñas */}
        <section className="review-content">
          {reviews.length === 0 ? (
            <div className="review-empty">
              <p>Aún no hay reseñas. ¡Sé el primero en comentar!</p>
            </div>
          ) : (
            reviews.map((review) => (
              <div className="box" key={review.id}>
                {/* Título: nombre del lugar/ecohotel, clickeable */}
                {review.place && (
                  <h3 className="review-place-title">
                    <Link to={`/lugares/${review.place.id}`} className="review-place-link">{review.place.name}</Link>
                    {review.place.location && (
                      <span className="review-place-location"> - {review.place.location}</span>
                    )}
                  </h3>
                )}
                {review.ecohotel && (
                  <h3 className="review-place-title">
                    <Link to={`/ecohoteles/${review.ecohotel.id}`} className="review-place-link review-place-link--ul">{review.ecohotel.name}</Link>
                    {review.ecohotel.location && (
                      <span className="review-place-location"> - {review.ecohotel.location}</span>
                    )}
                  </h3>
                )}
                
                {/* Información del usuario */}
                <div className="in-box">
                  <div
                    className="bx-img"
                    style={{
                      width: 60,
                      height: 60,
                      minWidth: 60,
                      minHeight: 60,
                      flex: '0 0 60px',
                      overflow: 'hidden',
                      borderRadius: '50%',
                    }}
                  >
                    <img 
                      src={review.usuario?.foto_perfil || usuarioImg} 
                      alt={review.usuario?.name || "Usuario"}
                      loading="lazy"
                      decoding="async"
                      style={{
                        width: '100%',
                        height: '100%',
                        minWidth: '100%',
                        minHeight: '100%',
                        aspectRatio: '1 / 1',
                        objectFit: 'cover',
                        borderRadius: '50%',
                        display: 'block',
                        border: '3px solid #2ecc71',
                      }}
                      onError={(e) => {
                        e.target.src = usuarioImg;
                      }}
                    />
                  </div>
                  <div className="bxx-text">
                    <h4 className="review-user-name">{review.usuario?.name || "Usuario"}</h4>
                    <div className="review-comment">{review.comment || "Sin comentario"}</div>
                    <div className="ratings">
                      {renderStars(getReviewRating(review))}
                    </div>
                    {review.fecha_comentario && (
                      <p className="review-date">
                        {new Date(review.fecha_comentario).toLocaleDateString('es-ES', {
                          year: 'numeric',
                          month: 'long',
                          day: 'numeric'
                        })}
                      </p>
                    )}
                  </div>
                </div>
                
                {/* Botones de acción */}
                <div className="review-actions">
                  {user && review.usuario && user.id === review.usuario.id && (
                    <button type="button" onClick={() => startEdit(review)} className="btn-action btn-action--edit">
                      Editar
                    </button>
                  )}
                  {user && (
                    (review.usuario && user.id === review.usuario.id) || user.is_admin
                  ) && (
                    <button type="button" onClick={() => handleDelete(review.id)} className="btn-action btn-action--del">
                      Eliminar
                    </button>
                  )}
                </div>
              </div>
            ))
          )}
        </section>

        {/* Formulario de comentarios */}
        <form className="comentaarios" onSubmit={editingReviewId ? handleEditSubmit : handleSubmit}>
          <h3 style={{ marginBottom: "20px", color: "#2c3e50" }}>
            {editingReviewId ? "✏️ Editar tu reseña" : "Deja tu reseña"}
          </h3>
          
          {editingReviewId && (
            <div className="edit-banner">
              ℹ️ Estás editando tu reseña. Guarda los cambios cuando termines.
            </div>
          )}
          
          <div style={{ marginBottom: "15px" }}>
            <label htmlFor="place_id" className="form-label">
              Selecciona un lugar <span className="required-star">*</span>
            </label>
            <select
              id="place_id"
              name="place_id"
              value={editingReviewId ? editForm.place_id : formData.place_id}
              onChange={editingReviewId ? handleEditChange : handleInputChange}
              required
              disabled={submitting}
              className="form-select"
            >
              <option value="">-- Selecciona un lugar --</option>
              {places.length === 0 && (
                <option value="" disabled>
                  No hay lugares disponibles
                </option>
              )}
              {places.map(place => (
                <option key={place.id} value={place.id}>
                  {place.name} {place.location ? `- ${place.location}` : ''}
                </option>
              ))}
            </select>
          </div>

          <div style={{ marginBottom: "15px" }}>
            <label className="form-label">
              Calificación <span className="required-star">*</span>
            </label>
            <div className="form-stars">
              {[1, 2, 3, 4, 5].map((n) => (
                <label key={n} className="star-label">
                  <input
                    type="radio"
                    name="rating"
                    value={n}
                    checked={activeRating === n}
                    onChange={editingReviewId ? handleEditChange : handleInputChange}
                    disabled={submitting}
                    className="star-radio"
                  />
                  <i className={activeRating >= n ? 'form-star form-star--active' : 'form-star'}>★</i>
                </label>
              ))}
              <span className="star-count">
                {activeRating} estrella{activeRating !== 1 ? 's' : ''}
              </span>
            </div>
          </div>

          <div className="input-container textarea focus">
            <textarea
              ref={textareaRef}
              name="comment"
              className="input"
              placeholder={editingReviewId ? "Edita tu comentario:" : "Déjanos tu comentario:"}
              value={editingReviewId ? editForm.comment : formData.comment}
              onChange={editingReviewId ? handleEditChange : handleInputChange}
              required
              disabled={submitting}
              maxLength={500}
            ></textarea>
            <div className={activeForm.comment.length > 500 ? 'char-counter char-counter--over' : 'char-counter'}>
              {activeForm.comment.length}/500 caracteres
            </div>
            {formError && (
              <div className="form-error">{formError}</div>
            )}
          </div>
          
          <div className="buttons-container" style={{ display: "flex", gap: "10px", flexWrap: "wrap", justifyContent: "space-between" }}>
            <div style={{ display: "flex", gap: "10px" }}>
              <Link to="/pagLogueados">
                <button type="button" className="volver" disabled={submitting}>
                  Volver
                </button>
              </Link>
              {editingReviewId && (
                <button
                  type="button"
                  onClick={() => {
                    setEditingReviewId(null);
                    setEditForm({ place_id: "", rating: 5, comment: "" });
                    setFormError("");
                  }}
                  className="btn-cancel"
                  disabled={submitting}
                >
                  Cancelar
                </button>
              )}
            </div>
            <input 
              type="submit" 
              value={submitting ? "Guardando..." : (editingReviewId ? "Guardar cambios" : "Enviar")} 
              className="btn"
              disabled={submitting}
            />
          </div>
        </form>
      </div>

      <Footer />
    </div>
  );
};

export default Comments2Page;
