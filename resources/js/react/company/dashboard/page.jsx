import React, { useEffect, useState } from 'react';
import { useNavigate, Navigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import { authService } from '../../services/api';
import './page.css';
import Header2 from '../../components/Header2/Header2'; // Assuming this is the logged in header

export default function CompanyDashboard() {
  const { user, isAuthenticated, loading: authLoading } = useAuth();
  const navigate = useNavigate();

  const [places, setPlaces] = useState([]);
  const [stats, setStats] = useState({ pending: 0, accepted: 0, rejected: 0 });
  const [loadingConfig, setLoadingConfig] = useState(true);
  const [errorConfig, setErrorConfig] = useState(null);

  // Route guard: only allow authenticated 'empresa' users
  useEffect(() => {
    if (!authLoading && (!isAuthenticated || user?.tipo_usuario !== 'empresa')) {
      navigate('/login', { replace: true });
    }
  }, [authLoading, isAuthenticated, user, navigate]);

  useEffect(() => {
    // Only fetch data if properly authenticated as company
    if (isAuthenticated && user?.tipo_usuario === 'empresa') {
      fetchDashboardData();
    }
  }, [isAuthenticated, user]);

  const fetchDashboardData = async () => {
    setLoadingConfig(true);
    try {
      const token = localStorage.getItem('token');
      if (!token) throw new Error('No token found');

      // Manual fetch calls as company routes might not be in api.js yet
      const headers = {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      };

      const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';

      const [placesRes, statsRes] = await Promise.all([
        fetch(`${API_URL}/api/company/places`, { headers }),
        fetch(`${API_URL}/api/company/reservations/stats`, { headers })
      ]);

      if (!placesRes.ok || !statsRes.ok) {
        throw new Error('Error al cargar datos del servidor');
      }

      const placesData = await placesRes.json();
      const statsData = await statsRes.json();

      setPlaces(placesData);
      setStats(statsData);
      setErrorConfig(null);
    } catch (err) {
      console.error(err);
      setErrorConfig('No se pudieron cargar los datos del panel.');
    } finally {
      setLoadingConfig(false);
    }
  };

  if (authLoading) return <div className="company-loading">Cargando sesión...</div>;
  
  if (!isAuthenticated || user?.tipo_usuario !== 'empresa') {
    return null; // Will redirect in useEffect
  }

  return (
    <div className="company-dashboard-page">
      <Header2 />
      
      <main className="dashboard-content">
        <header className="dashboard-header">
          <h1>Panel de Empresa</h1>
          <p>Bienvenido, {user.name}. Gestiona tus lugares ecoturísticos y reservaciones aquí.</p>
        </header>

        {errorConfig && <div className="dashboard-error">{errorConfig}</div>}

        {loadingConfig ? (
          <div className="company-loading">Cargando datos del panel...</div>
        ) : (
          <div className="dashboard-grid">
            <section className="dashboard-card stats-section">
              <h2>Estadísticas de Reservas</h2>
              <div className="stats-container">
                <div className="stat-box pending">
                  <span className="stat-value">{stats.pending}</span>
                  <span className="stat-label">Pendientes</span>
                </div>
                <div className="stat-box accepted">
                  <span className="stat-value">{stats.accepted}</span>
                  <span className="stat-label">Aceptadas</span>
                </div>
                <div className="stat-box rejected">
                  <span className="stat-value">{stats.rejected}</span>
                  <span className="stat-label">Rechazadas</span>
                </div>
              </div>
            </section>

            <section className="dashboard-card places-section">
              <h2>Lugares Asignados</h2>
              {places.length === 0 ? (
                <div className="empty-state">
                  <span className="empty-icon">📍</span>
                  <p className="empty-message">No tienes lugares asignados en este momento.</p>
                  <p className="empty-submessage">Contacta al administrador para que asigne ubicaciones a tu cuenta corporativa.</p>
                </div>
              ) : (
                <ul className="places-list">
                  {places.map((place) => (
                    <li key={place.id} className="place-item">
                      <div className="place-info">
                        <h3>{place.nombre}</h3>
                        <p>{place.ubicación}</p>
                      </div>
                      <div className="btn-group">
                        <button className="btn-manage" onClick={() => navigate(`/company/places/${place.id}/edit`)}>Administrar</button>
                        <button className="btn-schedules" onClick={() => navigate(`/company/places/${place.id}/schedules`)}>Horarios</button>
                      </div>
                    </li>
                  ))}
                </ul>
              )}
            </section>
          </div>
        )}
      </main>
    </div>
  );
}
