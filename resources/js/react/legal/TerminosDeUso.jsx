import React from "react";
import Header from "@/react/components/Header/Header";
import Header2 from "@/react/components/Header2/Header2";
import Footer from "@/react/components/Footer/Footer";
import "./LegalPage.css";

export default function TerminosDeUsoPage() {
  const isAuthenticated = !!localStorage.getItem('token') || !!localStorage.getItem('user');

  return (
    <>
      {isAuthenticated ? <Header2 /> : <Header />}
      <main className="legal-page">
        <section className="legal-card">
          <h1>Términos de uso</h1>
          <p>Al usar este sitio aceptas estos términos. Si no estás de acuerdo, por favor no continúes usando el servicio.</p>

          <h2>Uso permitido</h2>
          <p>Te comprometes a usar la plataforma de forma legal y a no vulnerar los derechos de otros usuarios.</p>

          <h2>Cuentas y seguridad</h2>
          <p>Debes mantener la confidencialidad de tus credenciales. Eres responsable de la actividad realizada con tu cuenta.</p>

          <h2>Contenido</h2>
          <p>No publiques ni compartas contenido ilegal, ofensivo o que infrinja derechos de terceros.</p>

          <h2>Disponibilidad del servicio</h2>
          <p>Podemos modificar o suspender funcionalidades sin previo aviso. Procuramos mantener el servicio disponible y seguro.</p>

          <h2>Limitación de responsabilidad</h2>
          <p>El uso del sitio es bajo tu propio riesgo. No garantizamos disponibilidad ininterrumpida ni ausencia total de errores.</p>

          <h2>Contacto</h2>
          <p>Para cualquier consulta: <a href="mailto:proyectoecoturismo2@gmail.com">proyectoecoturismo2@gmail.com</a>.</p>
        </section>
      </main>
      <Footer />
    </>
  );
}
