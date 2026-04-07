import React, { useState, useEffect } from 'react';
import { useAuth } from '../context/AuthContext';
import { reservationsService } from '../services/api';
import './ReservationModal.css';

const ReservationModal = ({ place, isOpen, onClose, onSuccess }) => {
  const { isAuthenticated } = useAuth();
  const [formData, setFormData] = useState({
    fecha_visita: '',
    hora_visita: '',
    personas: 1,
    telefono_contacto: '',
    comentarios: '',
  });
  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState('');
  const [suggestions, setSuggestions] = useState([]);
  const [comentariosError, setComentariosError] = useState('');

  // Bloquear scroll del body cuando el modal está abierto
  useEffect(() => {
    if (isOpen) {
      // Guardar el valor actual del overflow
      const originalOverflow = document.body.style.overflow;
      // Bloquear scroll
      document.body.style.overflow = 'hidden';
      
      return () => {
        // Restaurar el scroll cuando el modal se cierra
        document.body.style.overflow = originalOverflow;
      };
    }
  }, [isOpen]);

  if (!isOpen) return null;

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    
    // Validación de comentarios con limite de 500 caracteres
    if (name === 'comentarios') {
      if (value.length > 500) {
        setComentariosError('Los comentarios no deben tener más de 500 caracteres.');
      } else {
        setComentariosError('');
      }
    }
    
    setFormData(prev => ({
      ...prev,
      [name]: name === 'personas' ? parseInt(value) || 1 : value
    }));
    // Limpiar error del campo cuando el usuario empiece a escribir
    if (errors[name]) {
      setErrors(prev => {
        const newErrors = { ...prev };
        delete newErrors[name];
        return newErrors;
      });
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    if (!isAuthenticated) {
      setMessage('Debes iniciar sesión para hacer una reserva');
      setTimeout(() => {
        onClose();
        window.location.href = '/login';
      }, 2000);
      return;
    }

    // Validar que los comentarios no superen 500 caracteres
    if ((formData.comentarios || '').length > 500) {
      setComentariosError('Los comentarios no deben tener más de 500 caracteres.');
      return;
    }

    setLoading(true);
    setErrors({});
    setMessage('');

    try {
      const reservationData = {
        place_id: place.id,
        fecha_visita: formData.fecha_visita,
        hora_visita: formData.hora_visita || null,
        personas: formData.personas,
        telefono_contacto: formData.telefono_contacto || null,
        comentarios: formData.comentarios || null,
      };

      const result = await reservationsService.create(reservationData);
      
      setMessage('✅ Reserva creada correctamente');
      
      // Resetear formulario
      setFormData({
        fecha_visita: '',
        hora_visita: '',
        personas: 1,
        telefono_contacto: '',
        comentarios: '',
      });

      // Cerrar modal después de 2 segundos
      setTimeout(() => {
        onClose();
        if (onSuccess) {
          onSuccess(result.reservation);
        }
      }, 2000);
    } catch (error) {
      console.error('Error al crear reserva:', error);
      
      if (error.response?.data?.errors) {
        setErrors(error.response.data.errors);
        
        // Si hay sugerencias, mostrarlas
        if (error.response?.data?.suggestions && error.response.data.suggestions.length > 0) {
          setSuggestions(error.response.data.suggestions);
        } else {
          setSuggestions([]);
        }
      } else {
        setMessage(error.response?.data?.message || '❌ Error al crear la reserva');
        setSuggestions([]);
      }
    } finally {
      setLoading(false);
    }
  };

  const handleClose = () => {
    if (!loading) {
      setFormData({
        fecha_visita: '',
        hora_visita: '',
        personas: 1,
        telefono_contacto: '',
        comentarios: '',
      });
      setErrors({});
      setMessage('');
      setSuggestions([]);
      setComentariosError('');
      onClose();
    }
  };

  // Obtener fecha mínima (hoy)
  const today = new Date().toISOString().split('T')[0];

  return (
    <div className="reservation-modal-overlay" onClick={handleClose}>
      <div className="reservation-modal-content" onClick={(e) => e.stopPropagation()}>
        <button className="reservation-modal-close" onClick={handleClose} disabled={loading}>
          ✕
        </button>

        <h2>Reservar Visita</h2>
        
        <div className="reservation-place-info">
          <h3>{place.name}</h3>
          {place.location && (
            <p className="reservation-location">📍 {place.location}</p>
          )}
        </div>

        {message && (
          <div className={`reservation-message ${message.includes('✅') ? 'success' : 'error'}`}>
            {message}
          </div>
        )}

        <form onSubmit={handleSubmit} className="reservation-form">
          <div className="reservation-form-group">
            <label htmlFor="fecha_visita">
              Fecha de visita <span className="required">*</span>
            </label>
            <input
              type="date"
              id="fecha_visita"
              name="fecha_visita"
              value={formData.fecha_visita}
              onChange={handleInputChange}
              min={today}
              required
              disabled={loading}
            />
            {errors.fecha_visita && (
              <span className="reservation-error">{Array.isArray(errors.fecha_visita) ? errors.fecha_visita[0] : errors.fecha_visita}</span>
            )}
          </div>

          <div className="reservation-form-group">
            <label htmlFor="hora_visita">Hora de visita</label>
            <input
              type="time"
              id="hora_visita"
              name="hora_visita"
              value={formData.hora_visita}
              onChange={handleInputChange}
              disabled={loading}
            />
            {errors.hora_visita && (
              <>
                <span className="reservation-error">{Array.isArray(errors.hora_visita) ? errors.hora_visita[0] : errors.hora_visita}</span>
                {suggestions && suggestions.length > 0 && (
                  <div style={{ 
                    marginTop: '10px', 
                    padding: '12px', 
                    background: '#e8f5e9', 
                    borderRadius: '8px', 
                    borderLeft: '4px solid #24a148' 
                  }}>
                    <strong style={{ display: 'block', marginBottom: '8px', color: '#2d5016' }}>
                      💡 Horarios sugeridos:
                    </strong>
                    <div style={{ display: 'flex', flexWrap: 'wrap', gap: '8px' }}>
                      {suggestions.map((suggestion, idx) => (
                        <button
                          key={idx}
                          type="button"
                          onClick={() => {
                            setFormData(prev => ({ ...prev, hora_visita: suggestion.hora }));
                            setSuggestions([]);
                            setErrors(prev => {
                              const newErrors = { ...prev };
                              delete newErrors.hora_visita;
                              return newErrors;
                            });
                          }}
                          style={{
                            padding: '8px 16px',
                            background: '#24a148',
                            color: 'white',
                            border: 'none',
                            borderRadius: '6px',
                            cursor: 'pointer',
                            fontSize: '0.9em',
                            fontWeight: '500',
                            transition: 'background 0.2s'
                          }}
                          onMouseOver={(e) => e.target.style.background = '#1e7e34'}
                          onMouseOut={(e) => e.target.style.background = '#24a148'}
                        >
                          {suggestion.hora} - {suggestion.descripcion}
                        </button>
                      ))}
                    </div>
                  </div>
                )}
              </>
            )}
          </div>

          <div className="reservation-form-group">
            <label htmlFor="personas">
              Número de personas <span className="required">*</span>
            </label>
            <input
              type="number"
              id="personas"
              name="personas"
              value={formData.personas}
              onChange={handleInputChange}
              min="1"
              max="50"
              required
              disabled={loading}
            />
            {errors.personas && (
              <span className="reservation-error">{Array.isArray(errors.personas) ? errors.personas[0] : errors.personas}</span>
            )}
          </div>

          <div className="reservation-form-group">
            <label htmlFor="telefono_contacto">Teléfono de contacto</label>
            <input
              type="tel"
              id="telefono_contacto"
              name="telefono_contacto"
              value={formData.telefono_contacto}
              onChange={handleInputChange}
              placeholder="Ej: 3001234567"
              maxLength="20"
              disabled={loading}
            />
            {errors.telefono_contacto && (
              <span className="reservation-error">{Array.isArray(errors.telefono_contacto) ? errors.telefono_contacto[0] : errors.telefono_contacto}</span>
            )}
          </div>

          <div className="reservation-form-group">
            <label htmlFor="comentarios">Comentarios adicionales</label>
            <textarea
              id="comentarios"
              name="comentarios"
              value={formData.comentarios}
              onChange={handleInputChange}
              rows="4"
              placeholder="Notas especiales, requerimientos, etc."
              disabled={loading}
            />
            <div className={`reservation-char-counter ${formData.comentarios.length > 500 ? 'error' : ''}`}>
              {formData.comentarios.length}/500
            </div>
            {comentariosError && (
              <span className="reservation-error">{comentariosError}</span>
            )}
            {errors.comentarios && (
              <span className="reservation-error">{Array.isArray(errors.comentarios) ? errors.comentarios[0] : errors.comentarios}</span>
            )}
          </div>

          <div className="reservation-form-actions">
            <button 
              type="submit" 
              className="reservation-submit-btn"
              disabled={loading}
            >
              {loading ? 'Creando reserva...' : 'Confirmar Reserva'}
            </button>
            <button 
              type="button" 
              className="reservation-cancel-btn"
              onClick={handleClose}
              disabled={loading}
            >
              Cancelar
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default ReservationModal;

