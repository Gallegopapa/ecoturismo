import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { authService } from "../services/api";
import "../login/page.css";

export default function ForgotPasswordPage() {
  const navigate = useNavigate();
  const [email, setEmail] = useState("");
  const [errors, setErrors] = useState({});
  const [msg, setMsg] = useState("");
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});
    setMsg("");
    setLoading(true);

    try {
      const response = await authService.forgotPassword({ email });
      setMsg(response.message || "Te enviamos un enlace para restablecer la contrasena.");
      setTimeout(() => {
        navigate("/forgot-password/sent", { replace: true, state: { email } });
      }, 400);
    } catch (error) {
      const data = error.response?.data;
      const status = error.response?.status;
      // Mostrar el mensaje específico del servidor
      const errorMessage =
        data?.message ||
        (status === 503
          ? "No se pudo conectar al servidor de correo. Intenta más tarde."
          : "No se pudo enviar el correo.");
      setMsg(errorMessage);
      if (data?.errors) {
        setErrors(data.errors);
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="login-page-container">
      <video id="bg-video" autoPlay loop muted>
        <source src="/imagenes/Videofondo4.mp4" type="video/mp4" />
      </video>
      <div className="login-form-section">
        <form className="login-card" onSubmit={handleSubmit}>
          <h2>Recuperar contraseña</h2>
          <label>Correo electrónico:</label>
          <input
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
            placeholder="tu@email.com"
          />
          {errors.email && (
            <p className="error">
              {Array.isArray(errors.email) ? errors.email[0] : errors.email}
            </p>
          )}
          {msg && (
            <p
              className={`message ${
                msg.includes("No se pudo") || msg.includes("Error") ? "error" : "success"
              }`}
            >
              {msg}
            </p>
          )}
          <button type="submit" disabled={loading}>
            {loading ? "Enviando..." : "Enviar enlace"}
          </button>
          <p className="register-text">
            ¿Ya tienes cuenta? <Link to="/login">Inicia sesión</Link>
          </p>
        </form>
      </div>
      <div className="login-image-section">
        <div className="login-image-content">
          {/* <img src="/imagenes/login-image.png" alt="EcoTurismo" /> */}
          <h2>Recupera tu acceso</h2>
          <p>Ingresa tu correo para recibir el enlace de recuperación.</p>
        </div>
      </div>
      <footer className="footer">© 2025 Risaralda EcoTurismo</footer>
    </div>
  );
}
