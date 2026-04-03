import axios from 'axios';

// Configurar la URL base de la API
// Usar URL relativa siempre para que funcione en cualquier dominio
const API_URL = '/api';

// Crear instancia de axios
const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  withCredentials: false, // No necesario para tokens Bearer, solo para cookies
});

// Interceptor para agregar el token a las peticiones
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }

    // Si es FormData, eliminar Content-Type para que el navegador lo establezca con boundary
    if (config.data instanceof FormData) {
      delete config.headers['Content-Type'];
    }

    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Interceptor para manejar errores de respuesta
api.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    // Si el token expirÃ³ o es invÃ¡lido, limpiar y redirigir al login
    // Pero solo si no estamos ya en la pÃ¡gina de login
    if (error.response?.status === 401) {
      const currentPath = window.location.pathname;
      if (currentPath !== '/login' && currentPath !== '/registro') {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = '/login';
      }
    }

    // Los errores se manejan en la respuesta sin loguear datos sensibles
    // En producción, registrar solo códigos de error sin datos sensibles
    if (process.env.NODE_ENV === 'development') {
      // Solo en desarrollo, loguear información de debugging sin datos personales
      if (error.response) {
        console.debug('API Error:', error.response.status, error.config?.url);
      }
    }

    return Promise.reject(error);
  }
);

// Servicios de autenticaciÃ³n
export const authService = {
  // Login
  login: async (credentials) => {
    const response = await api.post('/login', credentials);
    return response.data;
  },

  // Registro
  register: async (userData) => {
    const response = await api.post('/register', userData);
    return response.data;
  },

  // Logout
  logout: async () => {
    try {
      await api.post('/logout');
    } catch (error) {
      // Error silencioso - no es crítico para logout
    } finally {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
    }
  },

  // Verificar token
  verifyToken: async () => {
    const response = await api.get('/verify-token');
    return response.data;
  },

  // Enviar enlace de recuperacion
  forgotPassword: async (payload) => {
    const response = await api.post('/password/forgot', payload);
    return response.data;
  },

  // Restablecer contrasena
  resetPassword: async (payload) => {
    const response = await api.post('/password/reset', payload);
    return response.data;
  },

  // Obtener usuario actual
  getCurrentUser: async () => {
    const response = await api.get('/user');
    return response.data;
  },
};

// Servicios de lugares
export const placesService = {
  getOptions: async () => {
    try {
      const response = await api.get('/places/options');
      const payload = response.data;

      if (Array.isArray(payload)) {
        return payload;
      }

      if (Array.isArray(payload?.places)) {
        return payload.places;
      }

      if (Array.isArray(payload?.data)) {
        return payload.data;
      }

      return [];
    } catch (_) {
      // Compatibilidad con despliegues que aún no tengan la ruta nueva.
      return placesService.getAll();
    }
  },

  getAll: async (params = {}) => {
    const response = await api.get('/places', { params });
    const payload = response.data;

    if (Array.isArray(payload)) {
      return payload;
    }

    if (Array.isArray(payload?.places)) {
      return payload.places;
    }

    if (Array.isArray(payload?.data)) {
      return payload.data;
    }

    return [];
  },

  getById: async (id) => {
    const response = await api.get(`/places/${id}`);
    return response.data;
  },

  create: async (placeData) => {
    const response = await api.post('/places', placeData);
    return response.data;
  },

  update: async (id, placeData) => {
    const response = await api.put(`/places/${id}`, placeData);
    return response.data;
  },

  delete: async (id) => {
    const response = await api.delete(`/places/${id}`);
    return response.data;
  },
};

// Servicios de reservas
export const reservationsService = {
  getMyReservations: async () => {
    const response = await api.get('/reservations/my');
    return response.data;
  },

  getAll: async () => {
    const response = await api.get('/admin/reservations');
    return response.data;
  },

  getById: async (id) => {
    const response = await api.get(`/reservations/${id}`);
    return response.data;
  },

  create: async (reservationData) => {
    const response = await api.post('/reservations', reservationData);
    return response.data;
  },

  update: async (id, reservationData) => {
    const response = await api.put(`/reservations/${id}`, reservationData);
    return response.data;
  },

  delete: async (id) => {
    const response = await api.delete(`/reservations/${id}`);
    return response.data;
  },
};

