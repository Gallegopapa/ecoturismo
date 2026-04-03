import React, { createContext, useContext, useState, useEffect } from 'react';
import { authService } from '../services/api';

const AuthContext = createContext(null);

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth debe usarse dentro de AuthProvider');
  }
  return context;
};

/**
 * Transmite la foto_perfil originaria intacta sin truncarla, permitiendo 
 * que resuelva orgánicamente en todos los componentes y previendo fallos de parseo.
 */
const normalizeUser = (userData) => {
  if (!userData) return userData;
  // Guardamos la URL nativa que dio el sistema en toda su gloria
  return userData;
};

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [isAuthenticated, setIsAuthenticated] = useState(false);

  // Cargar usuario al iniciar
  useEffect(() => {
    loadUser();
  }, []);

  const loadUser = async () => {
    try {
      const token = localStorage.getItem('token');
      if (token) {
        const response = await authService.verifyToken();
        if (response.valid) {
          const normalizedUser = normalizeUser(response.user);
          setUser(normalizedUser);
          setIsAuthenticated(true);
          localStorage.setItem('user', JSON.stringify(normalizedUser));
        } else {
          // Token inválido, limpiar
          localStorage.removeItem('token');
          localStorage.removeItem('user');
        }
      }
    } catch (error) {
      // Error al cargar usuario - limpiar sesión silenciosamente
      localStorage.removeItem('token');
      localStorage.removeItem('user');
    } finally {
      setLoading(false);
    }
  };

  const login = async (credentials) => {
    try {
      // Validar que se envíen los datos necesarios (login por correo o usuario)
      if (!credentials.login) {
        return { 
          success: false, 
          error: 'Debe proporcionar tu correo o usuario.' 
        };
      }
      
      if (!credentials.password) {
        return { 
          success: false, 
          error: 'La contraseña es requerida.' 
        };
      }

      const response = await authService.login(credentials);
      
      // Verificar que la respuesta tenga token y usuario
      if (!response.token || !response.user) {
        return { 
          success: false, 
          error: 'Correo o contraseña incorrectos. Por favor, intenta de nuevo.' 
        };
      }
      
      // Guardar token y usuario (normalizado)
      const normalizedUser = normalizeUser(response.user);
      localStorage.setItem('token', response.token);
      localStorage.setItem('user', JSON.stringify(normalizedUser));
      
      // Actualizar estado de forma síncrona para evitar race conditions
      setUser(normalizedUser);
      setIsAuthenticated(true);
      
      return { success: true, user: normalizedUser };
    } catch (error) {
      console.error('Error en login:', error);
      
      // Manejo detallado de errores
      let errorMessage = 'Error al iniciar sesión';
      let errors = {};
      
      if (error.response) {
        // Error de respuesta del servidor
        const data = error.response.data;
        errorMessage = data.message || errorMessage;
        
        if (data.errors) {
          errors = data.errors;
          // Priorizar mensaje de credentials si existe
          if (data.errors.credentials) {
            errorMessage = Array.isArray(data.errors.credentials) 
              ? data.errors.credentials[0] 
              : data.errors.credentials;
          } else {
            // Tomar el primer error disponible
            const firstError = Object.values(data.errors)[0];
            errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
          }
        }
      } else if (error.request) {
        // Error de red
        errorMessage = 'No se pudo conectar con el servidor. Verifica tu conexión a internet.';
      } else {
        // Error al configurar la petición
        errorMessage = error.message || errorMessage;
      }
      
      return { success: false, error: errorMessage, errors };
    }
  };

  const register = async (userData) => {
    try {
      if (!userData.email) {
        return {
          success: false,
          error: 'El email es requerido.',
        };
      }

      const response = await authService.register(userData);

      // Igual que en login, evitamos sesión inválida si backend no trae usuario/token completos.
      if (!response.token || !response.user || !response.user.id) {
        return {
          success: false,
          error: 'Correo o usuario ya existente. Por favor, intenta con otro.',
        };
      }
      
      // Guardar token y usuario (normalizado)
      const normalizedUser = normalizeUser(response.user);
      localStorage.setItem('token', response.token);
      localStorage.setItem('user', JSON.stringify(normalizedUser));
      
      // Actualizar estado de forma síncrona para evitar race conditions
      setUser(normalizedUser);
      setIsAuthenticated(true);
      
      return { success: true, user: normalizedUser };
    } catch (error) {
      const errors = error.response?.data?.errors || {};
      const errorMessage = error.response?.data?.message || 
                          Object.values(errors).flat()[0] ||
                          'Error al registrarse';
      return { success: false, error: errorMessage, errors };
    }
  };

  const logout = async () => {
    try {
      await authService.logout();
    } catch (error) {
      console.error('Error al cerrar sesión:', error);
    } finally {
      setUser(null);
      setIsAuthenticated(false);
      localStorage.removeItem('token');
      localStorage.removeItem('user');
    }
  };

  const updateUser = (userData) => {
    const normalizedUser = normalizeUser(userData);
    setUser(normalizedUser);
    localStorage.setItem('user', JSON.stringify(normalizedUser));
  };

  const value = {
    user,
    loading,
    isAuthenticated,
    login,
    register,
    logout,
    loadUser,
    updateUser,
    isAdmin: user?.is_admin || false,
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};