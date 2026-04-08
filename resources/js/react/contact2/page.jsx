import React, { useState } from "react";
import { Link } from "react-router-dom";
import Header2 from "@/react/components/Header2/Header2";
import Footer from "@/react/components/Footer/Footer";
import { contactsService } from "@/react/services/api";
import "./page.css";

export default function Contact2() {
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    phone: "",
    message: "",
  });
  const [errors, setErrors] = useState({});
  const [messageError, setMessageError] = useState("");
  const [loading, setLoading] = useState(false);
  const [successMsg, setSuccessMsg] = useState("");

  const handleChange = (e) => {
    const { name, value } = e.target;
    
    if (name === "message") {
      // mostrar error si excede 500, pero permitir escribir
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

    // Limpiar error del campo cuando el usuario empieza a typing
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
      newErrors.name = "Completa este campo";
    }

    if (!formData.email.trim()) {
      newErrors.email = "Completa este campo";
    } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
      newErrors.email = "El correo no es válido";
    }

    if (!formData.phone.trim()) {
      newErrors.phone = "Completa este campo";
    } else if (!/^\d{7,}$/.test(formData.phone.replace(/\s|-/g, ""))) {
      newErrors.phone = "El teléfono debe tener al menos 7 dígitos";
    }

    if (!formData.message.trim()) {
      newErrors.message = "Completa este campo";
    } else if (formData.message.length > 500) {
      newErrors.message = "El mensaje no debe exceder 500 caracteres";
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
      <Header2 />
      <div className="container-contact" style={{ marginTop: "100px" }}>
        <div className="form">

          {/* ------- COLUMNA IZQUIERDA ------- */}
          <div className="contact-info">
            <h3 className="tittle">Pongámonos en contacto</h3>
            <p className="text">
              Escríbenos y te ayudamos a encontrar la mejor opción para tu experiencia de ecoturismo
            </p>

            <div className="info">
              <div className="information">
                <img src="src/assets/maps-and-location.png" className="icon" alt="Ubicación" />
                <p>Dosquebradas-Pereira</p>
              </div>

              <div className="information">
                <img src="src/assets/correo-electronico.png" className="icon" alt="Email" />
                <a href="mailto:proyectoecoturismo2@gmail.com">
                  <p>proyectoecoturismo2@gmail.com</p>
                </a>
              </div>

              <div className="information">
                <img src="src/assets/telefono.png" className="icon" alt="Teléfono" />
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
                <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer">
                  <img src="src/assets/iconofb.png" width="30px" alt="Facebook" />
                </a>

                <a href="https://www.whatsapp.com" target="_blank" rel="noopener noreferrer">
                  <img src="src/assets/iconowp.png" width="30px" alt="WhatsApp" />
                </a>

                <a href="https://www.instagram.com" target="_blank" rel="noopener noreferrer">
                  <img src="src/assets/iconoig.png" width="30px" alt="Instagram" />
                </a>
              </div>
            </div>
          </div>

          {/* ------- COLUMNA DERECHA ------- */}
          <div className="contact-form">
            <form onSubmit={handleSubmit}>
              <h3 className="tittle">Contáctanos</h3>

              <div className="input-container">
                <input
                  type="text"
                  name="name"
                  className={`input ${errors.name ? "input-error" : ""}`}
                  value={formData.name}
                  onChange={handleChange}
                />
                <label>Nombre de usuario</label>
                <span>Nombre de usuario</span>
                {errors.name && <p className="error-message">{errors.name}</p>}
              </div>

              <div className="input-container">
                <input
                  type="email"
                  name="email"
                  className={`input ${errors.email ? "input-error" : ""}`}
                  value={formData.email}
                  onChange={handleChange}
                />
                <label>Correo</label>
                <span>Correo</span>
                {errors.email && <p className="error-message">{errors.email}</p>}
              </div>

              <div className="input-container">
                <input
                  type="tel"
                  name="phone"
                  className={`input ${errors.phone ? "input-error" : ""}`}
                  value={formData.phone}
                  onChange={handleChange}
                />
                <label>Teléfono</label>
                <span>Teléfono</span>
                {errors.phone && <p className="error-message">{errors.phone}</p>}
              </div>

              <div className="input-container textarea">
                <textarea
                  name="message"
                  className={`input ${errors.message ? "input-error" : ""}`}
                  value={formData.message}
                  onChange={handleChange}
                ></textarea>
                <label>Mensaje</label>
                <span>Mensaje</span>
                {errors.message && <p className="error-message">{errors.message}</p>}
              </div>
              <div className={`char-counter ${formData.message.length > 500 ? 'error' : ''}`}>
                {formData.message.length}/500
              </div>

              <input 
                type="submit" 
                value={loading ? "Enviando..." : "Enviar"} 
                className="btn" 
                disabled={loading}
              />

              {successMsg && (
                <p style={{ color: "#27ae60", marginTop: "15px", fontWeight: "bold", textAlign: "center" }}>
                  ✓ {successMsg}
                </p>
              )}

              {errors.general && (
                <p style={{ color: "#e74c3c", marginTop: "15px", fontWeight: "bold", textAlign: "center" }}>
                  ✗ {errors.general}
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