// Servicios de reseÃ±as
export const reviewsService = {
  // getByEntity eliminado: ahora se usan getByPlace y getByEcohotel
  getAll: async () => {
    const response = await api.get('/reviews/all');
    const payload = response.data;

    if (Array.isArray(payload)) {
      return payload;
    }

    if (Array.isArray(payload?.reviews)) {
      return payload.reviews;
    }

    if (Array.isArray(payload?.data)) {
      return payload.data;
    }

    return [];
  },


  getByPlace: async (placeId) => {
    const response = await api.get(`/places/${placeId}/reviews`);
    return response.data;
  },

  getByEcohotel: async (ecohotelId) => {
    const response = await api.get(`/ecohotels/${ecohotelId}/reviews`);
    return response.data;
  },

  // createForEcohotel eliminado: usar create con ecohotel_id

  create: async (reviewData) => {
    const hasRating = typeof reviewData?.rating !== 'undefined' && reviewData?.rating !== null;
    const payload = hasRating
      ? {
          ...reviewData,
          rating: Number(reviewData.rating),
          calificacion: Number(reviewData.rating),
          puntuacion: Number(reviewData.rating),
        }
      : reviewData;

    const response = await api.post('/reviews', payload);
    return response.data;
  },

  update: async (id, reviewData) => {
    const hasRating = typeof reviewData?.rating !== 'undefined' && reviewData?.rating !== null;
    const payload = hasRating
      ? {
          ...reviewData,
          rating: Number(reviewData.rating),
          calificacion: Number(reviewData.rating),
          puntuacion: Number(reviewData.rating),
        }
      : reviewData;

    const response = await api.put(`/reviews/${id}`, payload);
    return response.data;
  },

  delete: async (id) => {
    const response = await api.delete(`/reviews/${id}`);
    return response.data;
  },
};

// Servicios de favoritos
export const favoritesService = {
  getAll: async () => {
    const response = await api.get('/favorites');
    const payload = response.data;

    if (Array.isArray(payload)) {
      return payload;
    }

    if (Array.isArray(payload?.favorites)) {
      return payload.favorites;
    }

    if (Array.isArray(payload?.data)) {
      return payload.data;
    }

    return [];
  },

  check: async (placeId) => {
    const response = await api.get(`/favorites/check/${placeId}`);
    return response.data.is_favorite || false;
  },

  add: async (placeId) => {
    const response = await api.post('/favorites', { place_id: placeId });
    return response.data;
  },

  remove: async (placeId) => {
    const response = await api.delete(`/favorites/${placeId}`);
    return response.data;
  },
};

// Servicios de categorÃ­as
export const categoriesService = {
  getAll: async () => {
    const response = await api.get('/categories');
    return response.data;
  },

  getById: async (id) => {
    const response = await api.get(`/categories/${id}`);
    return response.data;
  },
};

// Servicios de perfil
export const profileService = {
  get: async () => {
    const response = await api.get('/profile');
    return response.data;
  },

  update: async (profileData) => {
    const fotoPerfil = profileData?.foto_perfil;
    const hasFileLike = Boolean(
      fotoPerfil
      && typeof fotoPerfil === 'object'
      && ((typeof File !== 'undefined' && fotoPerfil instanceof File)
        || (typeof Blob !== 'undefined' && fotoPerfil instanceof Blob)
        || typeof fotoPerfil.name === 'string')
    );

    // Si hay una imagen, usar FormData
    if (hasFileLike) {
      const formData = new FormData();

      // Enviar todos los campos
      formData.append('name', profileData.name || '');
      formData.append('email', profileData.email || '');
      if (profileData.telefono) {
        formData.append('telefono', profileData.telefono);
      }
      formData.append('foto_perfil', fotoPerfil);

      console.log('📤 FormData + FILE:', {
        name: profileData.name,
        email: profileData.email,
        telefono: profileData.telefono,
        fileSize: fotoPerfil.size,
        fileName: fotoPerfil.name,
      });

      formData.append('_method', 'PUT'); // Laravel requiere esto para PUT con FormData

      const response = await api.post('/profile', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });
      return response.data;
    } else {
      // Si no hay imagen, enviar JSON normal
      console.log('📤 JSON sin file:', profileData);
      const response = await api.put('/profile', profileData);
      return response.data;
    }
  },

  changePassword: async (passwordData) => {
    const response = await api.put('/profile/password', passwordData);
    return response.data;
  },

  deleteAccount: async () => {
    const response = await api.delete('/profile');
    return response.data;
  },
};

