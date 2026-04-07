import React, { useState, useEffect } from 'react';
import { adminService, categoriesService } from '../services/api';
import './admin.css';

const EcohotelsAdmin = () => {
  const [ecohotels, setEcohotels] = useState([]);
  const [categories, setCategories] = useState([]);
  const [places, setPlaces] = useState([]);
  const [loading, setLoading] = useState(true);
  const [message, setMessage] = useState('');
  const [editingEcohotel, setEditingEcohotel] = useState(null);
  const [viewingEcohotel, setViewingEcohotel] = useState(null);
  const [imagePreview, setImagePreview] = useState(null);
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [formData, setFormData] = useState({
    name: '',
    location: '',
    description: '',
    image: null,
    latitude: '',
    longitude: '',
    telefono: '',
    email: '',
    sitio_web: '',
    categories: [],
    places: [],
  });

  useEffect(() => {
    loadEcohotels();
    loadCategories();
    loadPlaces();
  }, []);

  const loadPlaces = async () => {
    try {
      const data = await adminService.places.getAll();
      setPlaces(Array.isArray(data) ? data : []);
    } catch (error) {
      console.error('Error al cargar lugares:', error);
    }
  };

  const loadCategories = async () => {
    try {
      const data = await categoriesService.getAll();
      setCategories(Array.isArray(data) ? data : []);
    } catch (error) {
      console.error('Error al cargar categorías:', error);
    }
  };

  const loadEcohotels = async () => {
    try {
      setLoading(true);
      const data = await adminService.ecohotels.getAll();
      setEcohotels(Array.isArray(data) ? data : []);
      setLoading(false);
    } catch (error) {
      console.error('Error al cargar ecohoteles:', error);
      setMessage('Error al cargar ecohoteles');
      setLoading(false);
    }
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      if (file.size > 5242880) {
        setMessage('La imagen no puede exceder 5MB');
        return;
      }
      setFormData({ ...formData, image: file });
      setImagePreview(URL.createObjectURL(file));
    } else {
      setFormData({ ...formData, image: null });
      setImagePreview(null);
    }
  };

  const handleCategoryChange = (categoryId) => {
    setFormData((prev) => {
      const isSelected = prev.categories.includes(categoryId);
      return {
        ...prev,
        categories: isSelected
          ? prev.categories.filter((id) => id !== categoryId)
          : [...prev.categories, categoryId],
      };
    });
  };

  const handleCreate = async (e) => {
    e.preventDefault();
    try {
      await adminService.ecohotels.create(formData);
      setMessage('Ecohotel creado correctamente');
      setShowCreateModal(false);
      resetForm();
      loadEcohotels();
    } catch (error) {
      const respData = error.response?.data;
      if (respData?.errors) {
        // Laravel validation errors
        const errorStrings = Object.values(respData.errors).flat().join(' | ');
        setMessage('Error de validación: ' + errorStrings);
      } else {
        setMessage(respData?.message || 'Error al crear el ecohotel');
      }
    }
  };

  const handleEdit = async (e) => {
    e.preventDefault();
    try {
      await adminService.ecohotels.update(editingEcohotel.id, formData);
      setMessage('Ecohotel actualizado correctamente');
      setEditingEcohotel(null);
      resetForm();
      loadEcohotels();
    } catch (error) {
      const respData = error.response?.data;
      if (respData?.errors) {
        // Laravel validation errors
        const errorStrings = Object.values(respData.errors).flat().join(' | ');
        setMessage('Error de validación: ' + errorStrings);
      } else {
        setMessage(respData?.message || 'Error al actualizar el ecohotel');
      }
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Estás seguro de eliminar este ecohotel?')) return;
    
    try {
      await adminService.ecohotels.delete(id);
      setMessage('Ecohotel eliminado correctamente');
      loadEcohotels();
    } catch (error) {
      setMessage(error.response?.data?.message || 'Error al eliminar el ecohotel');
    }
  };

  const openCreateModal = () => {
    resetForm();
    setShowCreateModal(true);
  };

  const openEditModal = (ecohotel) => {
    setEditingEcohotel(ecohotel);
    setFormData({
      name: ecohotel.name || '',
      location: ecohotel.location || '',
      description: ecohotel.description || '',
      image: null,
      latitude: ecohotel.latitude || '',
      longitude: ecohotel.longitude || '',
      telefono: ecohotel.telefono || '',
      email: ecohotel.email || '',
      sitio_web: ecohotel.sitio_web || '',
      categories: ecohotel.categories ? ecohotel.categories.map((cat) => cat.id) : [],
      places: ecohotel.places ? ecohotel.places.map((p) => p.id) : [],
    });
  };

  const openViewModal = (ecohotel) => {
    setViewingEcohotel(ecohotel);
  };

  const resetForm = () => {
    setFormData({
      name: '',
      location: '',
      description: '',
      image: null,
      latitude: '',
      longitude: '',
      telefono: '',
      email: '',
      sitio_web: '',
      categories: [],
      places: [],
    });
    setImagePreview(null);
  };

  const closeModals = () => {
    setShowCreateModal(false);
    setEditingEcohotel(null);
    setViewingEcohotel(null);
    resetForm();
  };

  if (loading) {
    return <div className="loading">Cargando ecohoteles...</div>;
  }

  return (
    <div className="admin-content">
      <div className="admin-header">
        <h2>Gestión de Ecohoteles</h2>
        <button className="btn-primary" onClick={openCreateModal}>
          Crear Ecohotel
        </button>
      </div>

      {message && (
        <div className={`message ${message.toLowerCase().includes('error') ? 'error' : 'success'}`}>
          {message}
          <button className="close-btn" onClick={() => setMessage('')}>×</button>
        </div>
      )}

      <div className="table-container">
        <table className="admin-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Ubicación</th>
              <th>Teléfono</th>
              <th>Email</th>
              <th>Categorías</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {ecohotels.length === 0 ? (
              <tr>
                <td colSpan="7" style={{ textAlign: 'center' }}>
                  No hay ecohoteles registrados
                </td>
              </tr>
            ) : (
              ecohotels.map((ecohotel) => (
                <tr key={ecohotel.id}>
                  <td data-label="ID">{ecohotel.id}</td>
                  <td data-label="Nombre">{ecohotel.name}</td>
                  <td data-label="Ubicación">{ecohotel.location || 'N/A'}</td>
                  <td data-label="Teléfono">{ecohotel.telefono || 'N/A'}</td>
                  <td data-label="Email">{ecohotel.email || 'N/A'}</td>
                  <td data-label="Categorías">
                    {ecohotel.categories && ecohotel.categories.length > 0
                      ? ecohotel.categories.map((cat) => cat.name).join(', ')
                      : 'Sin categorías'}
                  </td>
                  <td data-label="Acciones">
                    <div className="action-buttons">
                      <button
                        className="btn-action btn-view"
                        onClick={() => openViewModal(ecohotel)}
                        title="Ver detalles"
                      >
                        👁️
                      </button>
                      <button
                        className="btn-action btn-edit"
                        onClick={() => openEditModal(ecohotel)}
                        title="Editar"
                      >
                        ✏️
                      </button>
                      <button
                        className="btn-action btn-delete"
                        onClick={() => handleDelete(ecohotel.id)}
                        title="Eliminar"
                      >
                        🗑️
                      </button>
                    </div>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      {/* Modal Crear */}
      {showCreateModal && (
        <div className="modal-overlay" onClick={(e) => { e.stopPropagation(); closeModals(); }}>
          <div className="modal-content" onClick={(e) => e.stopPropagation()}>
            <div className="modal-header">
              <h3>Crear Ecohotel</h3>
              <button type="button" className="close-btn" onClick={closeModals}>×</button>
            </div>
            {message && (
              <div className={`message ${message.toLowerCase().includes('error') ? 'error' : 'success'}`} style={{marginBottom: '1rem'}}>
                {message}
                <button className="close-btn" onClick={() => setMessage('')}>×</button>
              </div>
            )}
            <form onSubmit={handleCreate} className="modal-form">
              
              {/* SECCIÓN: Información Básica */}
              <div className="form-section">
                <div className="form-section-title">
                  <div className="form-section-icon">1</div>
                  Información Básica
                </div>
                <div className="form-group">
                  <label htmlFor="name" className="required">Nombre</label>
                  <input
                    placeholder='Ej: Eco Resort La Selva'
                    type="text"
                    id="name"
                    name="name"
                    value={formData.name}
                    onChange={handleInputChange}
                    required
                  />
                </div>

                <div className="form-group">
                  <label htmlFor="description">Descripción</label>
                  <textarea
                    placeholder='Describe el ecohotel: amenidades, características especiales, experiencias únicas...'
                    id="description"
                    name="description"
                    value={formData.description}
                    onChange={handleInputChange}
                    rows="4"
                  />
                </div>
              </div>

              {/* SECCIÓN: Ubicación */}
              <div className="form-section">
                <div className="form-section-title">
                  <div className="form-section-icon">2</div>
                  Ubicación
                </div>
                <div className="form-group">
                  <label htmlFor="location">Ubicación</label>
                  <input
                    placeholder='Ej: Municipio de Pereira, Risaralda'
                    type="text"
                    id="location"
                    name="location"
                    value={formData.location}
                    onChange={handleInputChange}
                  />
                </div>

                <div className="form-row">
                  <div className="form-group">
                    <label htmlFor="latitude">Latitud</label>
                    <input
                      placeholder='Ej: 4.8133'
                      type="number"
                      id="latitude"
                      name="latitude"
                      value={formData.latitude}
                      onChange={handleInputChange}
                      step="any"
                    />
                  </div>
                  <div className="form-group">
                    <label htmlFor="longitude">Longitud</label>
                    <input
                      placeholder='Ej: -75.6963'
                      type="number"
                      id="longitude"
                      name="longitude"
                      value={formData.longitude}
                      onChange={handleInputChange}
                      step="any"
                    />
                  </div>
                </div>
              </div>

              {/* SECCIÓN: Contacto */}
              <div className="form-section">
                <div className="form-section-title">
                  <div className="form-section-icon">3</div>
                  Información de Contacto
                </div>
                <div className="form-group">
                  <label htmlFor="telefono">Teléfono</label>
                  <input
                    placeholder='Ej: +57 300 1234567'
                    type="text"
                    id="telefono"
                    name="telefono"
                    value={formData.telefono}
                    onChange={handleInputChange}
                  />
                </div>

                <div className="form-group">
                  <label htmlFor="email">Email</label>
                  <input
                    placeholder='Ej: contacto@ecohotel.com'
                    type="email"
                    id="email"
                    name="email"
                    value={formData.email}
                    onChange={handleInputChange}
                  />
                </div>

                <div className="form-group">
                  <label htmlFor="sitio_web">Sitio Web</label>
                  <input
                    type="url"
                    id="sitio_web"
                    name="sitio_web"
                    value={formData.sitio_web}
                    onChange={handleInputChange}
                    placeholder="https://www.ecohotel.com"
                  />
                </div>
              </div>

              {/* SECCIÓN: Imagen */}
              <div className="form-section">
                <div className="form-section-title">
                  <div className="form-section-icon">4</div>
                  Imagen Representativa
                </div>
                {imagePreview ? (
                  <div className="image-preview">
                    <label style={{ display: 'block', marginBottom: '0.75rem', color: '#2c5f2d', fontWeight: '600', fontSize: '0.9rem' }}>
                      Vista Previa de la Nueva Imagen
                    </label>
                    <img src={imagePreview} alt="Vista previa de nueva imagen" />
                  </div>
                ) : null}
                <div className="form-group">
                  <label htmlFor="image">Selecciona una imagen (máx. 5MB)</label>
                  <input
                    type="file"
                    id="image"
                    name="image"
                    onChange={handleImageChange}
                    accept="image/*"
                  />
                </div>
              </div>

              {/* SECCIÓN: Categorías
              <div className="form-section">
                <div className="form-section-title">
                  <div className="form-section-icon">5</div>
                  Categorías
                </div>
                <label style={{ marginBottom: '1rem', display: 'block', color: '#666', fontSize: '0.9rem' }}>
                  Selecciona las categorías que mejor describen este ecohotel
                </label>
                <div className="categories-checkbox">
                  {categories.map((category) => (
                    <label key={category.id} className="checkbox-label">
                      <input
                        type="checkbox"
                        checked={formData.categories.includes(category.id)}
                        onChange={() => handleCategoryChange(category.id)}
                      />
                      {category.name}
                    </label>
                  ))}
                </div>
              </div> */}

              {/* SECCIÓN: Lugares relacionados */}
              <div className="form-section">
                <div className="form-section-title">
                  <div className="form-section-icon">6</div>
                  Lugares relacionados
                </div>
                <div className="places-checkboxes" style={{ display: 'flex', flexWrap: 'wrap', gap: '12px', marginTop: '8px' }}>
                  {places.length === 0 ? (
                    <span style={{ color: '#888' }}>No hay lugares disponibles.</span>
                  ) : (
                    places.map((place) => (
                      <label key={place.id} className="place-checkbox-label" style={{ display: 'flex', alignItems: 'center', minWidth: '220px', background: '#f8f8f8', borderRadius: '6px', padding: '6px 10px', boxShadow: '0 1px 2px #0001' }}>
                        <input
                          type="checkbox"
                          name="places"
                          value={place.id}
                          checked={formData.places?.includes(place.id) || false}
                          onChange={e => {
                            const checked = e.target.checked;
                            setFormData(prev => {
                              const current = prev.places || [];
                              return {
                                ...prev,
                                places: checked
                                  ? [...current, place.id]
                                  : current.filter(id => id !== place.id)
                              };
                            });
                          }}
                          style={{ marginRight: '8px' }}
                        />
                        {place.image || place.imagen ? (
                          <img
                            src={place.image || place.imagen}
                            alt={place.name}
                            style={{ width: 32, height: 32, objectFit: 'cover', borderRadius: '4px', marginRight: '10px', background: '#eee' }}
                            onError={e => { e.target.src = '/imagenes/placeholder.jpg'; }}
                          />
                        ) : (
                          <span style={{ width: 32, height: 32, display: 'inline-block', background: '#eee', borderRadius: '4px', marginRight: '10px' }} />
                        )}
                        <span style={{ fontWeight: 500, color: '#222', fontSize: '1rem' }}>{place.name}</span>
                      </label>
                    ))
                  )}
                </div>
                <small style={{ color: '#666', fontSize: '0.85rem', marginTop: '5px', display: 'block' }}>
                  Puedes asociar uno o varios lugares a este ecohotel.
                </small>
              </div>

              {/* BOTONES DE ACCIÓN */}
              <div className="modal-actions">
                <button type="button" className="btn-secondary" onClick={closeModals}>
                  Cancelar
                </button>
                <button type="submit" className="btn-primary">
                  Crear Ecohotel
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Modal Editar */}
      {editingEcohotel && (
        <div className="modal-overlay" onClick={(e) => { e.stopPropagation(); closeModals(); }}>
          <div className="modal-content" onClick={(e) => e.stopPropagation()}>
            <div className="modal-header">
              <h3>Editar Ecohotel</h3>
              <button type="button" className="close-btn" onClick={closeModals}>×</button>
            </div>
            {message && (
              <div className={`message ${message.toLowerCase().includes('error') ? 'error' : 'success'}`} style={{marginBottom: '1rem'}}>
                {message}
                <button className="close-btn" onClick={() => setMessage('')}>×</button>
              </div>
            )}
            <form onSubmit={handleEdit} className="modal-form">
              
              {/* SECCIÓN: Información Básica */}
              <div className="form-section">
                <div className="form-section-title">
                  <div className="form-section-icon">1</div>
                  Información Básica
                </div>
                <div className="form-group">
                  <label htmlFor="edit-name" className="required">Nombre</label>
                  <input
                    type="text"
                    id="edit-name"
                    name="name"
                    value={formData.name}
                    onChange={handleInputChange}
                    required
                  />
                </div>

                <div className="form-group">
                  <label htmlFor="edit-description">Descripción</label>
                  <textarea
                    id="edit-description"
                    name="description"
                    value={formData.description}
                    onChange={handleInputChange}
                    rows="4"
                  />
                </div>
              </div>

              {/* SECCIÓN: Ubicación */}
              <div className="form-section">
                <div className="form-section-title">
                  <div className="form-section-icon">2</div>
                  Ubicación
                </div>
                <div className="form-group">
                  <label htmlFor="edit-location">Ubicación</label>
                  <input
                    type="text"
                    id="edit-location"
                    name="location"
                    value={formData.location}
                    onChange={handleInputChange}
                  />
                </div>

                <div className="form-row">
                  <div className="form-group">
                    <label htmlFor="edit-latitude">Latitud</label>
                    <input
                      type="number"
                      id="edit-latitude"
                      name="latitude"
                      value={formData.latitude}
                      onChange={handleInputChange}
                      step="any"
                    />
                  </div>
                  <div className="form-group">
                    <label htmlFor="edit-longitude">Longitud</label>
                    <input
                      type="number"
                      id="edit-longitude"
                      name="longitude"
                      value={formData.longitude}
                      onChange={handleInputChange}
                      step="any"
                    />
                  </div>
                </div>
              </div>

              {/* SECCIÓN: Contacto */}
              <div className="form-section">
                <div className="form-section-title">
                  <div className="form-section-icon">3</div>
                  Información de Contacto
                </div>
                <div className="form-group">
                  <label htmlFor="edit-telefono">Teléfono</label>
                  <input
                    type="text"
                    id="edit-telefono"
                    name="telefono"
                    value={formData.telefono}
                    onChange={handleInputChange}
                  />
                </div>

                <div className="form-group">
                  <label htmlFor="edit-email">Email</label>
                  <input
                    type="email"
                    id="edit-email"
                    name="email"
                    value={formData.email}
                    onChange={handleInputChange}
                  />
                </div>

                <div className="form-group">
                  <label htmlFor="edit-sitio_web">Sitio Web</label>
                  <input
                    type="url"
                    id="edit-sitio_web"
                    name="sitio_web"
                    value={formData.sitio_web}
                    onChange={handleInputChange}
                    placeholder="https://ejemplo.com"
                  />
                </div>
              </div>

              {/* SECCIÓN: Imagen */}
              <div className="form-section">
                <div className="form-section-title">
                  <div className="form-section-icon">4</div>
                  Imagen Representativa
                </div>
                
                {imagePreview ? (
                  <div className="image-preview">
                    <label style={{ display: 'block', marginBottom: '0.75rem', color: '#2c5f2d', fontWeight: '600', fontSize: '0.9rem' }}>
                      Vista Previa de la Nueva Imagen
                    </label>
                    <img src={imagePreview} alt="Vista previa de nueva imagen" />
                  </div>
                ) : editingEcohotel.image ? (
                  <div className="image-preview">
                    <label style={{ display: 'block', marginBottom: '0.75rem', color: '#666', fontWeight: '600', fontSize: '0.9rem' }}>
                      Imagen Actual Registrada
                    </label>
                    <img
                      src={editingEcohotel.image}
                      alt={editingEcohotel.name}
                      onError={e => { e.target.src = '/imagenes/placeholder.jpg'; }}
                    />
                  </div>
                ) : null}

                <div className="form-group">
                  <label htmlFor="edit-image">Subir o Cambiar Imagen (Máx 5MB)</label>
                  <input
                    type="file"
                    id="edit-image"
                    name="image"
                    onChange={handleImageChange}
                    accept="image/*"
                  />
                </div>
              </div>

              {/* SECCIÓN: Categorías */}
              <div className="form-section">
                <div className="form-section-title">
                  <div className="form-section-icon">5</div>
                  Categorías
                </div>
                <label style={{ marginBottom: '1rem', display: 'block', color: '#666', fontSize: '0.9rem' }}>
                  Selecciona las categorías que mejor describen este ecohotel
                </label>
                <div className="categories-checkbox">
                  {categories.map((category) => (
                    <label key={category.id} className="checkbox-label">
                      <input
                        type="checkbox"
                        checked={formData.categories.includes(category.id)}
                        onChange={() => handleCategoryChange(category.id)}
                      />
                      {category.name}
                    </label>
                  ))}
                </div>
              </div>

              {/* SECCIÓN: Lugares relacionados */}
              <div className="form-section">
                <div className="form-section-title">
                  <div className="form-section-icon">6</div>
                  Lugares relacionados
                </div>
                <div className="places-checkboxes" style={{ display: 'flex', flexWrap: 'wrap', gap: '12px', marginTop: '8px' }}>
                  {places.length === 0 ? (
                    <span style={{ color: '#888' }}>No hay lugares disponibles.</span>
                  ) : (
                    places.map((place) => (
                      <label key={place.id} className="place-checkbox-label" style={{ display: 'flex', alignItems: 'center', minWidth: '220px', background: '#f8f8f8', borderRadius: '6px', padding: '6px 10px', boxShadow: '0 1px 2px #0001' }}>
                        <input
                          type="checkbox"
                          name="places"
                          value={place.id}
                          checked={formData.places?.includes(place.id) || false}
                          onChange={e => {
                            const checked = e.target.checked;
                            setFormData(prev => {
                              const current = prev.places || [];
                              return {
                                ...prev,
                                places: checked
                                  ? [...current, place.id]
                                  : current.filter(id => id !== place.id)
                              };
                            });
                          }}
                          style={{ marginRight: '8px' }}
                        />
                        {place.image || place.imagen ? (
                          <img
                            src={place.image || place.imagen}
                            alt={place.name}
                            style={{ width: 32, height: 32, objectFit: 'cover', borderRadius: '4px', marginRight: '10px', background: '#eee' }}
                            onError={e => { e.target.src = '/imagenes/placeholder.jpg'; }}
                          />
                        ) : (
                          <span style={{ width: 32, height: 32, display: 'inline-block', background: '#eee', borderRadius: '4px', marginRight: '10px' }} />
                        )}
                        <span style={{ fontWeight: 500, color: '#222', fontSize: '1rem' }}>{place.name}</span>
                      </label>
                    ))
                  )}
                </div>
                <small style={{ color: '#666', fontSize: '0.85rem', marginTop: '5px', display: 'block' }}>
                  Puedes asociar uno o varios lugares a este ecohotel.
                </small>
              </div>

              {/* BOTONES DE ACCIÓN */}
              <div className="modal-actions">
                <button type="button" className="btn-secondary" onClick={closeModals}>
                  Cancelar
                </button>
                <button type="submit" className="btn-primary">
                  ✓ Actualizar
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Modal Ver Detalles */}
      {viewingEcohotel && (
        <div className="modal-overlay" onClick={(e) => { e.stopPropagation(); closeModals(); }}>
          <div className="modal-content modal-view" onClick={(e) => e.stopPropagation()}>
            <div className="modal-header">
              <h3>Detalles del Ecohotel</h3>
              <button type="button" className="close-btn" onClick={closeModals}>×</button>
            </div>
            <div className="modal-body">
              {viewingEcohotel.image && (
                <img
                  src={viewingEcohotel.image}
                  alt={viewingEcohotel.name}
                  className="modal-view .detail-image"
                />
              )}
              <div className="detail-item">
                <strong>Nombre</strong>
                <p>{viewingEcohotel.name}</p>
              </div>
              <div className="detail-item">
                <strong>Ubicación</strong>
                <p>{viewingEcohotel.location || 'No especificada'}</p>
              </div>
              <div className="detail-item">
                <strong>Descripción</strong>
                <p>{viewingEcohotel.description || 'No disponible'}</p>
              </div>
              <div className="detail-item">
                <strong>Teléfono</strong>
                <p>{viewingEcohotel.telefono || 'No disponible'}</p>
              </div>
              <div className="detail-item">
                <strong>Email</strong>
                <p>{viewingEcohotel.email || 'No disponible'}</p>
              </div>
              <div className="detail-item">
                <strong>Sitio Web</strong>
                {viewingEcohotel.sitio_web ? (
                  <a href={viewingEcohotel.sitio_web} target="_blank" rel="noopener noreferrer">
                    {viewingEcohotel.sitio_web}
                  </a>
                ) : (
                  <p>No disponible</p>
                )}
              </div>
              <div className="detail-item">
                <strong>Coordenadas</strong>
                <p>
                  {viewingEcohotel.latitude && viewingEcohotel.longitude
                    ? `${viewingEcohotel.latitude}, ${viewingEcohotel.longitude}`
                    : 'No especificadas'}
                </p>
              </div>
              <div className="detail-item">
                <strong>Categorías</strong>
                {viewingEcohotel.categories && viewingEcohotel.categories.length > 0 ? (
                  <div style={{ display: 'flex', flexWrap: 'wrap', gap: '0.5rem', marginTop: '0.5rem' }}>
                    {viewingEcohotel.categories.map((cat) => (
                      <span
                        key={cat.id}
                        style={{
                          display: 'inline-block',
                          padding: '0.4rem 0.8rem',
                          background: '#e8f5e9',
                          color: '#2c5f2d',
                          borderRadius: '20px',
                          fontSize: '0.85rem',
                          fontWeight: '500',
                          border: '1px solid #c8e6c9'
                        }}
                      >
                        {cat.name}
                      </span>
                    ))}
                  </div>
                ) : (
                  <p>Sin categorías</p>
                )}
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default EcohotelsAdmin;
