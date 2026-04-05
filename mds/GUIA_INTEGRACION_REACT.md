# Guía de Integración React - Validaciones Backend

## 🎯 Cómo Usar los Endpoints desde React/JSX

Esta guía muestra cómo conectar tus componentes React con los controllers que ahora contienen todas las validaciones.

---

## 🔧 Configuración Inicial

### 1. Obtener el CSRF Token

```javascript
// Agregar en el HTML base o en el index
// <meta name="csrf-token" content="{{ csrf_token() }}">

// Obtener el token en React
const getCsrfToken = () => {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
};
```

### 2. Helper para Peticiones API

```javascript
// utils/api.js
export const apiRequest = async (url, method = 'GET', data = null) => {
  const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-CSRF-TOKEN': getCsrfToken()
  };

  const config = {
    method,
    headers,
    credentials: 'same-origin' // Para incluir cookies de sesión
  };

  if (data && method !== 'GET') {
    config.body = JSON.stringify(data);
  }

  try {
    const response = await fetch(url, config);
    const result = await response.json();

    if (!response.ok) {
      throw {
        status: response.status,
        errors: result.errors || {},
        message: result.message || 'Error en la petición'
      };
    }

    return result;
  } catch (error) {
    throw error;
  }
};
```

---

## 🔐 Autenticación

### Login Component

```jsx
// components/Login.jsx
import React, { useState } from 'react';
import { apiRequest } from '../utils/api';

const Login = () => {
  const [formData, setFormData] = useState({
    name: '',
    password: ''
  });
  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
    // Limpiar error del campo cuando el usuario empieza a escribir
    if (errors[name]) {
      setErrors(prev => ({ ...prev, [name]: null }));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setErrors({});

    try {
      await apiRequest('/login', 'POST', formData);
      // Redireccionar después del login exitoso
      window.location.href = '/';
    } catch (error) {
      if (error.status === 422) {
        // Errores de validación
        setErrors(error.errors);
      } else {
        setErrors({ general: error.message });
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="login-form">
      <div className="form-group">
        <label htmlFor="name">Nombre de usuario o Email:</label>
        <input
          type="text"
          id="name"
          name="name"
          value={formData.name}
          onChange={handleChange}
          className={errors.name ? 'error' : ''}
        />
        {errors.name && <span className="error-message">{errors.name}</span>}
      </div>

      <div className="form-group">
        <label htmlFor="password">Contraseña:</label>
        <input
          type="password"
          id="password"
          name="password"
          value={formData.password}
          onChange={handleChange}
          className={errors.password ? 'error' : ''}
        />
        {errors.password && <span className="error-message">{errors.password}</span>}
      </div>

      {errors.credentials && (
        <div className="alert alert-error">{errors.credentials}</div>
      )}

      {errors.general && (
        <div className="alert alert-error">{errors.general}</div>
      )}

      <button type="submit" disabled={loading}>
        {loading ? 'Iniciando sesión...' : 'Iniciar sesión'}
      </button>

      <p>
        ¿Todavía no tienes cuenta? <a href="/registro">Regístrate</a>
      </p>
    </form>
  );
};

export default Login;
```

### Register Component

```jsx
// components/Register.jsx
import React, { useState } from 'react';
import { apiRequest } from '../utils/api';

const Register = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
  });
  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
    if (errors[name]) {
      setErrors(prev => ({ ...prev, [name]: null }));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setErrors({});

    try {
      await apiRequest('/registro', 'POST', formData);
      // Redireccionar al login con mensaje de éxito
      window.location.href = '/login?registered=true';
    } catch (error) {
      if (error.status === 422) {
        setErrors(error.errors);
      } else {
        setErrors({ general: error.message });
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="register-form">
      <h3>Regístrate...</h3>

      <div className="form-group">
        <label htmlFor="name">Nombre de usuario:</label>
        <input
          type="text"
          id="name"
          name="name"
          value={formData.name}
          onChange={handleChange}
          className={errors.name ? 'error' : ''}
        />
        {errors.name && <span className="error-message">{errors.name}</span>}
      </div>

      <div className="form-group">
        <label htmlFor="email">Correo electrónico:</label>
        <input
          type="email"
          id="email"
          name="email"
          value={formData.email}
          onChange={handleChange}
          className={errors.email ? 'error' : ''}
        />
        {errors.email && <span className="error-message">{errors.email}</span>}
      </div>

      <div className="form-group">
        <label htmlFor="password">Contraseña:</label>
        <input
          type="password"
          id="password"
          name="password"
          value={formData.password}
          onChange={handleChange}
          className={errors.password ? 'error' : ''}
        />
        {errors.password && <span className="error-message">{errors.password}</span>}
      </div>

      <div className="form-group">
        <label htmlFor="password_confirmation">Repita Contraseña:</label>
        <input
          type="password"
          id="password_confirmation"
          name="password_confirmation"
          value={formData.password_confirmation}
          onChange={handleChange}
        />
      </div>

      {errors.general && (
        <div className="alert alert-error">{errors.general}</div>
      )}

      <button type="submit" disabled={loading}>
        {loading ? 'Registrando...' : 'Ingresar'}
      </button>

      <p>
        ¿Ya tienes una cuenta? <a href="/login">Inicia Sesión</a>
      </p>
    </form>
  );
};

export default Register;
```

