import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useAuth } from '../../../context/AuthContext';
import Header2 from '../../../components/Header2/Header2';
import './EditPlace.css';

export default function EditPlace() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { user, isAuthenticated, loading: authLoading } = useAuth();
  
  const [formData, setFormData] = useState({
    nombre: '',
    ubicación: '',
    descripción: '',
    latitud: '',
    longitud: '',
  });
  
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(false);

  // Guard
  useEffect(() => {
    if (!authLoading && (!isAuthenticated || user?.tipo_usuario !== 'empresa')) {
      navigate('/login', { replace: true });
    }
  }, [authLoading, isAuthenticated, user, navigate]);

  useEffect(() => {
    if (isAuthenticated && user?.tipo_usuario === 'empresa') {
      fetchPlaceDetail();
    }
  }, [isAuthenticated, user, id]);

  const fetchPlaceDetail = async () => {
    setLoading(true);
    try {
      const token = localStorage.getItem('token');
      const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';
      
      const response = await fetch(`${API_URL}/api/company/places/${id}`, {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
        }
      });

      if (!response.ok) throw new Error('Error al cargar el lugar');
      
      const data = await response.json();
      setFormData({
        nombre: data.nombre || '',
        ubicación: data.ubicación || '',
        descripción: data.descripción || '',
        latitud: data.latitud || '',
        longitud: data.longitud || '',
      });
    } catch (err) {
      setError('No se pudo cargar la información del lugar.');
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSubmitting(true);
    setError(null);
    setSuccess(false);

    try {
      const token = localStorage.getItem('token');
      const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';
      
      const response = await fetch(`${API_URL}/api/company/places/${id}`, {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(formData),
      });

      if (!response.ok) throw new Error('Error al actualizar el lugar');
      
      setSuccess(true);
      setTimeout(() => navigate('/company/dashboard'), 2000);
    } catch (err) {
      setError('Error al guardar los cambios. Inténtalo de nuevo.');
    } finally {
      setSubmitting(false);
    }
  };

  if (authLoading || loading) return <div className="edit-place-loading">Cargando...</div>;

  return (
    <div className="company-edit-place-page">
      <Header2 />
      
      <main className="edit-place-content">
        <div className="edit-card">
          <header className="edit-card-header">
            <button className="btn-back" onClick={() => navigate('/company/dashboard')}>
              ← Volver al Panel
            </button>
            <h1>Editar Lugar Ecoturístico</h1>
            <p>Actualiza la información pública de tu destino para atraer más visitantes.</p>
          </header>

          {error && <div className="alert error">{error}</div>}
          {success && <div className="alert success">¡Cambios guardados con éxito! Redirigiendo...</div>}

          <form onSubmit={handleSubmit} className="edit-form">
            <div className="form-group">
              <label htmlFor="nombre">Nombre del Lugar</label>
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
              <label htmlFor="ubicación">Ubicación / Dirección</label>
              <input
                type="text"
                id="ubicación"
                name="ubicación"
                value={formData.ubicación}
                onChange={handleChange}
                required
                placeholder="Ej: Pereira-Marsella, Risaralda"
              />
            </div>

            <div className="form-group">
              <label htmlFor="descripción">Descripción Detallada</label>
              <textarea
                id="descripción"
                name="descripción"
                value={formData.descripción}
                onChange={handleChange}
                rows="6"
                placeholder="Describe los atractivos, actividades y lo que hace especial a este lugar..."
              ></textarea>
            </div>

            <div className="form-row">
              <div className="form-group">
                <label htmlFor="latitud">Latitud</label>
                <input
                  type="number"
                  step="any"
                  id="latitud"
                  name="latitud"
                  value={formData.latitud}
                  onChange={handleChange}
                  placeholder="Ej: 4.8133"
                />
              </div>
              <div className="form-group">
                <label htmlFor="longitud">Longitud</label>
                <input
                  type="number"
                  step="any"
                  id="longitud"
                  name="longitud"
                  value={formData.longitud}
                  onChange={handleChange}
                  placeholder="Ej: -75.6961"
                />
              </div>
            </div>

            <div className="form-actions">
              <button 
                type="button" 
                className="btn-cancel" 
                onClick={() => navigate('/company/dashboard')}
                disabled={submitting}
              >
                Cancelar
              </button>
              <button 
                type="submit" 
                className="btn-save" 
                disabled={submitting}
              >
                {submitting ? 'Guardando...' : 'Guardar Cambios'}
              </button>
            </div>
          </form>
        </div>
      </main>
    </div>
  );
}
