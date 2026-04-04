import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../../context/AuthContext';
import Header2 from '../../../components/Header2/Header2';
import './ManageReservations.css';

export default function ManageReservations() {
  const navigate = useNavigate();
  const { user, isAuthenticated, loading: authLoading } = useAuth();
  
  const [reservations, setReservations] = useState([]);
  const [reasons, setReasons] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);
  
  // Modal state
  const [rejectModal, setRejectModal] = useState({ show: false, reservationId: null, reasonId: '' });
  
  // Filter state
  const [filter, setFilter] = useState('all');

  // Guard
  useEffect(() => {
    if (!authLoading && (!isAuthenticated || user?.tipo_usuario !== 'empresa')) {
      navigate('/login', { replace: true });
    }
  }, [authLoading, isAuthenticated, user, navigate]);

  useEffect(() => {
    if (isAuthenticated && user?.tipo_usuario === 'empresa') {
      fetchData();
    }
  }, [isAuthenticated, user]);

  const fetchData = async () => {
    setLoading(true);
    try {
      const token = localStorage.getItem('token');
      const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';
      const headers = { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' };

      const [resResponse, reasonsResponse] = await Promise.all([
        fetch(`${API_URL}/api/company/reservations`, { headers }),
        fetch(`${API_URL}/api/company/rejection-reasons`, { headers })
      ]);

      if (!resResponse.ok || !reasonsResponse.ok) throw new Error('Error al cargar datos');
      
      const resData = await resResponse.json();
      const reasonsData = await reasonsResponse.json();
      
      setReservations(resData);
      setReasons(reasonsData);
    } catch (err) {
      setError('No se pudieron cargar las reservaciones.');
    } finally {
      setLoading(false);
    }
  };

  const handleAction = async (id, action, body = null) => {
    setError(null);
    setSuccess(null);
    try {
      const token = localStorage.getItem('token');
      const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';
      
      const response = await fetch(`${API_URL}/api/company/reservations/${id}/${action}`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: body ? JSON.stringify(body) : null
      });

      if (!response.ok) throw new Error(`Error al ${action} la reservación`);
      
      setSuccess(`Reservación ${action === 'accept' ? 'aceptada' : action === 'reject' ? 'rechazada' : 'reabierta'} con éxito.`);
      setRejectModal({ show: false, reservationId: null, reasonId: '' });
      fetchData(); // Refresh list
    } catch (err) {
      setError(err.message);
    }
  };

  if (authLoading || loading) return <div className="res-loading">Cargando reservaciones...</div>;

  const filteredReservations = reservations.filter(res => {
    if (filter === 'all') return true;
    return res.status === filter;
  });

  return (
    <div className="company-reservations-page">
      <Header2 />
      
      <main className="res-content">
        <header className="res-page-header">
          <div className="header-top">
             <button className="btn-back" onClick={() => navigate('/company/dashboard')}>
              ← Volver al Panel
            </button>
            <h1>Gestión de Reservaciones</h1>
          </div>
          <p>Administra las solicitudes de visita para tus lugares ecoturísticos.</p>
        </header>

        {(error || success) && (
          <div className={`alert ${error ? 'error' : 'success'}`}>
            {error || success}
          </div>
        )}

        <div className="res-filters">
          <button className={filter === 'all' ? 'active' : ''} onClick={() => setFilter('all')}>Todas</button>
          <button className={filter === 'pending' ? 'active' : ''} onClick={() => setFilter('pending')}>Pendientes</button>
          <button className={filter === 'accepted' ? 'active' : ''} onClick={() => setFilter('accepted')}>Aceptadas</button>
          <button className={filter === 'rejected' ? 'active' : ''} onClick={() => setFilter('rejected')}>Rechazadas</button>
        </div>

        <div className="res-table-container">
          <table className="res-table">
            <thead>
              <tr>
                <th>Lugar</th>
                <th>Cliente</th>
                <th>Fecha y Hora</th>
                <th>Pers.</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              {filteredReservations.length === 0 ? (
                <tr>
                  <td colSpan="6" className="empty-cell">No hay reservaciones que coincidan con el filtro.</td>
                </tr>
              ) : (
                filteredReservations.map(res => (
                  <tr key={res.id}>
                    <td><strong>{res.place_name}</strong></td>
                    <td>
                      <div className="client-info">
                        <span className="client-name">{res.client_name}</span>
                        <span className="client-meta">{res.email}</span>
                        <a href={`https://wa.me/${res.phone.replace(/\+/g, '').replace(/ /g, '')}`} target="_blank" className="whatsapp-link">
                          {res.phone}
                        </a>
                      </div>
                    </td>
                    <td>
                      <div className="date-info">
                        <span>{res.fecha_visita}</span>
                        <span className="time-badge">{res.hora_visita}</span>
                      </div>
                    </td>
                    <td>{res.personas}</td>
                    <td>
                      <span className={`status-badge ${res.status}`}>
                        {res.status === 'pending' ? 'Pendiente' : res.status === 'accepted' ? 'Aceptada' : 'Rechazada'}
                      </span>
                    </td>
                    <td className="actions-cell">
                      {res.status === 'pending' && (
                        <>
                          <button className="btn-accept" onClick={() => handleAction(res.id, 'accept')}>Aceptar</button>
                          <button className="btn-reject" onClick={() => setRejectModal({ show: true, reservationId: res.id, reasonId: '' })}>Rechazar</button>
                        </>
                      )}
                      {res.status === 'accepted' && (
                        <button className="btn-reject-outline" onClick={() => setRejectModal({ show: true, reservationId: res.id, reasonId: '' })}>Rechazar</button>
                      )}
                      {res.status === 'rejected' && (
                        <button className="btn-reopen" onClick={() => handleAction(res.id, 'reopen')}>reabrir</button>
                      )}
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </main>

      {/* Reject Modal */}
      {rejectModal.show && (
        <div className="modal-overlay">
          <div className="modal-card">
            <h2>Rechazar Reservación</h2>
            <p>Selecciona el motivo por el cual no puedes recibir esta visita.</p>
            
            <div className="form-group">
              <label>Motivo de rechazo:</label>
              <select 
                value={rejectModal.reasonId} 
                onChange={(e) => setRejectModal({...rejectModal, reasonId: e.target.value})}
              >
                <option value="">Selecciona un motivo...</option>
                {reasons.map(r => (
                  <option key={r.id} value={r.id}>{r.label}</option>
                ))}
              </select>
            </div>

            <div className="modal-actions">
              <button className="btn-modal-cancel" onClick={() => setRejectModal({ show: false, reservationId: null, reasonId: '' })}>Cancelar</button>
              <button 
                className="btn-modal-confirm" 
                disabled={!rejectModal.reasonId}
                onClick={() => handleAction(rejectModal.reservationId, 'reject', { reason_id: rejectModal.reasonId })}
              >
                Confirmar Rechazo
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
