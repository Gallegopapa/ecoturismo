import React, { useState, useEffect } from 'react';
import { adminService } from '../services/api';
import CreateUserModal from './CreateUserModal';
import EditUserModal from './EditUserModal';
import './admin.css';
import './AdminModals.css';

const UsersAdmin = () => {
  const [users, setUsers] = useState([]);
  const [places, setPlaces] = useState([]);
  const [loading, setLoading] = useState(true);
  const [message, setMessage] = useState('');
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [showEditModal, setShowEditModal] = useState(false);
  const [editingUserId, setEditingUserId] = useState(null);
  const [editingUser, setEditingUser] = useState(null);
  const [userFilter, setUserFilter] = useState('all'); // all, normal, empresa, admin

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
      const data = await adminService.places.getAll();
      setPlaces(Array.isArray(data) ? data : []);
    } catch (error) {
      console.error('Error al cargar lugares:', error);
    }
  };

  const showMessage = (msg, type = 'success') => {
    setMessage(msg);
    setTimeout(() => setMessage(''), 3000);
  };

  const handleDelete = async (id) => {
    if (!window.confirm('Â¿EstÃ¡s seguro de borrar este usuario?')) {
      return;
    }

    try {
      await adminService.users.delete(id);
      showMessage('Usuario eliminado correctamente');
      loadUsers();
    } catch (error) {
      console.error('Error al eliminar usuario:', error);
      showMessage(
        error.response?.data?.message || 'Error al eliminar usuario',
        'error'
      );
    }
  };

  const handleUserCreated = () => {
    setShowCreateModal(false);
    loadUsers();
    showMessage('Usuario empresa creado correctamente');
  };

  const handleEditPermissions = (user) => {
    setEditingUserId(user.id);
    setEditingUser(user);
    setShowEditModal(true);
  };

  const handleUserUpdated = () => {
    loadUsers();
    showMessage('Permisos actualizados correctamente');
  };

  const getPlaceNames = (user) => {
    if (Array.isArray(user.places_assigned) && user.places_assigned.length > 0) {
      return user.places_assigned.map(p => p.name).join(', ');
    }
    if (typeof user.lugares_asignados === 'number') {
      return `${user.lugares_asignados} lugar(es)`;
    }
    return '-';
  };

  const isUserAdmin = (user) => {
    return user?.tipo_usuario === 'admin' || user?.is_admin === true || user?.is_admin === 1 || user?.is_admin === '1';
  };

  const filteredUsers = users.filter(user => {
    if (userFilter === 'all') return true;
    if (userFilter === 'admin') {
      return isUserAdmin(user);
    }
    if (userFilter === 'normal') {
      return user.tipo_usuario === 'normal' && !isUserAdmin(user);
    }
    return user.tipo_usuario === userFilter && !isUserAdmin(user);
  });

  const getUserTypeColor = (user) => {
    if (isUserAdmin(user)) {
      return '#dc3545';
    }
    switch (user?.tipo_usuario) {
      case 'empresa':
        return '#0d6efd';
      case 'normal':
        return '#198754';
      default:
        return '#6c757d';
    }
  };

  const getUserTypeLabel = (user) => {
    if (isUserAdmin(user)) {
      return 'Administrador';
    }
    switch (user?.tipo_usuario) {
      case 'empresa':
        return 'Empresa/Lugar';
      case 'normal':
        return 'Cliente';
      default:
        return 'Usuario';
    }
  };

  const adminCount = users.filter(u => isUserAdmin(u)).length;
  const clientCount = users.filter(u => u.tipo_usuario === 'normal' && !isUserAdmin(u)).length;
  const companyCount = users.filter(u => u.tipo_usuario === 'empresa' && !isUserAdmin(u)).length;

  return (
    <div className="admin-panel">
      {message && (
        <div className={`admin-message ${message.includes('Error') ? 'error' : 'success'}`}>
          {message}
        </div>
      )}

      {/* Modal para crear usuario empresa */}
      <CreateUserModal
        isOpen={showCreateModal}
        onClose={() => setShowCreateModal(false)}
        onUserCreated={handleUserCreated}
        places={places}
      />

      <EditUserModal
        isOpen={showEditModal}
        onClose={() => setShowEditModal(false)}
        userId={editingUserId}
        user={editingUser}
        places={places}
        onUpdated={handleUserUpdated}
      />

      {/* SecciÃ³n de controles */}
      <div className="admin-list-section">
        <div style={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          marginBottom: '20px',
          flexWrap: 'wrap',
          gap: '10px'
        }}>
          <h2 style={{margin: 0}}>Gestion de Usuarios</h2>
          <button
            onClick={() => setShowCreateModal(true)}
            className="btn-primary"
            style={{
              padding: '10px 20px',
              cursor: 'pointer',
              whiteSpace: 'nowrap'
            }}
          >
            + Crear Usuario Empresa
          </button>
        </div>

        {/* Filtros */}
        <div style={{
          display: 'flex',
          gap: '10px',
          marginBottom: '20px',
          flexWrap: 'wrap'
        }}>
          <button
            onClick={() => setUserFilter('all')}
            style={{
              padding: '8px 16px',
              backgroundColor: userFilter === 'all' ? '#007bff' : '#e9ecef',
              color: userFilter === 'all' ? 'white' : '#333',
              border: 'none',
              borderRadius: '4px',
              cursor: 'pointer',
              fontWeight: userFilter === 'all' ? 'bold' : 'normal'
            }}
          >
            Todos
          </button>
          <button
            onClick={() => setUserFilter('empresa')}
            style={{
              padding: '8px 16px',
              backgroundColor: userFilter === 'empresa' ? '#0d6efd' : '#e9ecef',
              color: userFilter === 'empresa' ? 'white' : '#333',
              border: 'none',
              borderRadius: '4px',
              cursor: 'pointer',
              fontWeight: userFilter === 'empresa' ? 'bold' : 'normal'
            }}
          >
            Empresas/Lugares
          </button>
          <button
            onClick={() => setUserFilter('normal')}
            style={{
              padding: '8px 16px',
              backgroundColor: userFilter === 'normal' ? '#198754' : '#e9ecef',
              color: userFilter === 'normal' ? 'white' : '#333',
              border: 'none',
              borderRadius: '4px',
              cursor: 'pointer',
              fontWeight: userFilter === 'normal' ? 'bold' : 'normal'
            }}
          >
            Clientes
          </button>
          <button
            onClick={() => setUserFilter('admin')}
            style={{
              padding: '8px 16px',
              backgroundColor: userFilter === 'admin' ? '#dc3545' : '#e9ecef',
              color: userFilter === 'admin' ? 'white' : '#333',
              border: 'none',
              borderRadius: '4px',
              cursor: 'pointer',
              fontWeight: userFilter === 'admin' ? 'bold' : 'normal'
            }}
          >
            Administradores
          </button>
        </div>

        {/* Tabla de usuarios */}
        {loading ? (
          <p>Cargando usuarios...</p>
        ) : filteredUsers.length === 0 ? (
          <p>No hay usuarios en esta categorÃ­a</p>
        ) : (
          <div style={{overflowX: 'auto'}}>
            <table className="admin-table" style={{width: '100%', borderCollapse: 'collapse'}}>
              <thead>
                <tr style={{backgroundColor: '#f8f9fa'}}>
                  <th style={{padding: '12px', textAlign: 'left', borderBottom: '2px solid #dee2e6'}}>Nombre</th>
                  <th style={{padding: '12px', textAlign: 'left', borderBottom: '2px solid #dee2e6'}}>Email</th>
                  <th style={{padding: '12px', textAlign: 'left', borderBottom: '2px solid #dee2e6'}}>Tipo</th>
                  {userFilter === 'empresa' && (
                    <th style={{padding: '12px', textAlign: 'left', borderBottom: '2px solid #dee2e6'}}>Lugares Asignados</th>
                  )}
                  <th style={{padding: '12px', textAlign: 'center', borderBottom: '2px solid #dee2e6'}}>Acciones</th>
                </tr>
              </thead>
              <tbody>
                {filteredUsers.map((user) => (
                  <tr key={user.id} style={{borderBottom: '1px solid #dee2e6'}}>
                    <td style={{padding: '12px'}} data-label="Nombre">{user.name}</td>
                    <td style={{padding: '12px'}} data-label="Email">{user.email || '-'}</td>
                    <td style={{padding: '12px'}} data-label="Tipo">
                      <span
                        style={{
                          padding: '4px 12px',
                          borderRadius: '20px',
                          backgroundColor: getUserTypeColor(user),
                          color: 'white',
                          fontSize: '0.85em',
                          fontWeight: 'bold'
                        }}
                      >
                        {getUserTypeLabel(user)}
                      </span>
                    </td>
                    {userFilter === 'empresa' && (
                      <td style={{padding: '12px'}} data-label="Lugares">
                        <div style={{fontSize: '0.9em', color: '#666'}}>
                          {getPlaceNames(user)}
                        </div>
                      </td>
                    )}

                    <td style={{padding: '12px', textAlign: 'center'}} data-label="Acciones">
                      <button
                        onClick={() => handleEditPermissions(user)}
                        className="btn-primary"
                        style={{
                          padding: '6px 12px',
                          cursor: 'pointer'
                        }}
                      >
                        Editar permisos
                      </button>
                      <button
                        onClick={() => handleDelete(user.id)}
                        className="btn-delete"
                        style={{
                          padding: '6px 12px',
                          marginLeft: '5px',
                          cursor: 'pointer'
                        }}
                      >
                        Eliminar
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        {/* Resumen */}
        <div style={{
          marginTop: '20px',
          padding: '15px',
          backgroundColor: '#f8f9fa',
          borderRadius: '4px',
          fontSize: '0.9em',
          color: '#666'
        }}>
          <strong>Total de usuarios:</strong> {filteredUsers.length} 
          {userFilter !== 'all' && ` (${getUserTypeLabel(userFilter)})`}
          {' | '}
          <strong>Empresas:</strong> {companyCount}
          {' | '}
          <strong>Clientes:</strong> {clientCount}
          {' | '}
          <strong>Administradores:</strong> {adminCount}
        </div>
      </div>

      {/* Instrucciones */}
      <div style={{
        marginTop: '30px',
        padding: '20px',
        backgroundColor: '#e7f3ff',
        borderLeft: '4px solid #0d6efd',
        borderRadius: '4px'
      }}>
        <h3 style={{marginTop: 0, color: '#0d6efd'}}>Como crear un usuario Empresa</h3>
        <ol style={{marginBottom: 0, lineHeight: '1.8'}}>
          <li><strong>Haz clic</strong> en el boton "Crear Usuario Empresa" arriba</li>
          <li><strong>Rellena el formulario:</strong> Nombre, email, telefono, etc.</li>
          <li><strong>Selecciona el LUGAR</strong> para el cual trabaja esta empresa</li>
          <li><strong>Marca como "Principal"</strong> si es el contacto principal del lugar</li>
          <li><strong>Copia la contraseña generada</strong> y compartela con la empresa</li>
        </ol>
        <p style={{
          marginTop: '15px',
          padding: '10px',
          backgroundColor: '#fff',
          borderRadius: '4px',
          marginBottom: 0
        }}>
          <strong>asi de Importante:</strong> Cada usuario empresa vera SOLO las reservas del lugar asignado. 
          Si un lugar tiene multiples usuarios, todos veran las mismas reservas.
        </p>
      </div>
    </div>
  );
};

export default UsersAdmin;

