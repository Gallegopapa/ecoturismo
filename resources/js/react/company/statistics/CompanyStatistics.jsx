import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../../context/AuthContext';
import Header2 from '../../../components/Header2/Header2';
import './CompanyStatistics.css';

export default function CompanyStatistics() {
  const navigate = useNavigate();
  const { user, isAuthenticated, loading: authLoading } = useAuth();
  
  const [places, setPlaces] = useState([]);
  const [stats, setStats] = useState({ pending: 0, accepted: 0, rejected: 0 });
  const [selectedPlaceId, setSelectedPlaceId] = useState('all');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Guard
  useEffect(() => {
    if (!authLoading && (!isAuthenticated || user?.tipo_usuario !== 'empresa')) {
      navigate('/login', { replace: true });
    }
  }, [authLoading, isAuthenticated, user, navigate]);

  useEffect(() => {
    if (isAuthenticated && user?.tipo_usuario === 'empresa') {
      fetchInitialData();
    }
  }, [isAuthenticated, user]);

  useEffect(() => {
    if (isAuthenticated && user?.tipo_usuario === 'empresa') {
      fetchStats();
    }
  }, [selectedPlaceId]);

  const fetchInitialData = async () => {
    try {
      const token = localStorage.getItem('token');
      const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';
      const headers = { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' };

      const response = await fetch(`${API_URL}/api/company/places`, { headers });
      if (!response.ok) throw new Error('Error al cargar lugares');
      
      const data = await response.json();
      setPlaces(data);
    } catch (err) {
      console.error(err);
    }
  };

  const fetchStats = async () => {
    setLoading(true);
    try {
      const token = localStorage.getItem('token');
      const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';
      const headers = { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' };

      const url = selectedPlaceId === 'all' 
        ? `${API_URL}/api/company/reservations/stats`
        : `${API_URL}/api/company/reservations/place/${selectedPlaceId}/stats`;

      const response = await fetch(url, { headers });
      if (!response.ok) throw new Error('Error al cargar estadísticas');
      
      const data = await response.json();
      setStats(data);
    } catch (err) {
      setError('No se pudieron cargar las estadísticas.');
    } finally {
      setLoading(false);
    }
  };

  if (authLoading || loading) return <div className="stats-loading">Analizando datos...</div>;

  const total = stats.pending + stats.accepted + stats.rejected;
  const acceptedPct = total > 0 ? (stats.accepted / total * 100).toFixed(0) : 0;
  const pendingPct = total > 0 ? (stats.pending / total * 100).toFixed(0) : 0;
  const rejectedPct = total > 0 ? (stats.rejected / total * 100).toFixed(0) : 0;

  return (
    <div className="company-stats-page">
      <Header2 />
      
      <main className="stats-content">
        <header className="stats-page-header">
           <button className="btn-back" onClick={() => navigate('/company/dashboard')}>
            ← Volver al Panel
          </button>
          <h1>Análisis de Demanda</h1>
          <p>Consulta el rendimiento de tus lugares y la tasa de conversión de reservaciones.</p>
        </header>

        <section className="stats-controls">
          <div className="filter-group">
            <label htmlFor="place-filter">Filtrar por Destino:</label>
            <select 
              id="place-filter" 
              value={selectedPlaceId} 
              onChange={(e) => setSelectedPlaceId(e.target.value)}
            >
              <option value="all">Todos los lugares</option>
              {places.map(p => (
                <option key={p.id} value={p.id}>{p.nombre}</option>
              ))}
            </select>
          </div>
        </section>

        {error && <div className="alert error">{error}</div>}

        <div className="stats-grid-main">
          {/* Main Scorecards */}
          <div className="scorecards">
            <div className="card-stat primary">
              <span className="card-label">Total Reservas</span>
              <span className="card-value">{total}</span>
              <div className="card-trend">Flujo total histórico</div>
            </div>
            <div className="card-stat success">
              <span className="card-label">Aceptadas</span>
              <span className="card-value">{stats.accepted}</span>
              <span className="card-percentage">{acceptedPct}% de éxito</span>
            </div>
            <div className="card-stat warning">
              <span className="card-label">Pendientes</span>
              <span className="card-value">{stats.pending}</span>
              <span className="card-percentage">{pendingPct}% por gestionar</span>
            </div>
            <div className="card-stat danger">
              <span className="card-label">Rechazadas</span>
              <span className="card-value">{stats.rejected}</span>
              <span className="card-percentage">{rejectedPct}% de rechazo</span>
            </div>
          </div>

          {/* Visual Representation (CSS Charts) */}
          <div className="visual-analysis">
            <div className="analysis-card">
              <h2>Distribución de Reservas</h2>
              <div className="chart-container">
                <div className="custom-bar-chart">
                  <div className="chart-item">
                    <span className="item-label">Aceptadas</span>
                    <div className="bar-wrapper">
                      <div className="bar-fill accepted" style={{ width: `${acceptedPct}%` }}></div>
                    </div>
                    <span className="item-value">{acceptedPct}%</span>
                  </div>
                  <div className="chart-item">
                    <span className="item-label">Pendientes</span>
                    <div className="bar-wrapper">
                      <div className="bar-fill pending" style={{ width: `${pendingPct}%` }}></div>
                    </div>
                    <span className="item-value">{pendingPct}%</span>
                  </div>
                  <div className="chart-item">
                    <span className="item-label">Rechazadas</span>
                    <div className="bar-wrapper">
                      <div className="bar-fill rejected" style={{ width: `${rejectedPct}%` }}></div>
                    </div>
                    <span className="item-value">{rejectedPct}%</span>
                  </div>
                </div>
              </div>
              <p className="chart-footer">Métricas calculadas en tiempo real basándose en el estado de las solicitudes.</p>
            </div>
          </div>
        </div>
      </main>
    </div>
  );
}
