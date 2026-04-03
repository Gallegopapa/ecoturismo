import React, { useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "./context/AuthContext";
import "./index.css";
import Header from "./components/Header/Header";
import Header2 from "./components/Header2/Header2";
import Footer from "./components/Footer/Footer";
import Slider from "./components/slider/Slider";
import HomePage from "./components/HomePage";

function App() {
  const { user, isAuthenticated, loading } = useAuth();
  const navigate = useNavigate();

  // Si el usuario está logueado, redirigir a pagLogueados
  // Solo redirigir si no está en la página de login/registro
  useEffect(() => {
    if (!loading && isAuthenticated && user) {
      const currentPath = window.location.pathname;
      // Redirige únicamente si está en la página de inicio
      if (currentPath === '/') {
        navigate('/pagLogueados', { replace: true });
      }
    }
  }, [isAuthenticated, user, loading, navigate]);

  if (loading) {
    return (
      <div className="page-layout">
        <Header />
        <div className="page-content" style={{ display: 'flex', justifyContent: 'center', alignItems: 'center' }}>
          <p>Cargando...</p>
        </div>
        <Footer />
      </div>
    );
  }

  return (
    <div className="page-layout">
      {isAuthenticated && user ? <Header2 /> : <Header />}
      <main className="page-content">
        <HomePage loggedIn={isAuthenticated && user} user={user} />
      </main>
      <Footer />
    </div>
  );
}

export default App;
