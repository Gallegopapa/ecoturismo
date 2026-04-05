import React, { useEffect, useRef, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "@/react/context/AuthContext";
import Header2 from "@/react/components/Header2/Header2";
import Footer from "@/react/components/Footer/Footer";
import { profileService } from "@/react/services/api";
import usuarioImg from "@/react/components/imagenes/usuario.jpg";
import "./page.css";

const PerfilPage = () => {
  const navigate = useNavigate();
  const { user, isAuthenticated, loading, logout, updateUser } = useAuth();

  // Log inicial de montaje
  console.log('🟢 Componente PerfilPage montado');

  const fileInputRef = useRef(null);
  const hasHydratedProfileRef = useRef(false);
  const selectedProfileFileRef = useRef(null);

  const [formData, setFormData] = useState({
    name: "",
    email: "",
    telefono: "",
    foto_perfil: null,
  });
  const [previewImage, setPreviewImage] = useState(null);
  const [message, setMessage] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [showDeleteConfirm, setShowDeleteConfirm] = useState(false);
  const [deleteMessage, setDeleteMessage] = useState("");

  const appendCacheBuster = (url) => {
    const separator = url.includes('?') ? '&' : '?';
    return `${url}${separator}t=${Date.now()}`;
  };

  const extractFilename = (value) => {
    if (!value) return null;
    const normalized = String(value).replace(/\\/g, '/');
    const pathOnly = normalized.split('?')[0];
    const parts = pathOnly.split('/').filter(Boolean);
    return parts.length ? parts[parts.length - 1] : null;
  };

  const resolveProfileImageUrl = (rawUrl) => {
    if (!rawUrl || rawUrl === "null" || rawUrl === "undefined") {
      return usuarioImg;
    }
    if (typeof rawUrl === 'string' && rawUrl.startsWith('data:')) {
      return rawUrl;
    }
    if (/^https?:\/\//i.test(rawUrl) || rawUrl.startsWith('/')) {
      return rawUrl;
    }
    // Si viene solo el nombre
    const normalized = rawUrl.replace(/\\/g, '/');
    const fileName = normalized.split('/').filter(Boolean).pop();
    if (!fileName) return usuarioImg;
    return `/api/profile/photo/${encodeURIComponent(fileName)}`;
  };

  const handleDeleteAccount = async () => {
    setDeleteMessage("");
    try {
      await profileService.deleteAccount();
      setDeleteMessage("Cuenta eliminada exitosamente. Redirigiendo...");
      setTimeout(() => {
        logout();
        window.location.href = "/";
      }, 2000);
    } catch (error) {
      setDeleteMessage(error.response?.data?.message || error.message || "Error al eliminar la cuenta");
    }
  };

  // Validar que el usuario esté autenticado y cargar datos del perfil
  useEffect(() => {
    if (!loading && !isAuthenticated) {
      navigate("/login", { replace: true });
      return;
    }

    if (loading || !isAuthenticated || hasHydratedProfileRef.current) {
      return;
    }

    const hydrateFromUser = (profileUser) => {
      setFormData({
        name: profileUser.name || "",
        email: profileUser.email || "",
        telefono: profileUser.telefono || "",
        foto_perfil: null,
      });
      hasHydratedProfileRef.current = true;
    };

    // Cargar una sola vez para no pisar la imagen seleccionada por el usuario.
    const loadProfileData = async () => {
      if (user?.id) {
        hydrateFromUser(user);
        return;
      }

      try {
        const response = await profileService.get();
        if (response.user) {
          updateUser(response.user);
          hydrateFromUser(response.user);
        }
      } catch (error) {
        console.error("Error al cargar perfil:", error);
      }
    };

    loadProfileData();
  }, [user?.id, isAuthenticated, loading, navigate, updateUser]);

  // Mostrar loading
  if (loading) {
    return (
      <div style={{
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
        height: '100vh'
      }}>
        <p>Cargando...</p>
      </div>
    );
  }

  // Si no hay usuario autenticado, no renderizar
  if (!isAuthenticated || !user) {
    return null;
  }

  const handleChange = (e) => {
    console.log('🔄 handleChange ejecutado', {
      inputName: e.target.name,
      inputType: e.target.type,
      hasFiles: !!e.target.files,
      filesLength: e.target.files?.length || 0,
    });

    const { name, value, files } = e.target;

    if (name === 'foto_perfil' && files && files[0]) {
      const file = files[0];
      selectedProfileFileRef.current = file;

      console.log('✅ Archivo detectado:', {
        name: file.name,
        size: file.size,
        type: file.type,
      });

      // Actualizar formData
      setFormData((prev) => {
        const updated = { ...prev, foto_perfil: file };
        console.log('📝 formData actualizado:', {
          name: updated.name,
          email: updated.email,
          telefono: updated.telefono,
          hasFile: updated.foto_perfil instanceof File,
        });
        return updated;
      });

      // Crear preview local
      const reader = new FileReader();
      reader.onloadend = () => {
        console.log('✅ FileReader terminó, generando preview');
        setPreviewImage(reader.result);
      };
      reader.onerror = () => {
        console.error('❌ Error en FileReader');
      };
      reader.readAsDataURL(file);
    } else {
      console.log('📝 Cambio de campo texto:', name, '=', value);
      setFormData((prev) => ({
        ...prev,
        [name]: value,
      }));
    }
  };

  // Enviar archivo como File para evitar inflar el payload con base64 y reducir 413.
  const handleSubmit = async (e) => {
    e.preventDefault();
    console.log('=== INICIO handleSubmit ===');
    setIsSubmitting(true);
    setMessage("");

    try {
      const selectedProfileFile = formData.foto_perfil instanceof File
        ? formData.foto_perfil
        : selectedProfileFileRef.current;

      const payload = {
        name: formData.name,
        email: formData.email,
        telefono: formData.telefono,
      };

      if (selectedProfileFile instanceof File) {
        console.log('📸 Adjuntando archivo para multipart...', {
          name: selectedProfileFile.name,
          size: selectedProfileFile.size,
          type: selectedProfileFile.type,
        });
        payload.foto_perfil = selectedProfileFile;
      }

      console.log('📤 Enviando payload:', {
        name: payload.name,
        email: payload.email,
        telefono: payload.telefono,
        hasFile: payload.foto_perfil instanceof File,
        fileSize: payload.foto_perfil?.size || 0,
      });

      const response = await profileService.update(payload);
      console.log('✅ Respuesta del servidor:', response);

      setMessage(response.message || "Perfil actualizado exitosamente");

      // Actualizar usuario en contexto
      if (response.user) {
        updateUser(response.user);
      }

      // Evitar parpadeo/desaparición: fijar preview con la URL confirmada por backend.
      if (response?.user?.foto_perfil) {
        const backendImageUrl = resolveProfileImageUrl(response.user.foto_perfil);
        setPreviewImage(appendCacheBuster(backendImageUrl));
      }

      // Limpiar foto
      setFormData(prev => ({
        ...prev,
        foto_perfil: null,
      }));
      selectedProfileFileRef.current = null;

      if (fileInputRef.current) {
        fileInputRef.current.value = '';
      }

      setTimeout(() => setMessage(""), 5000);
    } catch (error) {
      console.error('❌ Error:', error);
      const msg = error.response?.data?.message || error.message || 'Error';
      setMessage(`Error: ${msg}`);
    } finally {
      setIsSubmitting(false);
      console.log('=== FIN handleSubmit ===');
    }
  };

  const handleLogout = async () => {
    try {
      await logout();
      window.location.href = "/";
    } catch (error) {
      console.error("Error al cerrar sesión:", error);
    }
  };

  const resolvedUserPhoto = resolveProfileImageUrl(user?.foto_perfil);
  const avatarSrc = previewImage || resolvedUserPhoto;

  return (
    <>
      <Header2 />

      <div className="perfil-container">
        <div className="perfil-card">
          <h1>Mi Perfil</h1>

          {message && (
            <div className={`message ${message.includes("Error") ? "error" : "success"}`}>
              {message}
            </div>
          )}

          {/* Sección de foto de perfil */}
          <div className="profile-photo-section">
            <div className="photo-preview">
              <img
                src={avatarSrc}
                alt="Foto de perfil"
                className="profile-photo"
                onError={(e) => {
                  const fallback = usuarioImg;
                  if (e.currentTarget.src !== fallback) {
                      e.currentTarget.src = fallback;
                  }
                }}
              />
            </div>
            <div
              className="photo-upload-wrapper"
              style={{ position: 'relative', display: 'inline-block' }}
            >
              <input
                ref={fileInputRef}
                id="foto_perfil"
                type="file"
                name="foto_perfil"
                accept="image/*"
                onChange={handleChange}
                disabled={isSubmitting}
                style={{
                  position: 'absolute',
                  inset: 0,
                  opacity: 0,
                  width: '100%',
                  height: '100%',
                  cursor: isSubmitting ? 'not-allowed' : 'pointer',
                  zIndex: 2,
                }}
              />
              <label
                htmlFor="foto_perfil"
                className="photo-upload-btn"
                style={{ display: 'inline-block', position: 'relative', zIndex: 1 }}
              >
                {formData.foto_perfil ? 'Cambiar Foto' : 'Subir Foto'}
              </label>
            </div>
            {formData.foto_perfil && (
              <p style={{ fontSize: '0.85rem', color: '#666', marginTop: '5px' }}>
                Nueva foto seleccionada: {formData.foto_perfil.name}
              </p>
            )}
          </div>

          <form onSubmit={handleSubmit} className="perfil-form" encType="multipart/form-data">
            <div className="form-group">
              <label htmlFor="name">Nombre:</label>
              <input
                id="name"
                type="text"
                name="name"
                value={formData.name}
                onChange={handleChange}
                required
                disabled={isSubmitting}
              />
            </div>

            <div className="form-group">
              <label htmlFor="email">Correo:</label>
              <input
                id="email"
                type="email"
                name="email"
                value={formData.email}
                onChange={handleChange}
                required
                disabled={isSubmitting}
              />
            </div>

            <div className="form-group">
              <label htmlFor="telefono">Teléfono:</label>
              <input
                id="telefono"
                type="tel"
                name="telefono"
                value={formData.telefono}
                onChange={handleChange}
                disabled={isSubmitting}
              />
            </div>

            <div className="form-buttons" style={{ display: 'flex', gap: '12px', marginBottom: '32px' }}>
              <button
                type="submit"
                disabled={isSubmitting}
                className="btn-guardar"
              >
                {isSubmitting ? "Guardando..." : "Guardar Cambios"}
              </button>

              <button
                type="button"
                onClick={handleLogout}
                className="btn-logout"
              >
                Cerrar Sesión
              </button>
            </div>

            <hr style={{ margin: '32px 0 16px 0', border: 'none', borderTop: '1px solid #eee' }} />
            <div className="danger-zone" style={{ marginBottom: '24px' }}>
              <h3 style={{ fontWeight: 'bold', fontSize: '1.1em', marginBottom: '8px' }}>Zona peligrosa</h3>
              <p style={{ color: '#888', fontSize: '0.95em', marginBottom: '12px' }}>
                Esta acción eliminará tu cuenta y todos tus datos de forma permanente. No se puede deshacer.
              </p>
              <button
                type="button"
                className="btn-delete"
                style={{ background: '#e53935', color: '#fff', width: '100%', maxWidth: '220px', marginTop: '8px', fontWeight: 'bold', fontSize: '1em', padding: '10px 0', borderRadius: '6px', boxShadow: '0 2px 8px rgba(229,57,53,0.08)' }}
                onClick={() => setShowDeleteConfirm(true)}
              >
                Eliminar cuenta
              </button>
            </div>

            {/* Modal de confirmación de eliminación de cuenta */}
            {showDeleteConfirm && (
              <div>
                <div
                  className="modal-overlay"
                  style={{
                    position: 'fixed',
                    top: 0,
                    left: 0,
                    width: '100vw',
                    height: '100vh',
                    background: 'rgba(0,0,0,0.35)',
                    zIndex: 1000,
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    animation: 'fadeIn 0.2s',
                    overflowY: 'auto',
                  }}
                  onClick={() => setShowDeleteConfirm(false)}
                >
                  <div
                    className="modal-content"
                    style={{
                      background: '#fff',
                      borderRadius: '16px',
                      boxShadow: '0 4px 24px rgba(0,0,0,0.12)',
                      padding: '24px 16px',
                      minWidth: '280px',
                      maxWidth: '95vw',
                      width: '100%',
                      zIndex: 1001,
                      position: 'relative',
                      animation: 'scaleIn 0.2s',
                      margin: '0 auto',
                      maxHeight: '90vh',
                      overflowY: 'auto',
                    }}
                    onClick={e => e.stopPropagation()}
                  >
                    <h2 style={{ fontWeight: 'bold', fontSize: '1.25em', marginBottom: '12px', color: '#222' }}>¿Eliminar cuenta?</h2>
                    <p style={{ color: '#888', fontSize: '1em', marginBottom: '24px' }}>
                      Esta acción eliminará permanentemente tu cuenta y todos tus datos. No se puede deshacer.
                    </p>
                    <div style={{ display: 'flex', gap: '12px', marginBottom: '8px' }}>
                      <button
                        type="button"
                        style={{ background: '#e53935', color: '#fff', fontWeight: 'bold', borderRadius: '6px', padding: '10px 24px', fontSize: '1em', boxShadow: '0 2px 8px rgba(229,57,53,0.08)' }}
                        onClick={handleDeleteAccount}
                      >
                        Confirmar eliminación
                      </button>
                      <button
                        type="button"
                        style={{ background: '#eee', color: '#222', borderRadius: '6px', padding: '10px 24px', fontSize: '1em', fontWeight: 'bold' }}
                        onClick={() => setShowDeleteConfirm(false)}
                      >
                        Cancelar
                      </button>
                    </div>
                    {deleteMessage && (
                      <p style={{ marginTop: 10, color: deleteMessage.includes('exitosamente') ? '#388e3c' : '#e53935' }}>{deleteMessage}</p>
                    )}
                  </div>
                </div>
                {/* Animaciones CSS */}
                <style>{`
                  @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                  }
                  @keyframes scaleIn {
                    from { transform: scale(0.95); opacity: 0; }
                    to { transform: scale(1); opacity: 1; }
                  }
                `}</style>
              </div>
            )}
          </form>
        </div>
      </div>

      <Footer />
    </>
  );
};

export default PerfilPage;