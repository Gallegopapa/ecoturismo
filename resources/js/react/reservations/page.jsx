import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { reservationsService } from '../services/api';
import Header2 from '../components/Header2/Header2';
import Footer from '../components/Footer/Footer';
import usuarioImg from '../components/imagenes/usuario.jpg';
import './page.css';

const ReservationsPage = () => {
  const navigate = useNavigate();
  const { isAuthenticated, loading: authLoading } = useAuth();
  const [reservations, setReservations] = useState([]);
  const [loading, setLoading] = useState(true);
  const [message, setMessage] = useState('');

  useEffect(() => {
    if (!authLoading && !isAuthenticated) {
      navigate('/login', { replace: true });
    }
  }, [isAuthenticated, authLoading, navigate]);

  useEffect(() => {
    if (isAuthenticated) {
      loadReservations();
    }
  }, [isAuthenticated]);

  const loadReservations = async () => {
    try {
      setLoading(true);
      const data = await reservationsService.getMyReservations();
      setReservations(Array.isArray(data) ? data : []);
    } catch (error) {
      console.error('Error al cargar reservas:', error);
      setMessage('Error al cargar las reservas');
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Estás seguro de cancelar esta reserva?')) {
      return;
    }

    try {
      await reservationsService.delete(id);
      setMessage('✅ Reserva cancelada correctamente');
      await loadReservations();
      setTimeout(() => setMessage(''), 3000);
    } catch (error) {
      console.error('Error al cancelar reserva:', error);
      setMessage('❌ Error al cancelar la reserva');
      setTimeout(() => setMessage(''), 3000);
    }
  };

  const formatDate = (dateString) => {
    if (!dateString) return 'No especificada';
    
    // Si es una fecha en formato ISO (ej: "2025-01-17" o "2025-01-17T00:00:00")
    // Extraer solo la parte de la fecha para evitar problemas de zona horaria
    let dateStr = dateString;
    if (dateString.includes('T')) {
      dateStr = dateString.split('T')[0];
    }
    
    // Crear fecha usando componentes locales para evitar conversión UTC
    const [year, month, day] = dateStr.split('-').map(Number);
    if (!year || !month || !day) {
      // Fallback si el formato no es el esperado
      const date = new Date(dateString);
      if (isNaN(date.getTime())) return 'Fecha inválida';
      return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    }
    
    const date = new Date(year, month - 1, day);
    return date.toLocaleDateString('es-ES', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  const formatTime = (timeString) => {
    if (!timeString) return '';
    
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
    
    return '';
  };

  if (authLoading || loading) {
    return (
      <div className="page-layout">
        <Header2 />
        <div className="page-content" style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', marginTop: '100px' }}>
          <p>Cargando reservas...</p>
        </div>
        <Footer />
      </div>
    );
  }

  if (!isAuthenticated) {
    return null;
  }

  return (
    <div className="page-layout">
      <Header2 />
      <div className="page-content reservations-container" style={{ marginTop: '100px' }}>
        <h1>Mis Reservas</h1>

        {message && (
          <div style={{
            padding: "12px 20px",
            margin: "20px 0",
            backgroundColor: message.includes("✅") ? "#d4edda" : "#f8d7da",
            color: message.includes("✅") ? "#155724" : "#721c24",
            borderRadius: "8px",
            textAlign: "center",
            fontWeight: "500"
          }}>
            {message}
          </div>
        )}

        {reservations.length === 0 ? (
          <div style={{ 
            textAlign: 'center', 
            padding: '60px 20px',
            background: '#f8f9fa',
            borderRadius: '12px',
            marginTop: '30px'
          }}>
            <p style={{ fontSize: '1.2em', color: '#666', marginBottom: '20px' }}>
              No tienes reservas aún
            </p>
            <button 
              onClick={() => navigate('/lugares')}
              style={{
                padding: '12px 24px',
                background: '#2ecc71',
                color: 'white',
                border: 'none',
                borderRadius: '8px',
                fontSize: '1rem',
                cursor: 'pointer',
                fontWeight: '600'
              }}
            >
              Explorar Lugares
            </button>
          </div>
        ) : (
          <div className="reservations-list">
            {reservations.map((reservation) => (
              <div key={reservation.id} className="reservation-card">
                <div className="reservation-header">
                  <div style={{ display: 'flex', alignItems: 'center', gap: '15px' }}>
                    <img 
                      src={reservation.usuario?.foto_perfil || usuarioImg} 
                      alt={reservation.usuario?.name || 'Usuario'}
                      style={{
                        width: '50px',
                        height: '50px',
                        borderRadius: '50%',
                        objectFit: 'cover',
                        border: '2px solid #2ecc71'
                      }}
                      onError={(e) => {
                        e.target.src = usuarioImg;
                      }}
                    />
                    <div>
                      <h3 style={{ margin: 0 }}>{reservation.place?.name || 'Lugar no disponible'}</h3>
                      <p style={{ margin: '5px 0 0 0', fontSize: '0.9rem', color: '#666' }}>
                        Reservado por: {reservation.usuario?.name || 'Usuario'}
                      </p>
                    </div>
                  </div>
                  <span className={`status-badge status-${reservation.estado || 'pendiente'}`}>
                    {reservation.estado || 'Pendiente'}
                  </span>
                </div>

                <div className="reservation-info">
                  <div className="info-item">
                    <span className="info-label">📍 Ubicación:</span>
                    <span className="info-value">
                      {reservation.place?.location || 'No especificada'}
                    </span>
                  </div>

                  <div className="info-item">
                    <span className="info-label">📅 Fecha de visita:</span>
                    <span className="info-value">
                      {formatDate(reservation.fecha_visita)}
                    </span>
                  </div>

                  {reservation.hora_visita && (
                    <div className="info-item">
                      <span className="info-label">🕐 Hora:</span>
                      <span className="info-value">
                        {formatTime(reservation.hora_visita)}
                      </span>
                    </div>
                  )}

                  <div className="info-item">
                    <span className="info-label">👥 Personas:</span>
                    <span className="info-value">
                      {reservation.personas || 1}
                    </span>
                  </div>

                  {reservation.telefono_contacto && (
                    <div className="info-item">
                      <span className="info-label">📞 Teléfono:</span>
                      <span className="info-value">
                        {reservation.telefono_contacto}
                      </span>
                    </div>
                  )}

                  {reservation.comentarios && (
                    <div className="info-item">
                      <span className="info-label">💬 Comentarios:</span>
                      <span className="info-value">
                        {reservation.comentarios}
                      </span>
                    </div>
                  )}

                  {reservation.precio_total && (
                    <div className="info-item">
                      <span className="info-label">💰 Precio total:</span>
                      <span className="info-value">
                        ${parseFloat(reservation.precio_total).toLocaleString('es-CO')} COP
                      </span>
                    </div>
                  )}

                  <div className="info-item">
                    <span className="info-label">📝 Fecha de reserva:</span>
                    <span className="info-value">
                      {formatDate(reservation.fecha_reserva || reservation.created_at)}
                    </span>
                  </div>
                </div>

                <div className="reservation-actions">
                  <button
                    onClick={() => handleDelete(reservation.id)}
                    className="btn-cancel"
                  >
                    Cancelar Reserva
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
      <Footer />
    </div>
  );
};

export default ReservationsPage;

