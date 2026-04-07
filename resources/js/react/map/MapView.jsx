import React, { useMemo, useState, useEffect, useRef } from 'react';
import { MapContainer, TileLayer, Marker, Popup, useMap } from 'react-leaflet';
import { Link } from 'react-router-dom';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import './MapView.css';

// Componente interno para ajustar el tamaño del popup según el zoom
// Debe estar dentro de MapContainer para usar useMap()
function ZoomAwarePopup({ children, location, ...props }) {
  const map = useMap();
  const [zoom, setZoom] = useState(map.getZoom());

  useEffect(() => {
    const updateZoom = () => {
      setZoom(map.getZoom());
    };
    
    map.on('zoomend', updateZoom);
    map.on('zoom', updateZoom);
    return () => {
      map.off('zoomend', updateZoom);
      map.off('zoom', updateZoom);
    };
  }, [map]);

  const zoomClass = zoom > 12 ? 'popup-zoom-high' : zoom > 10 ? 'popup-zoom-medium' : 'popup-zoom-low';

  return (
    <Popup 
      {...props}
      className={zoomClass}
      maxWidth={zoom > 12 ? 220 : zoom > 10 ? 250 : 280}
      maxHeight={zoom > 12 ? 300 : zoom > 10 ? 350 : 400}
    >
      {children}
    </Popup>
  );
}

// Componente que renderiza los marcadores (debe estar dentro de MapContainer)
function MapContent({ locations }) {
  return (
    <>
      {locations.map(loc => (
        <Marker 
          key={loc.id} 
          position={[parseFloat(loc.latitude), parseFloat(loc.longitude)]}
        >
          <ZoomAwarePopup location={loc}>
            <div className="map-popup-content">
              <h3>{loc.name}</h3>
              {loc.location && (
                <p className="map-popup-location">📍 {loc.location}</p>
              )}
              {loc.description && (
                <p className="map-popup-description">
                  {loc.description}
                </p>
              )}
              {(loc.imagen || loc.image) && (
                <img 
                  src={loc.imagen || loc.image || '/imagenes/placeholder.svg'} 
                  alt={loc.name} 
                  className="map-popup-image"
                  onError={(e) => {
                    e.target.src = '/imagenes/placeholder.svg';
                  }}
                />
              )}
              {loc.categories && loc.categories.length > 0 && (
                <div className="map-popup-categories">
                  {loc.categories.map(cat => (
                    <span key={cat.id} className="map-category-badge">
                      {cat.name}
                    </span>
                  ))}
                </div>
              )}
              <div className="map-popup-actions">
                <Link 
                  to={`/lugares/${loc.id}`} 
                  className="map-popup-link"
                >
                  Ver detalles
                </Link>
              </div>
            </div>
          </ZoomAwarePopup>
        </Marker>
      ))}
    </>
  );
}

// Fix para los iconos de Leaflet en React
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
  iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon-2x.png',
  iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
});

export default function MapView({ locations }) {
  const [query, setQuery] = useState('');
  const [category, setCategory] = useState('all');
  const mapContainerRef = useRef(null);
  const mapInstanceRef = useRef(null);

  const categories = useMemo(() => {
    const setCat = new Set();
    locations.forEach(l => {
      if (l.categories && l.categories.length > 0) {
        l.categories.forEach(cat => setCat.add(cat.name));
      }
    });
    return Array.from(setCat);
  }, [locations]);

  // Permitir zoom con scroll en el mapa
  useEffect(() => {
    const container = mapContainerRef.current;
    if (!container) return;

    const handleTouchMove = (e) => {
      // Permitir zoom con dos dedos pero no movimiento con un dedo
      if (e.touches.length === 2) return;
      if (e.touches.length === 1) {
        e.preventDefault();
      }
    };

    container.addEventListener('touchmove', handleTouchMove, { passive: false });

    return () => {
      container.removeEventListener('touchmove', handleTouchMove);
    };
  }, []);

  const filtered = locations.filter(l => {
    const matchQuery = 
      (l.name || '').toLowerCase().includes(query.toLowerCase()) || 
      (l.description || '').toLowerCase().includes(query.toLowerCase()) ||
      (l.location || '').toLowerCase().includes(query.toLowerCase());
    
    const matchCat = category === 'all' 
      ? true 
      : l.categories && l.categories.some(cat => cat.name === category);
    
    return matchQuery && matchCat && l.latitude && l.longitude;
  });

  // Usar bounds para ajustar exactamente a Pereira y Dosquebradas en la carga inicial
  // Coordenadas aproximadas:
  // Pereira  ~ (4.814, -75.6946)
  // Dosquebradas ~ (4.8242, -75.6731)
  // Bounds: [southWest, northEast]
  // Ajuste: bounds ligeramente más cerca que la versión muy lejana
  const initialBounds = [
    [4.79, -75.71], // SW (ligeramente más cerca)
    [4.842, -75.65], // NE (ligeramente más cerca)
  ];

  const initialZoom = 11; // fallback un poco más cercano

  // Si no hay búsqueda ni filtro activo, mostramos los bounds iniciales.
  const showInitialBounds = !query && category === 'all';

  // Si el usuario está filtrando/buscando, centramos en el primer resultado (comportamiento previo)
  const center = filtered.length > 0 ? [filtered[0].latitude, filtered[0].longitude] : [4.814, -75.694];

  return (
    <div className="map-view-container" ref={mapContainerRef}>
      <div className="map-controls">
        <input 
          type="text"
          placeholder="Buscar lugar, descripción o ubicación..." 
          value={query} 
          onChange={e => setQuery(e.target.value)} 
          className="map-search-input"
        />
        <select 
          value={category} 
          onChange={e => setCategory(e.target.value)} 
          className="map-category-select"
        >
          <option value="all">Todas las categorías</option>
          {categories.map(c => (
            <option key={c} value={c}>{c}</option>
          ))}
        </select>
      </div>

      <MapContainer 
        {...(showInitialBounds ? { bounds: initialBounds, boundsOptions: { padding: [80, 80] } } : { center: center, zoom: filtered.length > 0 ? 11 : initialZoom })}
        style={{ height: '100%', width: '100%' }}
        scrollWheelZoom={false}
        ref={(map) => {
          if (map) {
            mapInstanceRef.current = map._leaflet_map || map;
          }
        }}
      >
        <TileLayer 
          url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png" 
          attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors' 
        />
        <MapContent locations={filtered} />
      </MapContainer>

      {/* Botones de zoom flotantes */}
      <div className="map-zoom-controls">
        <button 
          className="map-zoom-button map-zoom-in" 
          onClick={() => {
            if (mapInstanceRef.current) {
              mapInstanceRef.current.zoomIn();
            }
          }}
          title="Acercar (Zoom +)"
          aria-label="Acercar"
        >
          +
        </button>
        <button 
          className="map-zoom-button map-zoom-out" 
          onClick={() => {
            if (mapInstanceRef.current) {
              mapInstanceRef.current.zoomOut();
            }
          }}
          title="Alejar (Zoom -)"
          aria-label="Alejar"
        >
          −
        </button>
      </div>
    </div>
  );
}

