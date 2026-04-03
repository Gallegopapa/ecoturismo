import React, { useState } from 'react';

const RejectReservationModal = ({ 
  isOpen, 
  onClose, 
  onReject, 
  loading = false,
  rejectionReasons = []
}) => {
  const [formData, setFormData] = useState({
    rejection_reason_id: '',
    comentario: '',
  });

  const [errors, setErrors] = useState({});

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
    if (errors[name]) {
      setErrors(prev => {
        const newErrors = { ...prev };
        delete newErrors[name];
        return newErrors;
      });
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});

    // Validar
    const newErrors = {};
    if (!formData.rejection_reason_id) {
      newErrors.rejection_reason_id = 'Debe seleccionar una razón';
    }

    if (Object.keys(newErrors).length > 0) {
      setErrors(newErrors);
      return;
    }

    await onReject(formData);

    // Limpiar form
    setFormData({
      rejection_reason_id: '',
      comentario: '',
    });
  };

  if (!isOpen) return null;

  return (
    <div className="modal-overlay" onClick={onClose}>
      <div className="modal-content" onClick={e => e.stopPropagation()}>
        <div className="modal-header">
          <h2>Rechazar Reserva</h2>
          <button className="modal-close" onClick={onClose}>&times;</button>
        </div>

        <form onSubmit={handleSubmit} className="reject-form">
          {/* Razón de Rechazo */}
          <div className="form-group">
            <label htmlFor="rejection_reason_id">Razón de Rechazo *</label>
            <select
              id="rejection_reason_id"
              name="rejection_reason_id"
              value={formData.rejection_reason_id}
              onChange={handleInputChange}
              className={errors.rejection_reason_id ? 'input-error' : ''}
              disabled={loading}
            >
              <option value="">-- Selecciona una razón --</option>
              {rejectionReasons.map(reason => (
                <option key={reason.id} value={reason.id}>
                  {reason.descripcion}
                </option>
              ))}
            </select>
            {errors.rejection_reason_id && (
              <small className="error-text">{errors.rejection_reason_id}</small>
            )}
          </div>

          {/* Mostrar detalles de la razón seleccionada */}
          {formData.rejection_reason_id && (
            <div className="reason-details">
              {rejectionReasons.find(r => r.id == formData.rejection_reason_id)?.detalles && (
                <p className="reason-description">
                  {rejectionReasons.find(r => r.id == formData.rejection_reason_id)?.detalles}
                </p>
              )}
            </div>
          )}

          {/* Comentario Adicional */}
          <div className="form-group">
            <label htmlFor="comentario">Comentario Adicional (opcional)</label>
            <textarea
              id="comentario"
              name="comentario"
              value={formData.comentario}
              onChange={handleInputChange}
              placeholder="Proporciona más detalles sobre el motivo del rechazo..."
              rows="4"
              disabled={loading}
              maxLength="500"
            />
            <small>{formData.comentario.length}/500</small>
          </div>

          {/* Botones */}
          <div className="modal-actions">
            <button
              type="submit"
              className="btn btn-danger"
              disabled={loading}
            >
              {loading ? 'Rechazando...' : 'Rechazar Reserva'}
            </button>
            <button
              type="button"
              className="btn btn-secondary"
              onClick={onClose}
              disabled={loading}
            >
              Cancelar
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default RejectReservationModal;