// Servicios de mensajes
export const messagesService = {
  send: async (messageData) => {
    const response = await api.post('/messages', messageData);
    return response.data;
  },
};

// Servicios de contactos
export const contactsService = {
  send: async (contactData) => {
    const response = await api.post('/contacts', contactData);
    return response.data;
  },

  getAll: async () => {
    const response = await api.get('/contacts');
    return response.data;
  },

  getById: async (id) => {
    const response = await api.get(`/contacts/${id}`);
    return response.data;
  },
};

// Servicios de admin
export const adminService = {
  // Lugares
  places: {
    getAll: async () => {
      const response = await api.get('/admin/places');
      return response.data;
    },
    getById: async (id) => {
      const response = await api.get(`/admin/places/${id}`);
      return response.data;
    },
    create: async (placeData) => {
      const formData = new FormData();
      formData.append('name', placeData.name);
      if (placeData.location) formData.append('location', placeData.location);
      if (placeData.description) formData.append('description', placeData.description);
      if (placeData.latitude) formData.append('latitude', placeData.latitude);
      if (placeData.longitude) formData.append('longitude', placeData.longitude);
      if (placeData.image) formData.append('image', placeData.image);

      // Agregar categorías como array
      if (placeData.categories && Array.isArray(placeData.categories) && placeData.categories.length > 0) {
        placeData.categories.forEach((categoryId) => {
          formData.append('categories[]', categoryId);
        });
      }
      // Agregar ecohoteles como array
      if (placeData.ecohoteles && Array.isArray(placeData.ecohoteles) && placeData.ecohoteles.length > 0) {
        placeData.ecohoteles.forEach((ecohotelId) => {
          formData.append('ecohoteles[]', ecohotelId);
        });
      }
      const response = await api.post('/admin/places', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });
      return response.data;
    },
    update: async (id, placeData) => {
      const formData = new FormData();
      if (placeData.name) formData.append('name', placeData.name);
      if (placeData.location) formData.append('location', placeData.location);
      if (placeData.description) formData.append('description', placeData.description);
      if (placeData.latitude !== undefined && placeData.latitude !== '') formData.append('latitude', placeData.latitude);
      if (placeData.longitude !== undefined && placeData.longitude !== '') formData.append('longitude', placeData.longitude);

      // Adjuntar imagen nueva siempre que no sea una URL/string previa.
      const hasNewImage = placeData.image && typeof placeData.image !== 'string';
      if (hasNewImage) {
        formData.append('image', placeData.image);
      }

      // Agregar categorías como array solo cuando haya datos válidos.
      // Si no vienen, backend usa [] por defecto y hace sync([]).
      if (placeData.categories !== undefined) {
        if (Array.isArray(placeData.categories) && placeData.categories.length > 0) {
          placeData.categories.forEach((categoryId) => {
            formData.append('categories[]', categoryId);
          });
        }
      }

      // Agregar ecohoteles como array solo cuando haya selección.
      // Evita enviar ecohoteles[]='' que falla validación exists.
      if (placeData.ecohoteles !== undefined) {
        if (Array.isArray(placeData.ecohoteles) && placeData.ecohoteles.length > 0) {
          placeData.ecohoteles.forEach((ecohotelId) => {
            formData.append('ecohoteles[]', ecohotelId);
          });
        }
      }
      // Ruta POST dedicada para update en backend admin (acepta multipart sin spoofing).
      const response = await api.post(`/admin/places/${id}`, formData);
      return response.data;
    },
    delete: async (id) => {
      const response = await api.delete(`/admin/places/${id}`);
      return response.data;
    },
    schedules: {
      getAll: async (placeId) => {
        const response = await api.get(`/admin/places/${placeId}/schedules`);
        return response.data;
      },
      create: async (placeId, payload) => {
        const response = await api.post(`/admin/places/${placeId}/schedules`, payload);
        return response.data;
      },
      update: async (placeId, scheduleId, payload) => {
        const response = await api.put(`/admin/places/${placeId}/schedules/${scheduleId}`, payload);
        return response.data;
      },
      delete: async (placeId, scheduleId) => {
        const response = await api.delete(`/admin/places/${placeId}/schedules/${scheduleId}`);
        return response.data;
      },
    },
  },

  // Ecohoteles
  ecohotels: {
    getAll: async () => {
      const response = await api.get('/admin/ecohotels');
      return response.data;
    },
    getById: async (id) => {
      const response = await api.get(`/admin/ecohotels/${id}`);
      return response.data;
    },
    create: async (ecohotelData) => {
      const formData = new FormData();
      formData.append('name', ecohotelData.name);
      if (ecohotelData.location) formData.append('location', ecohotelData.location);
      if (ecohotelData.description) formData.append('description', ecohotelData.description);
      if (ecohotelData.latitude) formData.append('latitude', ecohotelData.latitude);
      if (ecohotelData.longitude) formData.append('longitude', ecohotelData.longitude);
      if (ecohotelData.telefono) formData.append('telefono', ecohotelData.telefono);
      if (ecohotelData.email) formData.append('email', ecohotelData.email);
      if (ecohotelData.sitio_web) formData.append('sitio_web', ecohotelData.sitio_web);
      if (ecohotelData.image) formData.append('image', ecohotelData.image);

      // Agregar categorías como array
      if (ecohotelData.categories && Array.isArray(ecohotelData.categories) && ecohotelData.categories.length > 0) {
        ecohotelData.categories.forEach((categoryId) => {
          formData.append('categories[]', categoryId);
        });
      }
      // Agregar lugares como array
      if (ecohotelData.places && Array.isArray(ecohotelData.places) && ecohotelData.places.length > 0) {
        ecohotelData.places.forEach((placeId) => {
          formData.append('places[]', placeId);
        });
      }
      const response = await api.post('/admin/ecohotels', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });
      return response.data;
    },
    update: async (id, ecohotelData) => {
      const formData = new FormData();
      formData.append('_method', 'PUT');
      if (ecohotelData.name) formData.append('name', ecohotelData.name);
      if (ecohotelData.location) formData.append('location', ecohotelData.location);
      if (ecohotelData.description) formData.append('description', ecohotelData.description);
      if (ecohotelData.latitude !== undefined && ecohotelData.latitude !== '') formData.append('latitude', ecohotelData.latitude);
      if (ecohotelData.longitude !== undefined && ecohotelData.longitude !== '') formData.append('longitude', ecohotelData.longitude);
      if (ecohotelData.telefono) formData.append('telefono', ecohotelData.telefono);
      if (ecohotelData.email) formData.append('email', ecohotelData.email);
      if (ecohotelData.sitio_web) formData.append('sitio_web', ecohotelData.sitio_web);
      if (ecohotelData.image) formData.append('image', ecohotelData.image);

      // Agregar categorías como array
      if (ecohotelData.categories !== undefined) {
        if (Array.isArray(ecohotelData.categories) && ecohotelData.categories.length > 0) {
          ecohotelData.categories.forEach((categoryId) => {
            formData.append('categories[]', categoryId);
          });
        } else {
          formData.append('categories[]', '');
        }
      }
      // Agregar lugares como array (incluso si está vacío para sincronizar)
      if (ecohotelData.places !== undefined) {
        if (Array.isArray(ecohotelData.places) && ecohotelData.places.length > 0) {
          ecohotelData.places.forEach((placeId) => {
            formData.append('places[]', placeId);
          });
        } else {
          formData.append('places[]', '');
        }
      }
      const response = await api.post(`/admin/ecohotels/${id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });
      return response.data;
    },
    delete: async (id) => {
      const response = await api.delete(`/admin/ecohotels/${id}`);
      return response.data;
    },
  },

  // Usuarios
  users: {
    getAll: async () => {
      const response = await api.get('/admin/users');
      return response.data;
    },
    getById: async (id) => {
      const response = await api.get(`/admin/users/${id}`);
      return response.data;
    },
    create: async (userData) => {
      const response = await api.post('/admin/users', userData);
      return response.data;
    },
    update: async (id, userData) => {
      const response = await api.put(`/admin/users/${id}`, userData);
      return response.data;
    },
    delete: async (id) => {
      const response = await api.delete(`/admin/users/${id}`);
      return response.data;
    },
  },
};

// Servicios para usuario empresa
export const companyService = {
  places: {
    getAll: async () => {
      const response = await api.get('/company/places');
      return response.data;
    },
    getById: async (id) => {
      const response = await api.get(`/company/places/${id}`);
      return response.data;
    },
    update: async (id, placeData) => {
      const formData = new FormData();
      formData.append('_method', 'PUT');
      formData.append('name', placeData.name);
      if (placeData.location !== undefined) formData.append('location', placeData.location);
      if (placeData.description !== undefined) formData.append('description', placeData.description);
      if (placeData.latitude !== undefined && placeData.latitude !== '') formData.append('latitude', placeData.latitude);
      if (placeData.longitude !== undefined && placeData.longitude !== '') formData.append('longitude', placeData.longitude);
      if (placeData.telefono !== undefined) formData.append('telefono', placeData.telefono);
      if (placeData.email !== undefined) formData.append('email', placeData.email);
      if (placeData.sitio_web !== undefined) formData.append('sitio_web', placeData.sitio_web);
      if (placeData.image) formData.append('image', placeData.image);

      const response = await api.post(`/company/places/${id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });
      return response.data;
    },
    delete: async (id) => {
      const response = await api.delete(`/company/places/${id}`);
      return response.data;
    },
    schedules: {
      getAll: async (placeId) => {
        const response = await api.get(`/company/places/${placeId}/schedules`);
        return response.data;
      },
      create: async (placeId, payload) => {
        const response = await api.post(`/company/places/${placeId}/schedules`, payload);
        return response.data;
      },
      update: async (placeId, scheduleId, payload) => {
        const response = await api.put(`/company/places/${placeId}/schedules/${scheduleId}`, payload);
        return response.data;
      },
      delete: async (placeId, scheduleId) => {
        const response = await api.delete(`/company/places/${placeId}/schedules/${scheduleId}`);
        return response.data;
      },
    },
  },
  reservations: {
    getAll: async (filters = {}) => {
      const response = await api.get('/company/reservations', { params: filters });
      return response.data;
    },
    getStatsSummary: async () => {
      const response = await api.get('/company/reservations/stats');
      return response.data;
    },
    getById: async (id) => {
      const response = await api.get(`/company/reservations/${id}`);
      return response.data;
    },
    accept: async (id) => {
      const response = await api.post(`/company/reservations/${id}/accept`);
      return response.data;
    },
    reject: async (id, data) => {
      const response = await api.post(`/company/reservations/${id}/reject`, data);
      return response.data;
    },
    reopen: async (id) => {
      const response = await api.post(`/company/reservations/${id}/reopen`);
      return response.data;
    },
    getStats: async (placeId) => {
      const response = await api.get(`/company/reservations/place/${placeId}/stats`);
      return response.data;
    },
  },
  rejectionReasons: {
    getAll: async () => {
      const response = await api.get('/rejection-reasons');
      return response.data;
    },
  },
};

// Servicios de razones de rechazo (admin)
export const rejectionReasonService = {
  getAll: async () => {
    const response = await api.get('/admin/rejection-reasons');
    return response.data;
  },
  getById: async (id) => {
    const response = await api.get(`/admin/rejection-reasons/${id}`);
    return response.data;
  },
  create: async (data) => {
    const response = await api.post('/admin/rejection-reasons', data);
    return response.data;
  },
  update: async (id, data) => {
    const response = await api.put(`/admin/rejection-reasons/${id}`, data);
    return response.data;
  },
  delete: async (id) => {
    const response = await api.delete(`/admin/rejection-reasons/${id}`);
    return response.data;
  },
};

export default api;

