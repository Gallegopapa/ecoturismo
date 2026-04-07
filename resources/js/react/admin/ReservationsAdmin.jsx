import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { reservationsService } from '../services/api';
import './admin.css';

const ReservationsAdmin = () => {
  const [reservations, setReservations] = useState([]);
  const [loading, setLoading] = useState(true);
  const [message, setMessage] = useState('');
  const [filter, setFilter] = useState('all'); // all, pending, completed, cancelled
  const [editingReservation, setEditingReservation] = useState(null);
  const [contactReservation, setContactReservation] = useState(null);
  const [editForm, setEditForm] = useState({
    estado: '',
    fecha_visita: '',
    hora_visita: '',
    personas: '',
    telefono_contacto: '',
    comentarios: '',
  });

  useEffect(() => {
    loadReservations();
  }, []);

  const loadReservations = async () => {
    try {
      setLoading(true);
      const data = await reservationsService.getAll();
      setReservations(Array.isArray(data) ? data : []);
    } catch (error) {
      console.error('Error al cargar reservas:', error);
      showMessage('Error al cargar las reservas', 'error');
    } finally {
      setLoading(false);
    }
  };

  const showMessage = (msg, type = 'success') => {
    setMessage(msg);
    setTimeout(() => setMessage(''), 3000);
  };

  const getRejectionReasonText = (reservation) => {
    const companyReservation = reservation?.companyReservation || reservation?.company_reservation;
    const reason = companyReservation?.rejectionReason?.descripcion || companyReservation?.rejection_reason?.descripcion;
    const comment = companyReservation?.comentario_rechazo;

    if (reason && comment) {
      return `${reason} - ${comment}`;
    }
    if (reason || comment) {
      return reason || comment;
    }

    if (reservation?.estado === 'rechazada') {
      return 'Sin motivo registrado';
    }

    return '-';
  };

  const shouldShowContactButton = (reservation) => {
    return (
      reservation?.estado === 'rechazada' ||
      reservation?.estado === 'cancelada' ||
      reservation?.estado === 'aceptada'
    );
  };

  const handleContactClient = (reservation) => {
    setContactReservation(reservation);
  };

  const buildContactMessage = (reservation) => {
    const email = reservation?.usuario?.email;
    const phone = reservation?.telefono_contacto;
    const placeName = reservation?.place?.name || 'el lugar';
    const reasonText = getRejectionReasonText(reservation);
    const statusText = reservation?.estado || 'pendiente';
    const subject = encodeURIComponent('Estado de su reserva');
    const body = encodeURIComponent(
      `Hola, su reserva #${reservation.id} en ${placeName} fue marcada como ${statusText}. ` +
      (reasonText && reasonText !== '-' ? `Motivo: ${reasonText}. ` : '') +
      'Si tiene preguntas, puede responder a este correo.'
    );
    return { email, phone, subject, body };
  };

  const handleCopy = async (value, label) => {
    if (!value) {
      showMessage(`No hay ${label} para copiar`, 'error');
      return;
    }

    try {
      if (navigator.clipboard?.writeText) {
        await navigator.clipboard.writeText(value);
        showMessage(`${label} copiado: ${value}`);
        return;
      }
    } catch (error) {
      console.warn(`No se pudo copiar ${label}:`, error);
    }

    showMessage(`No se pudo copiar ${label}`, 'error');
  };

  const handleOpenEmail = (reservation) => {
    const { email, subject, body } = buildContactMessage(reservation);
    if (!email) {
      showMessage('No hay email del cliente', 'error');
      return;
    }

    const mailtoUrl = `mailto:${email}?subject=${subject}&body=${body}`;

    try {
      window.location.href = mailtoUrl;
    } catch (error) {
      console.warn('No se pudo abrir el cliente de correo:', error);
      showMessage('No se pudo abrir el correo. Copia el email y el mensaje.', 'error');
    }
  };

  const formatTimeForInput = (timeValue) => {
    if (!timeValue) return '';
    
    // Si es un string con formato datetime completo (ej: "2025-01-01 14:30:00")
    if (timeValue.includes('T') || timeValue.includes(' ')) {
      const date = new Date(timeValue);
      if (!isNaN(date.getTime())) {
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${hours}:${minutes}`;
      }
    }
    
    // Si es un string con formato time (ej: "14:30:00" o "14:30")
    if (typeof timeValue === 'string' && timeValue.includes(':')) {
      const parts = timeValue.split(':');
      if (parts.length >= 2) {
        const hours = parts[0].padStart(2, '0');
        const minutes = parts[1].padStart(2, '0');
        return `${hours}:${minutes}`;
      }
    }
    
    return '';
  };

  const handleEdit = (reservation) => {
    setEditingReservation(reservation);
    setEditForm({
      estado: reservation.estado || 'pendiente',
      fecha_visita: reservation.fecha_visita ? reservation.fecha_visita.split('T')[0] : '',
      hora_visita: formatTimeForInput(reservation.hora_visita),
      personas: reservation.personas || '',
      telefono_contacto: reservation.telefono_contacto || '',
      comentarios: reservation.comentarios || '',
    });
  };

  const handleCancelEdit = () => {
    setEditingReservation(null);
    setEditForm({
      estado: '',
      fecha_visita: '',
      hora_visita: '',
      personas: '',
      telefono_contacto: '',
      comentarios: '',
    });
  };

  const handleUpdate = async () => {
    if (!editingReservation) return;

    try {
      // Preparar los datos para enviar, asegurando que hora_visita esté en formato correcto
      const updateData = {
        ...editForm,
        // Si hora_visita está vacío, enviar null en lugar de string vacío
        hora_visita: editForm.hora_visita && editForm.hora_visita.trim() !== '' 
          ? editForm.hora_visita 
          : null,
        // Asegurar que personas sea un número
        personas: editForm.personas ? parseInt(editForm.personas, 10) : null,
      };

      await reservationsService.update(editingReservation.id, updateData);
      showMessage('Reserva actualizada correctamente');
      handleCancelEdit();
      loadReservations();
    } catch (error) {
      console.error('Error al actualizar reserva:', error);
      const errorMessage = error.response?.data?.message 
        || error.response?.data?.errors?.hora_visita?.[0]
        || 'Error al actualizar reserva';
      showMessage(errorMessage, 'error');
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Estás seguro de eliminar esta reserva?')) {
      return;
    }

    try {
      await reservationsService.delete(id);
      showMessage('Reserva eliminada correctamente');
      loadReservations();
    } catch (error) {
      console.error('Error al eliminar reserva:', error);
      showMessage('Error al eliminar reserva', 'error');
    }
  };

  const formatDate = (dateString) => {
    if (!dateString) return '-';

    const date = parseLocalDate(dateString);
    if (!date) {
      return '-';
    }

    return date.toLocaleDateString('es-ES', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    });
  };

  const parseLocalDate = (dateString) => {
    if (!dateString) return null;

    let dateStr = String(dateString);
    if (dateStr.includes('T')) {
      dateStr = dateStr.split('T')[0];
    }

    const [year, month, day] = dateStr.split('-').map(Number);
    if (!year || !month || !day) {
      const fallback = new Date(dateString);
      return isNaN(fallback.getTime()) ? null : fallback;
    }

    return new Date(year, month - 1, day);
  };

  const formatTime = (timeString) => {
    if (!timeString) return '-';
    
    // Si es un string en formato "HH:mm" o "HH:mm:ss"
    if (typeof timeString === 'string' && timeString.includes(':')) {
      const parts = timeString.split(':');
      if (parts.length >= 2) {
        const hours = parts[0].padStart(2, '0');
        const minutes = parts[1].padStart(2, '0');
        return `${hours}:${minutes}`;
      }
    }
    
    // Si es un datetime completo, intentar extraer la hora
    if (timeString.includes('T') || timeString.includes(' ')) {
      try {
        const date = new Date(timeString);
        if (!isNaN(date.getTime())) {
          return date.toLocaleTimeString('es-ES', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
          });
        }
      } catch (e) {
        console.error('Error al formatear hora:', e);
      }
    }
    
    return timeString;
  };

  const getStatusBadge = (reservation) => {
    const estado = reservation.estado;

    if (estado === 'confirmada') {
      return <span className="status-badge confirmed">Confirmada</span>;
    }
    if (estado === 'aceptada') {
      return <span className="status-badge accepted">Aceptada</span>;
    }
    if (estado === 'cancelada') {
      return <span className="status-badge cancelled">Cancelada</span>;
    }
    if (estado === 'rechazada') {
      return <span className="status-badge rejected">Rechazada</span>;
    }
    if (estado === 'completada') {
      return <span className="status-badge completed">Completada</span>;
    }
    if (estado === 'pendiente') {
      return <span className="status-badge pending">Pendiente</span>;
    }

    const fechaVisita = parseLocalDate(reservation.fecha_visita) || new Date(reservation.fecha_visita);
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    fechaVisita.setHours(0, 0, 0, 0);

    if (fechaVisita < hoy) {
      return <span className="status-badge completed">Completada</span>;
    }
    if (fechaVisita.getTime() === hoy.getTime()) {
      return <span className="status-badge pending">Hoy</span>;
    }
    return <span className="status-badge pending">Pendiente</span>;
  };

  const filteredReservations = reservations.filter((reservation) => {
    if (filter === 'all') return true;
    const estado = reservation.estado;
    const fechaVisita = parseLocalDate(reservation.fecha_visita) || new Date(reservation.fecha_visita);
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    fechaVisita.setHours(0, 0, 0, 0);

    if (filter === 'pending') {
      return estado === 'pendiente' || (!estado && fechaVisita >= hoy);
    }
    if (filter === 'completed') {
      return estado === 'completada' || estado === 'confirmada' || estado === 'aceptada';
    }
    if (filter === 'rejected') {
      return estado === 'rechazada' || estado === 'cancelada';
    }
    return true;
  });

  return (
    <div className="admin-panel">
      {message && (
        <div className={`admin-message ${message.includes('Error') ? 'error' : 'success'}`}>
          {message}
        </div>
      )}

      <div className="admin-list-section">
        <div className="reservations-header">
          <h2>Gestión de Reservas</h2>
          <div className="reservations-filters">
            <button
              className={filter === 'all' ? 'btn-filter active' : 'btn-filter'}
              onClick={() => setFilter('all')}
            >
              Todas
            </button>
            <button
              className={filter === 'pending' ? 'btn-filter active' : 'btn-filter'}
              onClick={() => setFilter('pending')}
            >
              Pendientes
            </button>
            <button
              className={filter === 'completed' ? 'btn-filter active' : 'btn-filter'}
              onClick={() => setFilter('completed')}
            >
              Completadas
            </button>
            <button
              className={filter === 'rejected' ? 'btn-filter active' : 'btn-filter'}
              onClick={() => setFilter('rejected')}
            >
              Rechazadas
            </button>
          </div>
        </div>

        {loading ? (
          <p>Cargando...</p>
        ) : filteredReservations.length === 0 ? (
          <p>No hay reservas registradas</p>
        ) : (
          <div className="reservations-table-container">
            <table className="admin-table reservations-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Usuario</th>
                  <th>Lugar</th>
                  <th>Fecha Visita</th>
                  <th>Hora</th>
                  <th>Personas</th>
                  <th>Teléfono</th>
                  <th>Estado</th>
                  <th>Motivo</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                {filteredReservations.map((reservation) => (
                  <React.Fragment key={reservation.id}>
                    {editingReservation?.id === reservation.id ? (
                      <tr className="editing-row">
                        <td>#{reservation.id}</td>
                        <td colSpan="8">
                          <div className="edit-form-container">
                            <h3>Editar Reserva #{reservation.id}</h3>
                            <div className="edit-form-grid">
                              <div className="form-group">
                                <label>Estado</label>
                                <select
                                  value={editForm.estado}
                                  onChange={(e) => setEditForm({ ...editForm, estado: e.target.value })}
                                >
                                  <option value="pendiente">Pendiente</option>
                                  <option value="aceptada">Aceptada</option>
                                  <option value="confirmada">Confirmada</option>
                                  <option value="cancelada">Cancelada</option>
                                  <option value="completada">Completada</option>
                                  <option value="rechazada">Rechazada</option>
                                </select>
                              </div>
                              <div className="form-group">
                                <label>Fecha Visita</label>
                                <input
                                  type="date"
                                  value={editForm.fecha_visita}
                                  onChange={(e) => setEditForm({ ...editForm, fecha_visita: e.target.value })}
                                />
                              </div>
                              <div className="form-group">
                                <label>Hora Visita</label>
                                <input
                                  type="time"
                                  value={editForm.hora_visita}
                                  onChange={(e) => setEditForm({ ...editForm, hora_visita: e.target.value })}
                                />
                              </div>
                              <div className="form-group">
                                <label>Personas</label>
                                <input
                                  type="number"
                                  min="1"
                                  max="50"
                                  value={editForm.personas}
                                  onChange={(e) => setEditForm({ ...editForm, personas: e.target.value })}
                                />
                              </div>
                              <div className="form-group">
                                <label>Teléfono</label>
                                <input
                                  type="text"
                                  value={editForm.telefono_contacto}
                                  onChange={(e) => setEditForm({ ...editForm, telefono_contacto: e.target.value })}
                                />
                              </div>
                              <div className="form-group full-width">
                                <label>Comentarios</label>
                                <textarea
                                  value={editForm.comentarios}
                                  onChange={(e) => setEditForm({ ...editForm, comentarios: e.target.value })}
                                  rows="3"
                                />
                              </div>
                            </div>
                            <div className="edit-form-actions">
                              <button onClick={handleUpdate} className="btn-primary">
                                Guardar
                              </button>
                              <button onClick={handleCancelEdit} className="btn-secondary">
                                Cancelar
                              </button>
                            </div>
                          </div>
                        </td>
                      </tr>
                    ) : (
                      <tr>
                        <td>#{reservation.id}</td>
                        <td>
                          <div className="user-cell">
                            {reservation.usuario?.foto_perfil ? (
                              <img
                                src={reservation.usuario.foto_perfil}
                                alt={reservation.usuario.name}
                                className="user-avatar"
                              />
                            ) : (
                              <div className="user-avatar-placeholder">
                                {reservation.usuario?.name?.charAt(0)?.toUpperCase() || 'U'}
                              </div>
                            )}
                            <div className="user-info">
                              <div className="user-name">{reservation.usuario?.name || 'Usuario'}</div>
                              <div className="user-email">{reservation.usuario?.email || ''}</div>
                            </div>
                          </div>
                        </td>
                        <td>
                          <div className="place-cell">
                            <div className="place-name">{reservation.place?.name || 'Lugar no encontrado'}</div>
                            <div className="place-location">{reservation.place?.location || ''}</div>
                          </div>
                        </td>
                        <td>{formatDate(reservation.fecha_visita)}</td>
                        <td>{formatTime(reservation.hora_visita)}</td>
                        <td>{reservation.personas || '-'}</td>
                        <td>{reservation.telefono_contacto || '-'}</td>
                        <td>{getStatusBadge(reservation)}</td>
                        <td className="reason-cell">{getRejectionReasonText(reservation)}</td>
                        <td>
                          <div className="action-buttons-cell">
                            <button
                              onClick={() => handleEdit(reservation)}
                              className="btn-edit"
                              title="Editar reserva"
                            >
                              Editar
                            </button>
                            {shouldShowContactButton(reservation) && (
                              <button
                                onClick={() => handleContactClient(reservation)}
                                className="btn-contact"
                                title="Contactar cliente"
                              >
                                Contactar
                              </button>
                            )}
                            <button
                              onClick={() => handleDelete(reservation.id)}
                              className="btn-delete"
                              title="Eliminar reserva"
                            >
                              Eliminar
                            </button>
                          </div>
                        </td>
                      </tr>
                    )}
                  </React.Fragment>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>

      {contactReservation && (
        <div className="contact-modal-overlay" onClick={() => setContactReservation(null)}>
          <div className="contact-modal" onClick={(event) => event.stopPropagation()}>
            <div className="contact-modal-header">
              <h3>Contactar cliente</h3>
              <button
                type="button"
                className="contact-modal-close"
                onClick={() => setContactReservation(null)}
                aria-label="Cerrar"
              >
                &times;
              </button>
            </div>

            <div className="contact-modal-body">
              <p>
                <strong>Reserva:</strong> #{contactReservation.id}
              </p>
              <p>
                <strong>Cliente:</strong> {contactReservation.usuario?.name || 'Usuario'}
              </p>
              <p>
                <strong>Email:</strong> {contactReservation.usuario?.email || 'No disponible'}
              </p>
              <p>
                <strong>Telefono:</strong> {contactReservation.telefono_contacto || 'No disponible'}
              </p>
              <p>
                <strong>Motivo:</strong> {getRejectionReasonText(contactReservation)}
              </p>

              <div className="contact-modal-actions">
                <button
                  type="button"
                  className="btn-secondary"
                  onClick={() => handleCopy(contactReservation.usuario?.email, 'Email')}
                >
                  Copiar email
                </button>
                <button
                  type="button"
                  className="btn-secondary"
                  onClick={() => handleCopy(contactReservation.telefono_contacto, 'Telefono')}
                >
                  Copiar telefono
                </button>
              </div>

              <div className="contact-modal-actions">
                <button
                  type="button"
                  className="btn-primary"
                  onClick={() => handleOpenEmail(contactReservation)}
                >
                  Abrir correo
                </button>
                <a
                  className="btn-primary"
                  href={(() => {
                    const phone = contactReservation.telefono_contacto;
                    const digits = phone ? String(phone).replace(/\D/g, '') : '';
                    return digits ? `https://wa.me/${digits}` : '#';
                  })()}
                  target="_blank"
                  rel="noopener noreferrer"
                  onClick={(event) => {
                    const phone = contactReservation.telefono_contacto;
                    const digits = phone ? String(phone).replace(/\D/g, '') : '';
                    if (!digits) {
                      event.preventDefault();
                      showMessage('No hay telefono del cliente', 'error');
                    }
                  }}
                >
                  Abrir WhatsApp
                </a>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default ReservationsAdmin;
