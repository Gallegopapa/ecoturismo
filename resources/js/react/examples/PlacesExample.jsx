// Ejemplo de cómo usar los servicios de API en un componente React

import { useState, useEffect } from 'react';
import { useAuth } from '../context/AuthContext';
import { placesService, reviewsService, favoritesService } from '../services/api';

export default function PlacesExample() {
  const { isAuthenticated, user } = useAuth();
  const [places, setPlaces] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    loadPlaces();
  }, []);

  const loadPlaces = async () => {
    try {
      setLoading(true);
      const data = await placesService.getAll();
      setPlaces(data);
      setError(null);
    } catch (err) {
      setError('Error al cargar lugares');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleAddFavorite = async (placeId) => {
    if (!isAuthenticated) {
      alert('Debes iniciar sesión para agregar favoritos');
      return;
    }

    try {
      await favoritesService.add(placeId);
      alert('Agregado a favoritos');
      // Recargar lugares o actualizar estado
    } catch (err) {
      alert('Error al agregar a favoritos');
      console.error(err);
    }
  };

  const handleAddReview = async (placeId, rating, comment) => {
    if (!isAuthenticated) {
      alert('Debes iniciar sesión para dejar una reseña');
      return;
    }

    try {
      await reviewsService.create({
        place_id: placeId,
        rating,
        comment,
      });
      alert('Reseña agregada correctamente');
    } catch (err) {
      const errorMsg = err.response?.data?.message || 'Error al agregar reseña';
      alert(errorMsg);
      console.error(err);
    }
  };

  if (loading) {
    return <div>Cargando lugares...</div>;
  }

  if (error) {
    return <div>Error: {error}</div>;
  }

  return (
    <div>
      <h1>Lugares de Risaralda</h1>
      {isAuthenticated && <p>Hola, {user.name}!</p>}
      
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(300px, 1fr))', gap: '20px' }}>
        {places.map((place) => (
          <div key={place.id} style={{ border: '1px solid #ccc', padding: '15px', borderRadius: '8px' }}>
            <h3>{place.name}</h3>
            {place.image && (
              <img 
                src={place.image} 
                alt={place.name} 
                style={{ width: '100%', height: '200px', objectFit: 'cover', borderRadius: '5px' }}
              />
            )}
            <p>{place.description}</p>
            <p><strong>Ubicación:</strong> {place.location}</p>
            {place.average_rating && (
              <p><strong>Rating:</strong> {place.average_rating} ⭐ ({place.reviews_count} reseñas)</p>
            )}
            
            {isAuthenticated && (
              <div style={{ marginTop: '10px' }}>
                <button onClick={() => handleAddFavorite(place.id)}>
                  ⭐ Agregar a Favoritos
                </button>
              </div>
            )}
          </div>
        ))}
      </div>
    </div>
  );
}

