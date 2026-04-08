import { Link, useLocation } from "react-router-dom";
import "../login/page.css";

export default function ForgotPasswordSentPage() {
    const location = useLocation();
    const email = location.state?.email;

    return (
        <div className="login-page-container login-layout">
            {/* <video id="bg-video" autoPlay loop muted>
                <source src="/imagenes/Videofondo4.mp4" type="video/mp4" />
            </video> */}

            <header className="header">
                <h1>🌿 Risaralda EcoTurismo</h1>
            </header>

            <div className="login-form-section">
                <div className="login-card">
                    <h2>Correo enviado</h2>
                    <p className="message success">
                        Enviamos el enlace de recuperación
                        {email ? ` a ${email}` : ""}. Revisa tu bandeja y spam.
                    </p>
                    <p className="register-text">
                        ¿Quieres intentar con otro correo?{" "}
                        <Link to="/forgot-password">Volver</Link>
                    </p>
                    <p className="register-text">
                        ¿Ya tienes cuenta?{" "}
                        <Link to="/login">Inicia sesión</Link>
                    </p>
                </div>
            </div>

            <div className="login-image-section">
                <div className="login-image-content">
                    <h2>¡Casi listo!</h2>
                    <p>
                        Revisa tu correo y sigue las instrucciones para
                        restablecer tu contraseña. Si no llega, recuerda revisar
                        la carpeta de spam.
                    </p>
                </div>
            </div>

            <footer className="footer">© 2025 Risaralda EcoTurismo</footer>
        </div>
    );
}
