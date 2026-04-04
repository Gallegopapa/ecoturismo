import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useAuth } from '../../../context/AuthContext';
import Header2 from '../../../components/Header2/Header2';
import './ManageSchedules.css';

export default function ManageSchedules() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { user, isAuthenticated, loading: authLoading } = useAuth();

  const [schedules, setSchedules] = useState([]);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);
  
  // Form state for adding/editing
  const [isEditing, setIsEditing] = useState(false);
  const [currentScheduleId, setCurrentScheduleId] = useState(null);
  const [formData, setFormData] = useState({
    dia_semana: 'lunes',
    hora_inicio: '08:00',
    hora_fin: '17:00',
    activo: true,
  });

  // Guard
  useEffect(() => {
    if (!authLoading && (!isAuthenticated || user?.tipo_usuario !== 'empresa')) {
      navigate('/login', { replace: true });
    }
  }, [authLoading, isAuthenticated, user, navigate]);

  useEffect(() => {
    if (isAuthenticated && user?.tipo_usuario === 'empresa') {
      fetchSchedules();
    }
  }, [isAuthenticated, user, id]);

  const fetchSchedules = async () => {
    setLoading(true);
    try {
      const token = localStorage.getItem('token');
      const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';
      
      const response = await fetch(`${API_URL}/api/company/places/${id}/schedules`, {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
        }
      });

      if (!response.ok) throw new Error('Error al cargar horarios');
      const data = await response.json();
      setSchedules(data);
    } catch (err) {
      setError('No se pudieron cargar los horarios del lugar.');
    } finally {
      setLoading(false);
    }
  };

  const handleInputChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value
    }));
  };

  const resetForm = () => {
    setFormData({
      dia_semana: 'lunes',
      hora_inicio: '08:00',
      hora_fin: '17:00',
      activo: true,
    });
    setIsEditing(false);
    setCurrentScheduleId(null);
  };

  const handleEdit = (schedule) => {
    setFormData({
      dia_semana: schedule.dia_semana,
      hora_inicio: schedule.hora_inicio,
      hora_fin: schedule.hora_fin,
      activo: schedule.activo,
    });
    setIsEditing(true);
    setCurrentScheduleId(schedule.id);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSubmitting(true);
    setError(null);
    setSuccess(null);

    const token = localStorage.getItem('token');
    const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';
    const url = isEditing 
      ? `${API_URL}/api/company/places/${id}/schedules/${currentScheduleId}`
      : `${API_URL}/api/company/places/${id}/schedules`;
    const method = isEditing ? 'PUT' : 'POST';

    try {
      const response = await fetch(url, {
        method,
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(formData),
      });

      if (!response.ok) throw new Error('Error al procesar el horario');
      
      setSuccess(isEditing ? 'Horario actualizado correctamente' : 'Horario creado correctamente');
      resetForm();
      fetchSchedules(); // Refresh list
    } catch (err) {
      setError('Error al guardar el horario. Inténtalo de nuevo.');
    } finally {
      setSubmitting(false);
    }
  };

  const handleDelete = async (scheduleId) => {
    if (!window.confirm('¿Estás seguro de que deseas eliminar este horario?')) return;

    try {
      const token = localStorage.getItem('token');
      const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';
      
      const response = await fetch(`${API_URL}/api/company/places/${id}/schedules/${scheduleId}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
        }
      });

      if (!response.ok) throw new Error('Error al eliminar');
      
      setSuccess('Horario eliminado correctamente');
      fetchSchedules();
    } catch (err) {
      setError('No se pudo eliminar el horario.');
    }
  };

  if (authLoading || loading) return <div className="schedules-loading">Cargando horarios...</div>;

  const dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];

  return (
    <div className="company-schedules-page">
      <Header2 />
      
      <main className="schedules-content">
        <header className="schedules-page-header">
          <button className="btn-back" onClick={() => navigate('/company/dashboard')}>
            ← Volver al Panel
          </button>
          <h1>Gestión de Horarios</h1>
          <p>Configura cuándo estará abierto tu lugar para recibir reservaciones.</p>
        </header>

        {(error || success) && (
          <div className={`alert ${error ? 'error' : 'success'}`}>
            {error || success}
          </div>
        )}

        <div className="schedules-layout">
          {/* Form Section */}
          <section className="schedule-form-container">
            <div className="form-card">
              <h2>{isEditing ? 'Editar Horario' : 'Crear Nuevo Horario'}</h2>
              <form onSubmit={handleSubmit}>
                <div className="form-group">
                  <label htmlFor="dia_semana">Día de la Semana</label>
                  <select
                    id="dia_semana"
                    name="dia_semana"
                    value={formData.dia_semana}
                    onChange={handleInputChange}
                    required
                  >
                    {dias.map(dia => (
                      <option key={dia} value={dia}>{dia.charAt(0).toUpperCase() + dia.slice(1)}</option>
                    ))}
                  </select>
                </div>

                <div className="form-row">
                  <div className="form-group">
                    <label htmlFor="hora_inicio">Hora de Apertura</label>
                    <input
                      type="time"
                      id="hora_inicio"
                      name="hora_inicio"
                      value={formData.hora_inicio}
                      onChange={handleInputChange}
                      required
                    />
                  </div>
                  <div className="form-group">
                    <label htmlFor="hora_fin">Hora de Cierre</label>
                    <input
                      type="time"
                      id="hora_fin"
                      name="hora_fin"
                      value={formData.hora_fin}
                      onChange={handleInputChange}
                      required
                    />
                  </div>
                </div>

                <div className="form-group-checkbox">
                  <input
                    type="checkbox"
                    id="activo"
                    name="activo"
                    checked={formData.activo}
                    onChange={handleInputChange}
                  />
                  <label htmlFor="activo">Horario Activo (Visible para reservas)</label>
                </div>

                <div className="form-actions-inline">
                  {isEditing && (
                    <button type="button" className="btn-cancel-form" onClick={resetForm}>
                      Cancelar Edición
                    </button>
                  )}
                  <button type="submit" className="btn-submit-form" disabled={submitting}>
                    {submitting ? 'Procesando...' : isEditing ? 'Actualizar Horario' : 'Crear Horario'}
                  </button>
                </div>
              </form>
            </div>
          </section>

          {/* List Section */}
          <section className="schedules-list-container">
            <div className="list-card">
              <h2>Listado de Horarios</h2>
              {schedules.length === 0 ? (
                <div className="empty-schedules">
                  <p>No hay horarios configurados para este lugar.</p>
                </div>
              ) : (
                <div className="schedules-table-container">
                  <table className="schedules-table">
                    <thead>
                      <tr>
                        <th>Día</th>
                        <th>Rango</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      {schedules.map((schedule) => (
                        <tr key={schedule.id} className={!schedule.activo ? 'row-inactive' : ''}>
                          <td className="cap">{schedule.dia_semana}</td>
                          <td>{schedule.hora_inicio} - {schedule.hora_fin}</td>
                          <td>
                            <span className={`badge ${schedule.activo ? 'active' : 'inactive'}`}>
                              {schedule.activo ? 'Activo' : 'Inactivo'}
                            </span>
                          </td>
                          <td className="actions-cell">
                            <button className="btn-edit-sm" onClick={() => handleEdit(schedule)} title="Editar">
                              ✏️
                            </button>
                            <button className="btn-delete-sm" onClick={() => handleDelete(schedule.id)} title="Eliminar">
                              🗑️
                            </button>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              )}
            </div>
          </section>
        </div>
      </main>
    </div>
  );
}
