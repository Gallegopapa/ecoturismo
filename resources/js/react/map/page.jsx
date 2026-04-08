import React, { useState, useEffect } from 'react';
import { useAuth } from '../context/AuthContext';
import { placesService, categoriesService } from '../services/api';
import Header from '../components/Header/Header';
import Header2 from '../components/Header2/Header2';
import Footer from '../components/Footer/Footer';
import MapView from './MapView';
import { resolvePlaceImage } from '../utils/imageUtils';
import './page.css';

const MapPage = () => {
  const { isAuthenticated, user } = useAuth();
  const [locations, setLocations] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [showNotification, setShowNotification] = useState(true);

  useEffect(() => {
    loadPlaces();
  }, []);

  useEffect(() => {
    if (showNotification) {
      const timer = setTimeout(() => {
        setShowNotification(false);
      }, 5000);
      return () => clearTimeout(timer);
    }
  }, [showNotification]);

  const loadPlaces = async () => {
    try {
      setLoading(true);
      setError('');
      
      // Obtener todos los lugares con sus categorías
      const data = await placesService.getAll();
      
      // Filtrar solo los lugares que tienen coordenadas
      let placesWithCoords = Array.isArray(data) 
        ? data.filter(place => place.latitude && place.longitude)
        : [];
      
      // Usar resolvePlaceImage para resolver la imagen de cada lugar
      placesWithCoords = placesWithCoords.map((place) => {
        const imagenFinal = resolvePlaceImage(place);
        return {
          ...place,
          imagen: imagenFinal,
        };
      });
      
      setLocations(placesWithCoords);
    } catch (err) {
      console.error('Error al cargar lugares para el mapa:', err);
      setError('Error al cargar los lugares. Por favor, intenta de nuevo.');
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="page-layout">
        {isAuthenticated && user ? <Header2 /> : <Header />}
        <div className="page-content" style={{ marginTop: '100px', display: 'flex', justifyContent: 'center', alignItems: 'center', flexDirection: 'column', gap: '20px' }}>
          <div className="loading-spinner"></div>
          <p>Cargando mapa...</p>
        </div>
        <Footer />
      </div>
    );
  }

  if (error) {
    return (
      <div className="page-layout">
        {isAuthenticated && user ? <Header2 /> : <Header />}
        <div className="page-content" style={{ marginTop: '100px', display: 'flex', justifyContent: 'center', alignItems: 'center', flexDirection: 'column', gap: '20px', padding: '20px' }}>
          <p style={{ color: '#e74c3c', fontSize: '18px' }}>{error}</p>
          <button 
            onClick={loadPlaces}
            style={{
              padding: '10px 20px',
              background: '#2ecc71',
              color: 'white',
              border: 'none',
              borderRadius: '6px',
              cursor: 'pointer',
              fontSize: '16px'
            }}
          >
            Reintentar
          </button>
        </div>
        <Footer />
      </div>
    );
  }

  return (
    <div className="page-layout">
      {isAuthenticated && user ? <Header2 /> : <Header />}
      <div className="page-content map-page-container">
        <div className={`map-page-notification ${showNotification ? 'show' : ''}`}>
          <h1>Mapa Interactivo de Lugares</h1>
          <p>Explora los lugares ecoturísticos de Risaralda en el mapa</p>
        </div>
        {locations.length === 0 && (
          <div className="map-warning-container">
            <div className="map-warning">
              <p>⚠️ No hay lugares con coordenadas disponibles en este momento.</p>
              <p>Los administradores pueden agregar coordenadas desde el panel de administración.</p>
            </div>
          </div>
        )}
        <MapView locations={locations} />
      </div>
      <Footer />
    </div>
  );
};

export default MapPage;

