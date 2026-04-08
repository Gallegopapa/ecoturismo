import React, { useState } from "react";
import { Link } from "react-router-dom";
import { useAuth } from "@/react/context/AuthContext";
import Header from "@/react/components/Header/Header";
import Header2 from "@/react/components/Header2/Header2";
import Footer from "@/react/components/Footer/Footer";
import { contactsService } from "@/react/services/api";
import "./contacto.css";

// 🔹 Importación correcta de imágenes
import locationIcon from "@/assets/maps-and-location.png";
import mailIcon from "@/assets/correo-electronico.png";
import phoneIcon from "@/assets/telefono.png";
import fbIcon from "@/assets/iconofb.png";
import wpIcon from "@/assets/iconowp.png";
import igIcon from "@/assets/iconoig.png";

export default function Contact() {
  const { isAuthenticated } = useAuth();

  const [formData, setFormData] = useState({
    name: "",
    email: "",
    phone: "",
    message: "",
  });

  const [errors, setErrors] = useState({});
  const [successMsg, setSuccessMsg] = useState("");
  const [loading, setLoading] = useState(false);
  const [messageError, setMessageError] = useState("");

  const handleChange = (e) => {
    const { name, value } = e.target;

    // Validación de mensaje con limite de 500 caracteres
    if (name === "message") {
      if (value.length > 500) {
        setMessageError("El mensaje no debe tener más de 500 caracteres.");
      } else {
        setMessageError("");
      }
    }

    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }));

    if (errors[name]) {
      setErrors((prev) => ({
        ...prev,
        [name]: "",
      }));
    }
  };

  const validateForm = () => {
    const newErrors = {};

    if (!formData.name.trim()) {
      newErrors.name = "El nombre es requerido";
    }

    if (!formData.email.trim()) {
      newErrors.email = "El correo es requerido";
    } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
      newErrors.email = "El correo no es válido";
    }

    if (!formData.phone.trim()) {
      newErrors.phone = "El teléfono es requerido";
    } else if (!/^\d{7,10}$/.test(formData.phone)) {
      newErrors.phone = "El teléfono debe tener entre 7 y 10 dígitos";
    }

    if (!formData.message.trim()) {
      newErrors.message = "El mensaje es requerido";
    } else if (formData.message.length > 500) {
      newErrors.message = "El mensaje no debe tener más de 500 caracteres";
    }

    return newErrors;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});
    setSuccessMsg("");
    setLoading(true);

    const validationErrors = validateForm();

    if (Object.keys(validationErrors).length > 0) {
      setErrors(validationErrors);
      setLoading(false);
      return;
    }

    try {
      // Enviar el mensaje al backend
      const response = await contactsService.send({
        name: formData.name,
        email: formData.email,
        phone: formData.phone,
        message: formData.message,
      });

      // Mensaje enviado exitosamente
      setSuccessMsg(response.message || "¡Mensaje enviado exitosamente!");

      // Limpiar el formulario
      setFormData({
        name: "",
        email: "",
        phone: "",
        message: "",
      });

      // Ocultar el mensaje después de 5 segundos
      setTimeout(() => setSuccessMsg(""), 5000);
    } catch (error) {
      console.error("Error al enviar mensaje:", error);
      
      // Manejar errores de validación del servidor
      if (error.response?.data?.errors) {
        setErrors(error.response.data.errors);
      } else {
        setErrors({
          general: error.response?.data?.message || "Error al enviar el mensaje. Por favor, intenta nuevamente."
        });
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      {isAuthenticated ? <Header2 /> : <Header />}

      <div className="contact-page-container">
        <div className="form">

          {/* ------- COLUMNA IZQUIERDA ------- */}
          <div className="contact-info">
            <h3 className="tittle">Pongámonos en contacto</h3>
            <p className="text">
              Escríbenos y te buscamos la mejor opción para tu página
            </p>

            <div className="info">
              <div className="information">
                <img src={locationIcon} className="icon" alt="" />
                <p>Dosquebradas-Pereira</p>
              </div>

              <div className="information">
                <img src={mailIcon} className="icon" alt="" />
                <a href="mailto:proyectoecoturismo2@gmail.com">
                  <p>proyectoecoturismo2@gmail.com</p>
                </a>
              </div>

              <div className="information">
                <img src={phoneIcon} className="icon" alt="" />
                <a href="tel:3134152020">
                  <p>3134152020</p>
                </a>
              </div>

              <div className="information copyright">
                <p>© 2025 RisaraldaEcoTurismo</p>
              </div>
            </div>

            {/* Redes sociales */}
            <div className="social-media">
              <p>Conéctate con nosotros:</p>
              <div className="social-icon">
                <a href="https://www.facebook.com" target="_blank" rel="noreferrer">
                  <img src={fbIcon} width="30" alt="Facebook" />
                </a>

                <a href="https://www.whatsapp.com" target="_blank" rel="noreferrer">
                  <img src={wpIcon} width="30" alt="WhatsApp" />
                </a>

                <a href="https://www.instagram.com" target="_blank" rel="noreferrer">
                  <img src={igIcon} width="30" alt="Instagram" />
                </a>
              </div>
            </div>
          </div>

          {/* ------- COLUMNA DERECHA ------- */}
          <div className="contact-form">
            <form onSubmit={handleSubmit}>
              <h3 className="tittle">Contáctanos</h3>

              <div className="input-container focus">
                <input
                  type="text"
                  name="name"
                  className={`input ${errors.name ? 'input-error' : ''}`}
                  value={formData.name}
                  onChange={handleChange}
                />
                <label>Nombre de usuario</label>
                <span>Nombre de usuario</span>
              </div>
              {errors.name && <p className="field-error">{errors.name}</p>}

              <div className="input-container focus">
                <input
                  type="email"
                  name="email"
                  className={`input ${errors.email ? 'input-error' : ''}`}
                  value={formData.email}
                  onChange={handleChange}
                />
                <label>Correo</label>
                <span>Correo</span>
              </div>
              {errors.email && <p className="field-error">{errors.email}</p>}

              <div className="input-container focus">
                <input
                  type="tel"
                  name="phone"
                  className={`input ${errors.phone ? 'input-error' : ''}`}
                  value={formData.phone}
                  onChange={handleChange}
                />
                <label>Teléfono</label>
                <span>Teléfono</span>
              </div>
              {errors.phone && <p className="field-error">{errors.phone}</p>}

              <div className="input-container textarea focus">
                <textarea
                  name="message"
                  className={`input ${errors.message ? 'input-error' : ''}`}
                  value={formData.message}
                  onChange={handleChange}
                ></textarea>
                <label>Mensaje</label>
                <span>Mensaje</span>
              </div>
              {errors.message && <p className="field-error">{errors.message}</p>}

              <div className={`char-counter ${formData.message.length > 500 ? 'error' : ''}`}>
                {formData.message.length}/500
              </div>

              {messageError && <p className="error-message">{messageError}</p>}

              <div className="button-container">
                <input 
                  type="submit" 
                  value={loading ? "Enviando..." : "Enviar"} 
                  className="btn" 
                  disabled={loading}
                />
              </div>

              {successMsg && (
                <p style={{ color: "#2563eb", marginTop: "10px", fontWeight: "bold", backgroundColor: "#dbeafe", padding: "12px", borderRadius: "6px", border: "1px solid #93c5fd" }}>
                  ✓ {successMsg}
                </p>
              )}

              {errors.general && (
                <p style={{ color: "#ef4444", marginTop: "10px" }}>
                  {errors.general}
                </p>
              )}
            </form>
          </div>
        </div>
      </div>

      <Footer />
    </>
  );
}
