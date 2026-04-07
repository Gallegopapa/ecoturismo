import React, { useEffect, useState } from 'react';
import { adminService } from '../services/api';
import './AdminModals.css';

const EditUserModal = ({ isOpen, onClose, userId, user, places = [], onUpdated }) => {
  const [formData, setFormData] = useState({
    tipo_usuario: 'normal',
    lugares: [],
  });
  const [userInfo, setUserInfo] = useState({ name: '', email: '' });
  const [loading, setLoading] = useState(false);
  const [errors, setErrors] = useState({});
  const [message, setMessage] = useState('');

  useEffect(() => {
    if (!isOpen) return;
    if (user) {
      setUserInfo({
        name: user.name || '',
        email: user.email || '',
      });
      setFormData((prev) => ({
        ...prev,
        tipo_usuario: user.tipo_usuario || (user.is_admin ? 'admin' : 'normal'),
      }));
    }
    if (userId) {
      loadUser();
    }
  }, [isOpen, userId, user]);

  const loadUser = async () => {
    try {
      setLoading(true);
      const data = await adminService.users.getById(userId);
      setUserInfo({
        name: data.name || '',
        email: data.email || '',
      });

      const lugaresAsignados = Array.isArray(data.lugares_asignados)
        ? data.lugares_asignados.map((item) => ({
            place_id: item.id,
            es_principal: !!item.es_principal,
          }))
        : [];

      setFormData({
        tipo_usuario: data.tipo_usuario || (data.is_admin ? 'admin' : 'normal'),
        lugares: lugaresAsignados,
      });
      setMessage('');
      setErrors({});
    } catch (error) {
      console.error('Error al cargar usuario:', error);
      setMessage('Error al cargar usuario');
    } finally {
      setLoading(false);
    }
  };

  const handleInputChange = (e) => {
    const { value } = e.target;
    setFormData((prev) => ({
      ...prev,
      tipo_usuario: value,
      lugares: value === 'empresa' ? prev.lugares : [],
    }));
  };

  const handlePlaceToggle = (placeId) => {
    setFormData((prev) => {
      const lugares = prev.lugares || [];
      const existingPlace = lugares.find((p) => p.place_id === placeId);

      if (existingPlace) {
        return {
          ...prev,
          lugares: lugares.filter((p) => p.place_id !== placeId),
        };
      }

      return {
        ...prev,
        lugares: [...lugares, { place_id: placeId, es_principal: false }],
      };
    });
  };

  const handlePlacePrincipalChange = (placeId) => {
    setFormData((prev) => ({
      ...prev,
      lugares: prev.lugares.map((p) => ({
        ...p,
        es_principal: p.place_id === placeId ? !p.es_principal : false,
      })),
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});
    setMessage('');

    // Validar que usuario empresa tenga al menos un lugar asignado
    if (formData.tipo_usuario === 'empresa' && (!formData.lugares || formData.lugares.length === 0)) {
      setErrors({ lugares: ['Debe asignar al menos un lugar para usuario empresa'] });
      setMessage('Error: Usuario empresa debe tener al menos un lugar asignado');
      return;
    }

    try {
      setLoading(true);

      // Preparar el payload con la estructura correcta de lugares
      const payload = {
        tipo_usuario: formData.tipo_usuario,
        is_admin: formData.tipo_usuario === 'admin',
      };

      // Solo incluir lugares si es usuario empresa
      if (formData.tipo_usuario === 'empresa' && formData.lugares.length > 0) {
        payload.lugares = formData.lugares.map((lugar) => ({
          place_id: lugar.place_id,
          es_principal: lugar.es_principal || false,
        }));
      } else {
        payload.lugares = [];
      }

      console.log('Enviando payload:', payload);

      await adminService.users.update(userId, payload);
      setMessage('✅ Permisos actualizados correctamente');
      
      if (onUpdated) {
        onUpdated();
      }

      setTimeout(() => {
        onClose();
      }, 1500);
    } catch (error) {
      console.error('Error al actualizar:', error);
      if (error.response?.data?.errors) {
        setErrors(error.response.data.errors);
        const errorMessages = Object.values(error.response.data.errors).flat().join(', ');
        setMessage('❌ ' + errorMessages);
      } else {
        setMessage('❌ ' + (error.response?.data?.message || 'Error al actualizar permisos'));
      }
    } finally {
      setLoading(false);
    }
  };

  if (!isOpen) return null;

  return (
    <div className="modal-overlay" onClick={onClose}>
      <div className="modal-content" onClick={(e) => e.stopPropagation()}>
        <div className="modal-header">
          <h2>Editar permisos</h2>
          <button className="modal-close" onClick={onClose}>&times;</button>
        </div>

        <div className="modal-body-wrapper">
          {message && (
            <div className={`alert alert-${message.includes('Error') ? 'error' : 'success'}`}>
              {message}
            </div>
          )}

          <form onSubmit={handleSubmit} className="user-form" id="edit-user-form">
          <div className="form-group">
            <label>Usuario</label>
            <div className="readonly-field">
              <strong>{userInfo.name || '-'}</strong>
              <span>{userInfo.email || '-'}</span>
            </div>
          </div>

          <div className="form-group">
            <label htmlFor="tipo_usuario">Tipo de Usuario *</label>
            <select
              id="tipo_usuario"
              value={formData.tipo_usuario}
              onChange={handleInputChange}
              disabled={loading}
              style={{
                borderColor: errors.tipo_usuario ? '#dc3545' : undefined
              }}
            >
              <option value="normal">Usuario Normal</option>
              <option value="empresa">Usuario Empresa</option>
              <option value="admin">Administrador</option>
            </select>
            {errors.tipo_usuario && (
              <small className="error-text" style={{ display: 'block', marginTop: '4px' }}>
                {errors.tipo_usuario[0]}
              </small>
            )}
          </div>

          {formData.tipo_usuario === 'empresa' && (
            <div className="form-group">
              <label>Lugares asignados *</label>
              <small style={{ color: '#666', fontSize: '0.85rem', marginLeft: '8px', fontStyle: 'italic' }}>
                (Obligatorio para usuario empresa)
              </small>
              {errors.lugares && (
                <div className="error-text" style={{ 
                  marginTop: '8px', 
                  padding: '8px 12px',
                  backgroundColor: '#ffebee',
                  borderRadius: '4px',
                  border: '1px solid #dc3545'
                }}>
                  ⚠️ {errors.lugares[0]}
                </div>
              )}
              <div className="places-list" style={{
                borderColor: errors.lugares ? '#dc3545' : undefined,
                borderWidth: errors.lugares ? '2px' : undefined
              }}>
                {places.length > 0 ? (
                  places.map((place) => {
                    const selectedPlace = formData.lugares?.find((p) => p.place_id === place.id);
                    return (
                      <div key={place.id} className="place-item">
                        <label className="checkbox-label">
                          <input
                            type="checkbox"
                            checked={!!selectedPlace}
                            onChange={() => handlePlaceToggle(place.id)}
                            disabled={loading}
                          />
                          <span>{place.name}</span>
                        </label>

                        {selectedPlace && (
                          <div className="place-options">
                            <label className="principal-label">
                              <input
                                type="checkbox"
                                checked={selectedPlace.es_principal || false}
                                onChange={() => handlePlacePrincipalChange(place.id)}
                                disabled={loading}
                              />
                              <span>Principal</span>
                            </label>
                            <small className="helper-text">
                              Principal: responsable principal del lugar.
                            </small>
                          </div>
                        )}
                      </div>
                    );
                  })
                ) : (
                  <p className="no-places">No hay lugares disponibles</p>
                )}
              </div>
            </div>
          )}
        </form>
        </div>

        {/* Botones fuera del formulario */}
        <div className="modal-actions">
          <button 
            type="submit" 
            form="edit-user-form"
            className="btn btn-primary" 
            disabled={loading}
          >
            {loading ? 'Guardando...' : 'Guardar cambios'}
          </button>
          <button 
            type="button" 
            className="btn btn-secondary" 
            onClick={onClose} 
            disabled={loading}
          >
            Cancelar
          </button>
        </div>
      </div>
    </div>
  );
};

export default EditUserModal;