---

## 👤 Perfil y Configuración

### Profile Component

```jsx
// components/Profile.jsx
import React, { useState } from 'react';
import { apiRequest } from '../utils/api';

const Profile = () => {
  const [activeSection, setActiveSection] = useState('perfil');
  const [profileData, setProfileData] = useState({
    name: '',
    email: ''
  });
  const [passwordData, setPasswordData] = useState({
    current_password: '',
    new_password: '',
    new_password_confirmation: ''
  });
  const [errors, setErrors] = useState({});
  const [successMessage, setSuccessMessage] = useState('');

  const handleUpdateProfile = async (e) => {
    e.preventDefault();
    setErrors({});
    setSuccessMessage('');

    try {
      const result = await apiRequest('/perfil', 'PUT', profileData);
      setSuccessMessage(result.message);
    } catch (error) {
      if (error.status === 422) {
        setErrors(error.errors);
      }
    }
  };

  const handleChangePassword = async (e) => {
    e.preventDefault();
    setErrors({});
    setSuccessMessage('');

    try {
      const result = await apiRequest('/perfil/password', 'PUT', passwordData);
      setSuccessMessage(result.message);
      // Limpiar formulario
      setPasswordData({
        current_password: '',
        new_password: '',
        new_password_confirmation: ''
      });
    } catch (error) {
      if (error.status === 422) {
        setErrors(error.errors);
      } else {
        setErrors({ general: error.message });
      }
    }
  };

  return (
    <div className="config-container">
      <div className="config-menu">
        <div 
          className={`menu-item ${activeSection === 'perfil' ? 'active' : ''}`}
          onClick={() => setActiveSection('perfil')}
        >
          <span>👤 Perfil</span>
        </div>
        <div 
          className={`menu-item ${activeSection === 'seguridad' ? 'active' : ''}`}
          onClick={() => setActiveSection('seguridad')}
        >
          <span>🔒 Cambia Tu Contraseña</span>
        </div>
      </div>

      <div className="config-content">
        {successMessage && (
          <div className="alert alert-success">{successMessage}</div>
        )}

        {/* Sección Perfil */}
        {activeSection === 'perfil' && (
          <section id="perfil">
            <h2>Información del Perfil</h2>
            <form onSubmit={handleUpdateProfile}>
              <div className="form-group">
                <label htmlFor="name">Nombre de Usuario</label>
                <input
                  type="text"
                  id="name"
                  name="name"
                  value={profileData.name}
                  onChange={(e) => setProfileData({...profileData, name: e.target.value})}
                />
                {errors.name && <span className="error-message">{errors.name}</span>}
              </div>

              <div className="form-group">
                <label htmlFor="email">Correo Electrónico</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  value={profileData.email}
                  onChange={(e) => setProfileData({...profileData, email: e.target.value})}
                />
                {errors.email && <span className="error-message">{errors.email}</span>}
              </div>

              <button type="submit">Actualizar Perfil</button>
            </form>
          </section>
        )}

        {/* Sección Seguridad */}
        {activeSection === 'seguridad' && (
          <section id="seguridad">
            <h2>Cambiar Contraseña</h2>
            <form onSubmit={handleChangePassword}>
              <div className="form-group">
                <label htmlFor="current_password">Contraseña Actual</label>
                <input
                  type="password"
                  id="current_password"
                  name="current_password"
                  value={passwordData.current_password}
                  onChange={(e) => setPasswordData({...passwordData, current_password: e.target.value})}
                />
                {errors.current_password && (
                  <span className="error-message">{errors.current_password}</span>
                )}
              </div>

              <div className="form-group">
                <label htmlFor="new_password">Nueva Contraseña</label>
                <input
                  type="password"
                  id="new_password"
                  name="new_password"
                  value={passwordData.new_password}
                  onChange={(e) => setPasswordData({...passwordData, new_password: e.target.value})}
                />
                {errors.new_password && (
                  <span className="error-message">{errors.new_password}</span>
                )}
              </div>

              <div className="form-group">
                <label htmlFor="new_password_confirmation">Confirmar Nueva Contraseña</label>
                <input
                  type="password"
                  id="new_password_confirmation"
                  name="new_password_confirmation"
                  value={passwordData.new_password_confirmation}
                  onChange={(e) => setPasswordData({...passwordData, new_password_confirmation: e.target.value})}
                />
              </div>

              {errors.general && (
                <div className="alert alert-error">{errors.general}</div>
              )}

              <button type="submit" className="btn-cambiar">
                Cambiar Contraseña
              </button>
            </form>
          </section>
        )}
      </div>
    </div>
  );
};

export default Profile;
```

