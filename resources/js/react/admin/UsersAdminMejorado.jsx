import React, { useState, useEffect } from 'react';
import { adminService } from '../services/api';
import CreateUserModal from './CreateUserModal';
import './admin.css';

const UsersAdmin = () => {
  const [users, setUsers] = useState([]);
  const [places, setPlaces] = useState([]);
  const [loading, setLoading] = useState(true);
  const [message, setMessage] = useState('');
  const [messageType, setMessageType] = useState('');
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [searchTerm, setSearchTerm] = useState('');

  useEffect(() => {
    loadUsers();
    loadPlaces();
  }, []);

  const loadUsers = async () => {
    try {
      setLoading(true);
      const data = await adminService.users.getAll();
      setUsers(Array.isArray(data) ? data : []);
    } catch (error) {
      console.error('Error al cargar usuarios:', error);
      showMessage('Error al cargar usuarios', 'error');
    } finally {
      setLoading(false);
    }
  };

  const loadPlaces = async () => {
    try {
      // Asumiendo que existe un servicio para lugares
      const response = await fetch('/api/places');
      const data = await response.json();
      setPlaces(Array.isArray(data) ? data : (data.data || []));
    } catch (error) {
      console.error('Error al cargar lugares:', error);
    }
  };

  const showMessage = (msg, type = 'success') => {
    setMessage(msg);
    setMessageType(type);
    setTimeout(() => setMessage(''), 3000);
  };

  const handleDeleteUser = async (userId) => {
    if (!window.confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
      return;
    }

    try {
      await adminService.users.delete(userId);
      showMessage('Usuario eliminado correctamente', 'success');
      loadUsers();
    } catch (error) {
      console.error('Error al eliminar usuario:', error);
      showMessage('Error al eliminar usuario', 'error');
    }
  };

  const handleUserCreated = (response) => {
    showMessage('Usuario creado correctamente', 'success');
    loadUsers();
  };

  const filteredUsers = users.filter(user => {
    const searchLower = searchTerm.toLowerCase();
    return (
      user.name.toLowerCase().includes(searchLower) ||
      (user.email && user.email.toLowerCase().includes(searchLower))
    );
  });

  const getTypeLabel = (tipoUsuario) => {
    const labels = {
      normal: 'Usuario Normal',
      empresa: 'Empresa',
      admin: 'Administrador'
    };
    return labels[tipoUsuario] || tipoUsuario;
  };

  const getTypeBadgeClass = (tipoUsuario) => {
    const classes = {
      normal: 'badge-normal',
      empresa: 'badge-empresa',
      admin: 'badge-admin'
    };
    return classes[tipoUsuario] || '';
  };

  if (loading) {
    return <div className="users-admin loading">Cargando usuarios...</div>;
  }

  return (
    <div className="users-admin">
      <div className="admin-header">
        <h1>Gestión de Usuarios</h1>
        <button
          className="btn btn-primary"
          onClick={() => setShowCreateModal(true)}
        >
          + Crear Nuevo Usuario
        </button>
      </div>

      {message && (
        <div className={`alert alert-${messageType}`}>
          {message}
        </div>
      )}

      <div className="search-bar">
        <input
          type="text"
          placeholder="Buscar por nombre o email..."
          value={searchTerm}
          onChange={(e) => setSearchTerm(e.target.value)}
        />
      </div>

      {filteredUsers.length === 0 ? (
        <div className="empty-state">
          <p>No hay usuarios que mostrar</p>
        </div>
      ) : (
        <div className="users-grid">
          {filteredUsers.map(user => (
            <div key={user.id} className="user-card">
              <div className="user-header">
                <h3>{user.name}</h3>
                <span className={`badge ${getTypeBadgeClass(user.tipo_usuario)}`}>
                  {getTypeLabel(user.tipo_usuario)}
                </span>
              </div>

              <div className="user-details">
                <p>
                  <strong>Email:</strong> {user.email || 'No configurado'}
                </p>
                <p>
                  <strong>Teléfono:</strong> {user.telefono || 'No configurado'}
                </p>
                {user.tipo_usuario === 'empresa' && (
                  <p>
                    <strong>Lugares:</strong> {user.lugares_asignados || 0}
                  </p>
                )}
                <p>
                  <strong>Registrado:</strong> {new Date(user.fecha_registro).toLocaleDateString('es-CO')}
                </p>
              </div>

              <div className="user-actions">
                <button className="btn btn-sm btn-secondary">
                  Editar
                </button>
                <button
                  className="btn btn-sm btn-danger"
                  onClick={() => handleDeleteUser(user.id)}
                >
                  Eliminar
                </button>
              </div>
            </div>
          ))}
        </div>
      )}

      {/* Modal para crear usuario */}
      <CreateUserModal
        isOpen={showCreateModal}
        onClose={() => setShowCreateModal(false)}
        onUserCreated={handleUserCreated}
        places={places}
      />
    </div>
  );
};

export default UsersAdmin;
