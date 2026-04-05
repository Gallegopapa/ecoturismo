import { useState, useEffect, useRef } from "react";
import { useLocation, Link, useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import "./page.css";

export default function Login() {
  const location = useLocation();
  const navigate = useNavigate();
  const { login, register, isAuthenticated } = useAuth();
  const isRegister = location.pathname === "/registro";
  const emailInputRef = useRef(null);

  const [username, setUsername] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [passwordConfirmation, setPasswordConfirmation] = useState("");
  const [showPassword, setShowPassword] = useState(false);
  const [showPasswordConfirmation, setShowPasswordConfirmation] = useState(false);
  const [errors, setErrors] = useState({});
  const [msg, setMsg] = useState("");
  const [loading, setLoading] = useState(false);

  const syncEmailFromAutofill = () => {
    if (emailInputRef.current && emailInputRef.current.value && !email) {
      setEmail(emailInputRef.current.value);
    }
  };

  useEffect(() => {
    const t = setTimeout(syncEmailFromAutofill, 100);
    return () => clearTimeout(t);
  }, []);

  useEffect(() => {
    if (!isRegister) {
      const t = setTimeout(syncEmailFromAutofill, 300);
      return () => clearTimeout(t);
    }
  }, [isRegister]);

  useEffect(() => {
    if (isAuthenticated) {
      navigate("/pagLogueados", { replace: true });
    }
  }, [isAuthenticated, navigate]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});
    setMsg("");
    setLoading(true);

    // VALIDACIÓN FRONTEND PARA GMAIL
    if (isRegister && !email.toLowerCase().endsWith("@gmail.com")) {
      setErrors({ email: ["Solo se permiten correos @gmail.com"] });
      setMsg("Solo se permiten correos con dominio @gmail.com");
      setLoading(false);
      return;
    }

    try {
      let result;

      if (isRegister) {
        result = await register({
          name: username,
          email,
          password,
          password_confirmation: passwordConfirmation,
        });
      } else {
        const loginValue = email || (emailInputRef.current?.value ?? "");
        result = await login({
          login: loginValue,
          password,
        });
      }

      if (result.success) {
        setMsg("¡Inicio de sesión exitoso! Redirigiendo...");
        setTimeout(() => {
          navigate("/pagLogueados", { replace: true });
        }, 500);
      } else {
        setMsg(result.error || "Error al procesar la solicitud");
        if (result.errors) {
          setErrors(result.errors);
        }
        setLoading(false);
      }

    } catch (error) {

      if (error.response && error.response.status === 422) {
        const backendErrors = error.response.data.errors || {};
        setErrors(backendErrors);

        if (backendErrors.email) {
          setMsg(backendErrors.email[0]);
        }

      } else {
        setMsg("Error inesperado. Por favor, intenta de nuevo.");
      }

      setLoading(false);
    }
  };

  return (
    <div className={`login-page-container ${isRegister ? 'register-layout' : 'login-layout'}`}> 

      <header className="header">
        <h1> Risaralda EcoTurismo</h1>
      </header>

      <div className="login-form-section">
        <form className="login-card" onSubmit={handleSubmit}>
          <h2>{isRegister ? "Registro" : "Iniciar Sesión"}</h2>

          {isRegister ? (
            <>
              <label>Nombre de usuario:</label>
              <input
                type="text"
                value={username}
                onChange={(e) => setUsername(e.target.value)}
                required
                placeholder="Tu nombre"
              />
              {errors.name && <p className="error">{errors.name[0]}</p>}

              <label>Email:</label>
              <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
                placeholder="tu@gmail.com"
              />
              {errors.email && <p className="error">{errors.email[0]}</p>}
            </>
          ) : (
            <>
              <label>Correo o usuario:</label>
              <input
                ref={emailInputRef}
                type="text"
                name="login"
                autoComplete="username"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                onBlur={syncEmailFromAutofill}
                required
                placeholder="Email o usuario"
              />
              {(errors.login || errors.email || errors.credentials) && (
                <p className="error">
                  {errors.login?.[0] || errors.email?.[0] || errors.credentials?.[0]}
                </p>
              )}
            </>
          )}

          <label>Contraseña:</label>
          <div className="password-container">
            <input
              type={showPassword ? "text" : "password"}
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              minLength={isRegister ? 8 : 1}
              maxLength={isRegister ? 15 : undefined}
              placeholder="Tu contraseña"
            />
            <button
              type="button"
              className="password-toggle"
              onClick={() => setShowPassword(!showPassword)}
            >
              {showPassword ? "👁️" : "👁️‍🗨️"}
            </button>
          </div>
          {errors.password && <p className="error">{errors.password[0]}</p>}

          {isRegister && (
            <>
              <label>Confirmar contraseña:</label>
              <div className="password-container">
                <input
                  type={showPasswordConfirmation ? "text" : "password"}
                  value={passwordConfirmation}
                  onChange={(e) => setPasswordConfirmation(e.target.value)}
                  required
                  minLength={8}
                  maxLength={15}
                  placeholder="Confirma contraseña"
                />
                <button
                  type="button"
                  className="password-toggle"
                  onClick={() => setShowPasswordConfirmation(!showPasswordConfirmation)}
                >
                  {showPasswordConfirmation ? "👁️" : "👁️‍🗨️"}
                </button>
              </div>
              {errors.password_confirmation && <p className="error">{errors.password_confirmation[0]}</p>}
            </>
          )}

          {!isRegister && (
            <p className="register-text">
              ¿Todavía no tienes cuenta? <Link to="/registro">Regístrate</Link>
            </p>
          )}

          {isRegister && (
            <p className="register-text">
              ¿Ya tienes cuenta? <Link to="/login">Inicia sesión</Link>
            </p>
          )}

          {!isRegister && (
            <p className="register-text">
              ¿Has olvidado tu contraseña? <Link to="/forgot-password">Recupérala</Link>
            </p>
          )}

          {msg && (
            <p className={`message ${msg.includes("Error") || msg.includes("inválido") ? "error" : "success"}`}>
              {msg}
            </p>
          )}

          <button type="submit" disabled={loading}>
            {loading ? "Procesando..." : isRegister ? "Registrarse" : "Iniciar sesión"}
          </button>
        </form>
      </div>

      <div className="login-image-section">
        <div className="login-image-content">
          <h2>{isRegister ? "Únete a Nosotros" : "Explora la Naturaleza"}</h2>
          <p>
            {isRegister
              ? "Acceso a reservas exclusivas, recomendaciones personalizadas y una comunidad de viajeros."
              : "Descubre los destinos ecológicos más hermosos de Risaralda."}
          </p>
        </div>
      </div>

      <footer className="footer">© 2025 Risaralda EcoTurismo</footer>

    </div>
  );
}