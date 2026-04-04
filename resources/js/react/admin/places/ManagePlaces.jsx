import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../../context/AuthContext';
import Header2 from '../../../components/Header2/Header2';
import PlaceForm from './PlaceForm';
import './places.css';

export default function ManagePlaces() {
  const navigate = useNavigate();
  const { user, isAuthenticated, loading: authLoading } = useAuth();
  
  const [places, setPlaces] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);
  
  // States for modal / form
  const [showForm, setShowForm] = useState(false);
  const [editingPlace, setEditingPlace] = useState(null);

  // Authorization Guard
  useEffect(() => {
    if (!authLoading && (!isAuthenticated || !user?.is_admin)) {
      navigate('/login', { replace: true });
    }
  }, [authLoading, isAuthenticated, user, navigate]);

  useEffect(() => {
    if (isAuthenticated && user?.is_admin) {
      fetchPlaces();
    }
  }, [isAuthenticated, user]);

  const fetchPlaces = async () => {
    setLoading(true);
    try {
      const token = localStorage.getItem('token');
      const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';
      const response = await fetch(`${API_URL}/api/admin/places`, {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
        }
      });

      if (!response.ok) {
         if (response.status === 403) throw new Error('Acceso denegado');
         throw new Error('Error al cargar los lugares');
      }
      
      const data = await response.json();
      setPlaces(data);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Estás seguro de que deseas eliminar este lugar permanentemente? Esta acción no se puede deshacer.')) return;

    try {
      const token = localStorage.getItem('token');
      const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';
      const response = await fetch(`${API_URL}/api/admin/places/${id}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
        }
      });

      if (!response.ok) throw new Error('No se pudo eliminar el lugar');
      
      setSuccess('Lugar eliminado correctamente');
      fetchPlaces();
      setTimeout(() => setSuccess(null), 3000);
    } catch (err) {
      setError(err.message);
    }
  };

  const handleOpenCreate = () => {
    setEditingPlace(null);
    setShowForm(true);
  };

  const handleOpenEdit = (place) => {
    setEditingPlace(place);
    setShowForm(true);
  };

  const handleFormSuccess = (msg) => {
    setShowForm(false);
    setSuccess(msg);
    fetchPlaces();
    setTimeout(() => setSuccess(null), 3000);
  };

  if (authLoading || loading) return <div className="admin-loading">Cargando catálogo maestro...</div>;

  return (
    <div className="admin-places-page">
      <Header2 />
      
      <main className="admin-content">
        <header className="admin-page-header">
          <div className="title-section">
            <h1>Gestión Global de Lugares</h1>
            <p>Control total sobre el inventario de destinos ecoturísticos de Risaralda.</p>
          </div>
          <button className="btn-add-place" onClick={handleOpenCreate}>
            + Añadir Nuevo Destino
          </button>
        </header>

        {error && <div className="alert error">{error}</div>}
        {success && <div className="alert success">{success}</div>}

        <div className="admin-table-wrapper">
          <table className="admin-table">
            <thead>
              <tr>
                <th>Lugar</th>
                <th>Ubicación</th>
                <th>Estado</th>
                <th>Categorías</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              {places.length === 0 ? (
                <tr>
                  <td colSpan="5" className="empty-table">No hay lugares registrados en el sistema.</td>
                </tr>
              ) : (
                places.map((place) => (
                  <tr key={place.id}>
                    <td>
                      <div className="place-cell">
                        <div className="place-img-thumb">
                          {place.imagen ? <img src={place.imagen} alt={place.nombre} /> : <span>📍</span>}
                        </div>
                        <span className="place-name">{place.nombre}</span>
                      </div>
                    </td>
                    <td>{place.ubicación}</td>
                    <td>
                      <span className={`status-pill ${place.is_active ? 'active' : 'inactive'}`}>
                        {place.is_active ? 'Activo' : 'Inactivo'}
                      </span>
                    </td>
                    <td>
                      <div className="badges-list">
                        {place.categorías?.map((cat, idx) => (
                          <span key={idx} className="badge-cat">{cat}</span>
                        ))}
                      </div>
                    </td>
                    <td className="actions-cell">
                      <button className="btn-icon-edit" onClick={() => handleOpenEdit(place)} title="Editar información">✏️</button>
                      <button className="btn-icon-schedule" onClick={() => navigate(`/company/places/${place.id}/schedules`)} title="Gestionar horarios">📅</button>
                      <button className="btn-icon-delete" onClick={() => handleDelete(place.id)} title="Eliminar lugar">🗑️</button>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </main>

      {/* Render form modal */}
      {showForm && (
        <PlaceForm 
          place={editingPlace}
          onClose={() => setShowForm(false)}
          onSuccess={handleFormSuccess}
        />
      )}
    </div>
  );
}