---

## 📝 Reservaciones

### Reservation Form Component

```jsx
// components/ReservationForm.jsx
import React, { useState } from 'react';
import { apiRequest } from '../utils/api';

const ReservationForm = ({ placeId, placeName }) => {
  const [formData, setFormData] = useState({
    place_id: placeId,
    fecha_visita: '',
    hora_visita: '',
    personas: 1,
    telefono_contacto: '',
    comentarios: ''
  });
  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setErrors({});

    try {
      await apiRequest('/reservas', 'POST', formData);
      alert('Reserva creada correctamente');
      window.location.href = '/reservas';
    } catch (error) {
      if (error.status === 422) {
        setErrors(error.errors);
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="reservation-form">
      <h2>Reservar: {placeName}</h2>

      <div className="form-group">
        <label htmlFor="fecha_visita">Fecha de Visita:</label>
        <input
          type="date"
          id="fecha_visita"
          name="fecha_visita"
          value={formData.fecha_visita}
          onChange={(e) => setFormData({...formData, fecha_visita: e.target.value})}
          min={new Date().toISOString().split('T')[0]}
        />
        {errors.fecha_visita && <span className="error-message">{errors.fecha_visita}</span>}
      </div>

      <div className="form-group">
        <label htmlFor="hora_visita">Hora de Visita:</label>
        <input
          type="time"
          id="hora_visita"
          name="hora_visita"
          value={formData.hora_visita}
          onChange={(e) => setFormData({...formData, hora_visita: e.target.value})}
        />
        {errors.hora_visita && <span className="error-message">{errors.hora_visita}</span>}
      </div>

      <div className="form-group">
        <label htmlFor="personas">Número de Personas:</label>
        <input
          type="number"
          id="personas"
          name="personas"
          value={formData.personas}
          onChange={(e) => setFormData({...formData, personas: parseInt(e.target.value)})}
          min="1"
          max="50"
        />
        {errors.personas && <span className="error-message">{errors.personas}</span>}
      </div>

      <div className="form-group">
        <label htmlFor="telefono_contacto">Teléfono de Contacto:</label>
        <input
          type="tel"
          id="telefono_contacto"
          name="telefono_contacto"
          value={formData.telefono_contacto}
          onChange={(e) => setFormData({...formData, telefono_contacto: e.target.value})}
        />
        {errors.telefono_contacto && <span className="error-message">{errors.telefono_contacto}</span>}
      </div>

      <div className="form-group">
        <label htmlFor="comentarios">Comentarios Adicionales:</label>
        <textarea
          id="comentarios"
          name="comentarios"
          value={formData.comentarios}
          onChange={(e) => setFormData({...formData, comentarios: e.target.value})}
          rows="4"
        />
        {errors.comentarios && <span className="error-message">{errors.comentarios}</span>}
      </div>

      <button type="submit" disabled={loading}>
        {loading ? 'Creando reserva...' : 'Crear Reserva'}
      </button>
    </form>
  );
};

export default ReservationForm;
```

---

## 💬 Mensajes/Comentarios

### Message Form Component

```jsx
// components/MessageForm.jsx
import React, { useState } from 'react';
import { apiRequest } from '../utils/api';

const MessageForm = () => {
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    setSuccess('');

    try {
      const result = await apiRequest('/mensajes', 'POST', { message });
      setSuccess(result.message);
      setMessage(''); // Limpiar el textarea
    } catch (error) {
      if (error.status === 422 && error.errors.message) {
        setError(error.errors.message[0]);
      } else {
        setError(error.message);
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="message-form">
      <div className="input-container">
        <textarea
          name="message"
          value={message}
          onChange={(e) => setMessage(e.target.value)}
          placeholder="Déjanos tu comentario: "
          rows="5"
          className={error ? 'error' : ''}
        />
        {error && <span className="error-message">{error}</span>}
        {success && <span className="success-message">{success}</span>}
      </div>

      <button type="submit" disabled={loading} className="btn">
        {loading ? 'Enviando...' : 'Enviar'}
      </button>
    </form>
  );
};

export default MessageForm;
```

