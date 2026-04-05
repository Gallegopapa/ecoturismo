import React, { useState, useEffect } from 'react';
import './places.css';

export default function PlaceForm({ place, onClose, onSuccess }) {
  const [formData, setFormData] = useState({
    nombre: '',
    ubicación: '',
    descripción: '',
    latitud: '',
    longitud: '',
    is_active: true,
  });
  
  const [imageFile, setImageFile] = useState(null);
  const [imagePreview, setImagePreview] = useState(null);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (place) {
      setFormData({
        nombre: place.nombre || '',
        ubicación: place.ubicación || '',
        descripción: place.descripción || '',
        latitud: place.latitud || '',
        longitud: place.longitud || '',
        is_active: place.is_active || true,
      });
      if (place.imagen) setImagePreview(place.imagen);
    }
  }, [place]);

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value
    }));
  };

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setImageFile(file);
      setImagePreview(URL.createObjectURL(file));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSubmitting(true);
    setError(null);

    try {
      const token = localStorage.getItem('token');
      const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';
      const url = place 
        ? `${API_URL}/api/admin/places/${place.id}`
        : `${API_URL}/api/admin/places`;
        
      const method = place ? 'POST' : 'POST'; // We use POST with _method=PUT spoofing for Laravel file uploads on update

      const data = new FormData();
      Object.keys(formData).forEach(key => {
        data.append(key, formData[key]);
      });
      
      if (imageFile) {
        data.append('imagen', imageFile);
      }
      
      if (place) {
        data.append('_method', 'PUT'); // Spoofing for update
      }

      const response = await fetch(url, {
        method,
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
        },
        body: data
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Error al procesar el lugar');
      }

      onSuccess(place ? 'Lugar actualizado correctamente' : 'Lugar creado correctamente');
    } catch (err) {
      setError(err.message);
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="modal-overlay">
      <div className="admin-form-card modal-content">
        <header className="form-header">
          <h2>{place ? 'Editar Lugar' : 'Nuevo Lugar Ecoturístico'}</h2>
          <button className="btn-close-modal" onClick={onClose} title="Cerrar ventana">×</button>
        </header>

        {error && <div className="alert error">{error}</div>}

        <form onSubmit={handleSubmit} className="place-admin-form">
          <div className="form-grid">
            {/* Left Column: Form Fields */}
            <div className="form-column">
              <div className="form-group">
                <label htmlFor="nombre">Nombre Completo del Destino</label>
                <input
                  type="text"
                  id="nombre"
                  name="nombre"
                  value={formData.nombre}
                  onChange={handleChange}
                  required
                  placeholder="Ej: Reserva Natural La Pastora"
                />
              </div>

              <div className="form-group">
                <label htmlFor="ubicación">Ubicación / Dirección Exacta</label>
                <input
                  type="text"
                  id="ubicación"
                  name="ubicación"
                  value={formData.ubicación}
                  onChange={handleChange}
                  required
                  placeholder="Ej: Kilómetro 12 vía Marsella..."
                />
              </div>

              <div className="form-group">
                <label htmlFor="descripción">Descripción Detallada</label>
                <textarea
                  id="descripción"
                  name="descripción"
                  rows="4"
                  value={formData.descripción}
                  onChange={handleChange}
                  placeholder="Describe los atractivos naturales, servicios y flora/fauna del lugar..."
                ></textarea>
              </div>

              <div className="form-row-latlng">
                 <div className="form-group">
                    <label htmlFor="latitud">Latitud</label>
                    <input type="number" step="any" name="latitud" id="latitud" value={formData.latitud} onChange={handleChange} placeholder="Ej: 4.8133" />
                 </div>
                 <div className="form-group">
                    <label htmlFor="longitud">Longitud</label>
                    <input type="number" step="any" name="longitud" id="longitud" value={formData.longitud} onChange={handleChange} placeholder="Ej: -75.6961" />
                 </div>
              </div>
            </div>

            {/* Right Column: Image and Status */}
            <div className="form-column-img">
              <div className="form-group-img">
                <label>Foto de Portada</label>
                <div className="image-preview-container">
                  {imagePreview ? (
                    <img src={imagePreview} alt="Snapshot" />
                  ) : (
                    <div className="image-placeholder">No hay foto disponible</div>
                  )}
                </div>
                <input 
                  type="file" 
                  id="file-upload" 
                  className="hidden-file-input" 
                  onChange={handleImageChange}
                  accept="image/*"
                />
                <label htmlFor="file-upload" className="btn-upload-label">
                  📷 {place ? 'Cambiar Foto' : 'Cargar Foto'}
                </label>
              </div>

              <div className="form-group-checkbox">
                <input
                  type="checkbox"
                  id="is_active"
                  name="is_active"
                  checked={formData.is_active}
                  onChange={handleChange}
                />
                <label htmlFor="is_active">Lugar Activo / Habilitado</label>
              </div>
            </div>
          </div>

          <div className="form-footer">
            <button type="button" className="btn-cancel" onClick={onClose} disabled={submitting}>
              Cancelar
            </button>
            <button type="submit" className="btn-primary-admin" disabled={submitting}>
              {submitting ? 'Guardando...' : place ? 'Guardar Cambios' : 'Crear Destino'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
