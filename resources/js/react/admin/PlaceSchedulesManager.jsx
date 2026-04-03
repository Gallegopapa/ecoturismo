import React, { useState, useEffect } from 'react';
import { adminService } from '../services/api';
import './PlaceSchedulesManager.css';

const PlaceSchedulesManager = ({ placeId, placeName, onClose }) => {
  const [schedules, setSchedules] = useState([]);
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState('');
  const [showModal, setShowModal] = useState(false);
  const [editingSchedule, setEditingSchedule] = useState(null);
  const [formData, setFormData] = useState({
    dia_semana: 'lunes',
    hora_inicio: '08:00',
    hora_fin: '17:00',
    activo: true,
  });
  const [errors, setErrors] = useState({});

  const diasSemana = [
    { value: 'lunes', label: 'Lunes' },
    { value: 'martes', label: 'Martes' },
    { value: 'miercoles', label: 'Miércoles' },
    { value: 'jueves', label: 'Jueves' },
    { value: 'viernes', label: 'Viernes' },
    { value: 'sabado', label: 'Sábado' },
    { value: 'domingo', label: 'Domingo' },
  ];

  useEffect(() => {
    loadSchedules();
  }, [placeId]);

  const loadSchedules = async () => {
    try {
      setLoading(true);
      const data = await adminService.places.schedules.getAll(placeId);
      setSchedules(data);
    } catch (error) {
      console.error('Error al cargar horarios:', error);
      setMessage('Error al cargar horarios');
    } finally {
      setLoading(false);
    }
  };

  const handleOpenModal = (schedule = null) => {
    if (schedule) {
      setEditingSchedule(schedule);
      setFormData({
        dia_semana: schedule.dia_semana,
        hora_inicio: schedule.hora_inicio,
        hora_fin: schedule.hora_fin,
        activo: schedule.activo,
      });
    } else {
      setEditingSchedule(null);
      setFormData({
        dia_semana: 'lunes',
        hora_inicio: '08:00',
        hora_fin: '17:00',
        activo: true,
      });
    }
    setErrors({});
    setMessage('');
    setShowModal(true);
  };

  const handleCloseModal = () => {
    setShowModal(false);
    setEditingSchedule(null);
    setErrors({});
    setMessage('');
  };

  const handleInputChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});
    setMessage('');

    // Validación cliente
    if (formData.hora_fin <= formData.hora_inicio) {
      setErrors({ hora_fin: ['La hora de fin debe ser posterior a la hora de inicio'] });
      return;
    }

    try {
      setLoading(true);
      if (editingSchedule) {
        await adminService.places.schedules.update(placeId, editingSchedule.id, formData);
        setMessage('Horario actualizado correctamente');
      } else {
        await adminService.places.schedules.create(placeId, formData);
        setMessage('Horario creado correctamente');
      }
      await loadSchedules();
      setTimeout(() => {
        handleCloseModal();
      }, 1000);
    } catch (error) {
      console.error('Error al guardar horario:', error);
      if (error.response?.data?.errors) {
        setErrors(error.response.data.errors);
      }
      setMessage(error.response?.data?.message || 'Error al guardar horario');
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (scheduleId) => {
    if (!confirm('¿Estás seguro de eliminar este horario?')) {
      return;
    }

    try {
      setLoading(true);
      await adminService.places.schedules.delete(placeId, scheduleId);
      setMessage('Horario eliminado correctamente');
      await loadSchedules();
      setTimeout(() => setMessage(''), 3000);
    } catch (error) {
      console.error('Error al eliminar horario:', error);
      setMessage('Error al eliminar horario');
    } finally {
      setLoading(false);
    }
  };

  const handleToggleActive = async (schedule) => {
    try {
      setLoading(true);
      await adminService.places.schedules.update(placeId, schedule.id, {
        activo: !schedule.activo
      });
      await loadSchedules();
      setMessage(`Horario ${!schedule.activo ? 'activado' : 'desactivado'} correctamente`);
      setTimeout(() => setMessage(''), 3000);
    } catch (error) {
      console.error('Error al cambiar estado:', error);
      setMessage('Error al cambiar estado del horario');
    } finally {
      setLoading(false);
    }
  };

  const getDiaLabel = (dia) => {
    return diasSemana.find(d => d.value === dia)?.label || dia;
  };

  const groupSchedulesByDay = () => {
    const grouped = {};
    diasSemana.forEach(dia => {
      grouped[dia.value] = schedules.filter(s => s.dia_semana === dia.value);
    });
    return grouped;
  };

  const groupedSchedules = groupSchedulesByDay();

  return (
    <div className="schedules-manager-overlay" onClick={onClose}>
      <div className="schedules-manager-content" onClick={(e) => e.stopPropagation()}>
        <div className="schedules-header">
          <h2>Gestión de Horarios</h2>
          <p className="place-name">{placeName}</p>
          <button className="close-btn" onClick={onClose}>&times;</button>
        </div>

        {message && (
          <div className={`alert ${message.includes('Error') ? 'alert-error' : 'alert-success'}`}>
            {message}
          </div>
        )}

        <div className="schedules-actions">
          <button 
            className="btn-primary" 
            onClick={() => handleOpenModal()}
            disabled={loading}
          >
            + Agregar Horario
          </button>
        </div>

        <div className="schedules-list">
          {loading && <div className="loading">Cargando...</div>}
          
          {!loading && schedules.length === 0 && (
            <div className="empty-state">
              <p>No hay horarios configurados para este lugar</p>
              <p className="empty-hint">Agrega horarios para que los usuarios puedan hacer reservas</p>
            </div>
          )}

          {!loading && schedules.length > 0 && (
            <div className="schedules-by-day">
              {diasSemana.map(dia => {
                const daySchedules = groupedSchedules[dia.value];
                if (daySchedules.length === 0) return null;

                return (
                  <div key={dia.value} className="day-group">
                    <h3 className="day-title">{dia.label}</h3>
                    <div className="day-schedules">
                      {daySchedules.map(schedule => (
                        <div 
                          key={schedule.id} 
                          className={`schedule-card ${!schedule.activo ? 'inactive' : ''}`}
                        >
                          <div className="schedule-time">
                            <span className="time-range">
                              {schedule.hora_inicio} - {schedule.hora_fin}
                            </span>
                            <span className={`status-badge ${schedule.activo ? 'active' : 'inactive'}`}>
                              {schedule.activo ? 'Activo' : 'Inactivo'}
                            </span>
                          </div>
                          <div className="schedule-actions">
                            <button
                              className="btn-icon btn-toggle"
                              onClick={() => handleToggleActive(schedule)}
                              disabled={loading}
                              title={schedule.activo ? 'Desactivar' : 'Activar'}
                            >
                              {schedule.activo ? '🔵' : '⚪'}
                            </button>
                            <button
                              className="btn-icon btn-edit"
                              onClick={() => handleOpenModal(schedule)}
                              disabled={loading}
                              title="Editar"
                            >
                              ✏️
                            </button>
                            <button
                              className="btn-icon btn-delete"
                              onClick={() => handleDelete(schedule.id)}
                              disabled={loading}
                              title="Eliminar"
                            >
                              🗑️
                            </button>
                          </div>
                        </div>
                      ))}
                    </div>
                  </div>
                );
              })}
            </div>
          )}
        </div>

        {/* Modal para crear/editar horario */}
        {showModal && (
          <div className="modal-overlay" onClick={handleCloseModal}>
            <div className="modal-content schedule-modal" onClick={(e) => e.stopPropagation()}>
              <div className="modal-header">
                <h3>{editingSchedule ? 'Editar Horario' : 'Nuevo Horario'}</h3>
                <button className="modal-close" onClick={handleCloseModal}>&times;</button>
              </div>

              {message && (
                <div className={`alert ${message.includes('Error') ? 'alert-error' : 'alert-success'}`}>
                  {message}
                </div>
              )}

              <form onSubmit={handleSubmit} className="schedule-form">
                <div className="form-group">
                  <label htmlFor="dia_semana">Día de la semana *</label>
                  <select
                    id="dia_semana"
                    name="dia_semana"
                    value={formData.dia_semana}
                    onChange={handleInputChange}
                    disabled={loading}
                    required
                  >
                    {diasSemana.map(dia => (
                      <option key={dia.value} value={dia.value}>
                        {dia.label}
                      </option>
                    ))}
                  </select>
                  {errors.dia_semana && <small className="error-text">{errors.dia_semana[0]}</small>}
                </div>

                <div className="form-row">
                  <div className="form-group">
                    <label htmlFor="hora_inicio">Hora de inicio *</label>
                    <input
                      type="time"
                      id="hora_inicio"
                      name="hora_inicio"
                      value={formData.hora_inicio}
                      onChange={handleInputChange}
                      disabled={loading}
                      required
                    />
                    {errors.hora_inicio && <small className="error-text">{errors.hora_inicio[0]}</small>}
                  </div>

                  <div className="form-group">
                    <label htmlFor="hora_fin">Hora de fin *</label>
                    <input
                      type="time"
                      id="hora_fin"
                      name="hora_fin"
                      value={formData.hora_fin}
                      onChange={handleInputChange}
                      disabled={loading}
                      required
                    />
                    {errors.hora_fin && <small className="error-text">{errors.hora_fin[0]}</small>}
                  </div>
                </div>

                <div className="form-group">
                  <label className="checkbox-label">
                    <input
                      type="checkbox"
                      name="activo"
                      checked={formData.activo}
                      onChange={handleInputChange}
                      disabled={loading}
                    />
                    <span>Horario activo</span>
                  </label>
                  <small>Los horarios inactivos no estarán disponibles para reservas</small>
                </div>

                <div className="modal-actions">
                  <button 
                    type="button" 
                    className="btn-secondary" 
                    onClick={handleCloseModal}
                    disabled={loading}
                  >
                    Cancelar
                  </button>
                  <button 
                    type="submit" 
                    className="btn-primary"
                    disabled={loading}
                  >
                    {loading ? 'Guardando...' : editingSchedule ? 'Actualizar' : 'Crear'}
                  </button>
                </div>
              </form>
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default PlaceSchedulesManager;