---

## ⭐ Favoritos

### Favorites Component

```jsx
// components/Favorites.jsx
import React, { useState } from 'react';
import { apiRequest } from '../utils/api';

const FavoriteButton = ({ placeId, isFavorite, onToggle }) => {
  const [loading, setLoading] = useState(false);

  const handleToggle = async () => {
    setLoading(true);

    try {
      if (isFavorite) {
        // Remover de favoritos
        await apiRequest(`/favoritos/${placeId}`, 'DELETE');
      } else {
        // Agregar a favoritos
        await apiRequest('/favoritos', 'POST', { place_id: placeId });
      }
      onToggle();
    } catch (error) {
      alert(error.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <button 
      onClick={handleToggle} 
      disabled={loading}
      className={`favorite-btn ${isFavorite ? 'active' : ''}`}
    >
      {loading ? '...' : isFavorite ? '❤️' : '🤍'}
    </button>
  );
};

export default FavoriteButton;
```

---

## 🎨 Estilos CSS para Errores

```css
/* styles/forms.css */

.form-group {
  margin-bottom: 1rem;
}

.form-group input.error,
.form-group textarea.error {
  border-color: #e74c3c;
  background-color: #ffe6e6;
}

.error-message {
  display: block;
  color: #e74c3c;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.success-message {
  display: block;
  color: #27ae60;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.alert {
  padding: 1rem;
  border-radius: 4px;
  margin-bottom: 1rem;
}

.alert-error {
  background-color: #f8d7da;
  border: 1px solid #f5c6cb;
  color: #721c24;
}

.alert-success {
  background-color: #d4edda;
  border: 1px solid #c3e6cb;
  color: #155724;
}
```

---

## 🔄 Custom Hook para Formularios

```javascript
// hooks/useForm.js
import { useState } from 'react';

export const useForm = (initialValues = {}) => {
  const [values, setValues] = useState(initialValues);
  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setValues(prev => ({ ...prev, [name]: value }));
    // Limpiar error cuando el usuario empieza a escribir
    if (errors[name]) {
      setErrors(prev => ({ ...prev, [name]: null }));
    }
  };

  const handleSubmit = async (callback) => {
    setLoading(true);
    setErrors({});

    try {
      await callback(values);
    } catch (error) {
      if (error.status === 422) {
        setErrors(error.errors);
      } else {
        setErrors({ general: error.message });
      }
    } finally {
      setLoading(false);
    }
  };

  const reset = () => {
    setValues(initialValues);
    setErrors({});
  };

  return {
    values,
    errors,
    loading,
    handleChange,
    handleSubmit,
    setValues,
    setErrors,
    reset
  };
};
```

### Uso del Hook:

```jsx
import { useForm } from '../hooks/useForm';
import { apiRequest } from '../utils/api';

const LoginWithHook = () => {
  const { values, errors, loading, handleChange, handleSubmit } = useForm({
    name: '',
    password: ''
  });

  const onSubmit = async (formData) => {
    await apiRequest('/login', 'POST', formData);
    window.location.href = '/';
  };

  return (
    <form onSubmit={(e) => {
      e.preventDefault();
      handleSubmit(onSubmit);
    }}>
      <input
        type="text"
        name="name"
        value={values.name}
        onChange={handleChange}
      />
      {errors.name && <span>{errors.name}</span>}
      
      <input
        type="password"
        name="password"
        value={values.password}
        onChange={handleChange}
      />
      {errors.password && <span>{errors.password}</span>}
      
      <button type="submit" disabled={loading}>
        {loading ? 'Cargando...' : 'Iniciar sesión'}
      </button>
    </form>
  );
};
```

---

## 📚 Resumen

Con esta guía, puedes:

1. ✅ Conectar cualquier componente React con los controllers del backend
2. ✅ Manejar errores de validación de forma elegante
3. ✅ Mostrar mensajes de éxito/error al usuario
4. ✅ Implementar loading states
5. ✅ Limpiar errores cuando el usuario interactúa con el formulario
6. ✅ Reutilizar código con custom hooks

Todos los controllers ya tienen las validaciones implementadas, solo necesitas conectarlos desde React! 🚀
