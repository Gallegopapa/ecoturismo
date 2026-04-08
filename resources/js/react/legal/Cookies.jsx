import React from "react";
import Header from "@/react/components/Header/Header";
import Header2 from "@/react/components/Header2/Header2";
import Footer from "@/react/components/Footer/Footer";
import "./LegalPage.css";

export default function CookiesPage() {
  const isAuthenticated = !!localStorage.getItem('token') || !!localStorage.getItem('user');

  return (
    <>
      {isAuthenticated ? <Header2 /> : <Header />}
      <main className="legal-page">
        <section className="legal-card">
          <h1>Política de Cookies</h1>
          <p>Esta política explica qué son las cookies, qué tipos usamos y cómo puedes gestionarlas.</p>

          <h2>¿Qué son las cookies?</h2>
          <p>Son pequeños archivos de texto que se almacenan en tu navegador al visitar un sitio web. Sirven para recordar preferencias y mejorar la experiencia de usuario.</p>

          <h2>Cookies que usamos</h2>
          <ul>
            <li><strong>Esenciales:</strong> necesarias para el funcionamiento básico del sitio.</li>
            <li><strong>Analíticas:</strong> nos ayudan a entender el uso de la web de forma agregada.</li>
            <li><strong>Funcionales:</strong> recuerdan tus preferencias (idioma, sesión).</li>
          </ul>

          <h2>Cómo gestionar las cookies</h2>
          <p>Puedes aceptar o rechazar las cookies desde la configuración de tu navegador o mediante las opciones que ofrecemos en el banner de consentimiento.</p>

          <h2>Contacto</h2>
          <p>Si tienes preguntas, escríbenos a <a href="mailto:proyectoecoturismo2@gmail.com">proyectoecoturismo2@gmail.com</a>.</p>
        </section>
      </main>
      <Footer />
    </>
  );
}
