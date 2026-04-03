import React from "react";
import Header from "@/react/components/Header/Header";
import Header2 from "@/react/components/Header2/Header2";
import Footer from "@/react/components/Footer/Footer";
import "./LegalPage.css";

export default function PoliticaDePrivacidadPage() {
  const isAuthenticated = !!localStorage.getItem('token') || !!localStorage.getItem('user');

  return (
    <>
      {isAuthenticated ? <Header2 /> : <Header />}
      <main className="legal-page">
        <section className="legal-card">
          <h1>Política de privacidad</h1>
          <p>Esta política explica cómo tratamos tus datos personales cuando usas nuestro sitio.</p>

          <h2>Datos que recolectamos</h2>
          <ul>
            <li>Datos de contacto: nombre, correo, teléfono cuando los proporcionas.</li>
            <li>Datos de uso: páginas visitadas, interacciones y métricas anónimas.</li>
          </ul>

          <h2>Para qué usamos tus datos</h2>
          <ul>
            <li>Prestar y mejorar el servicio.</li>
            <li>Responder solicitudes de contacto y soporte.</li>
            <li>Enviar comunicaciones relacionadas con el servicio cuando corresponda.</li>
          </ul>

          <h2>Con quién compartimos</h2>
          <p>No vendemos tus datos. Solo los compartimos con proveedores que nos ayudan a operar el servicio (hosting, analítica) bajo obligaciones de confidencialidad.</p>

          <h2>Tus derechos</h2>
          <p>Puedes solicitar acceso, corrección o eliminación de tus datos escribiendo a nuestro correo.</p>

          <h2>Contacto</h2>
          <p>Correo: <a href="mailto:proyectoecoturismo2@gmail.com">proyectoecoturismo2@gmail.com</a></p>
        </section>
      </main>
      <Footer />
    </>
  );
}
