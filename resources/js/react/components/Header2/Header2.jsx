import React, { useState, useRef, useEffect } from "react";
import { Link, useNavigate } from "react-router-dom";
import { useAuth } from "../../context/AuthContext";
import icono from "../imagenes/iconoecoturismo.jpg";
import usuarioImg from "../imagenes/usuario.jpg";
import "./Header2.css";

const Header2 = () => {
  const { user, logout, isAdmin } = useAuth();
  const navigate = useNavigate();
  const [openPlacesMenu, setOpenPlacesMenu] = useState(false);
  const [openUserMenu, setOpenUserMenu] = useState(false);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const placesDropdownRef = useRef(null);
  const userDropdownRef = useRef(null);

  // Verificar si es usuario empresa
  const isCompanyUser = user?.tipo_usuario === 'empresa';

  const resolveAvatarSrc = (rawValue) => {
    if (!rawValue || rawValue === 'null' || rawValue === 'undefined') {
      return usuarioImg;
    }

    if (typeof rawValue !== 'string') {
      return usuarioImg;
    }

    if (/^https?:\/\//i.test(rawValue) || rawValue.startsWith('/')) {
      return rawValue;
    }

    const normalized = rawValue.replace(/\\/g, '/');
    const fileName = normalized.split('/').filter(Boolean).pop();

    if (!fileName) {
      return usuarioImg;
    }

    return `/api/profile/photo/${encodeURIComponent(fileName)}`;
  };

  const togglePlacesMenu = () => {
    setOpenPlacesMenu((prev) => !prev);
    setOpenUserMenu(false); // Cerrar el otro menú
  };

  const toggleUserMenu = () => {
    setOpenUserMenu((prev) => !prev);
    setOpenPlacesMenu(false); // Cerrar el otro menú
  };

  // Cierra los menús si se hace clic fuera
  useEffect(() => {
    const handleClickOutside = (event) => {
      if (placesDropdownRef.current && !placesDropdownRef.current.contains(event.target)) {
        setOpenPlacesMenu(false);
      }
      if (userDropdownRef.current && !userDropdownRef.current.contains(event.target)) {
        setOpenUserMenu(false);
      }
    };
    document.addEventListener("mousedown", handleClickOutside);
    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);

  // Cierra los menús al hacer clic en un enlace
  const handleLinkClick = () => {
    setOpenPlacesMenu(false);
    setOpenUserMenu(false);
    setMobileMenuOpen(false);
  };

  const toggleMobileMenu = () => {
    setMobileMenuOpen(!mobileMenuOpen);
    if (!mobileMenuOpen) {
      setOpenPlacesMenu(false);
      setOpenUserMenu(false);
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

  return (
    <header>
      <div className="header-container">
        <Link to="/pagLogueados" className="logo-principal">
          <img src={icono} alt="Logo" width="60" />
          <div className="titulos">
            <h2 className="risaralda">RisaraldaEcoTurismo</h2>
          </div>
        </Link>

        {/* Botón hamburguesa */}
        <button 
          className="mobile-menu-toggle"
          onClick={toggleMobileMenu}
          aria-label="Toggle menu"
          aria-expanded={mobileMenuOpen}
        >
          <span className={mobileMenuOpen ? "hamburger open" : "hamburger"}>
            <span></span>
            <span></span>
            <span></span>
          </span>
        </button>

        <nav className={`navbar ${mobileMenuOpen ? "mobile-open" : ""}`}>
          {/* Enlaces de navegación vaciados para la etapa de Auth pura */}

          {/* Menú desplegable de Usuario */}
          <div className="dropdown user-dropdown" ref={userDropdownRef}>
            <button
              className="dropdown-btn user-menu-btn"
              onClick={toggleUserMenu}
              aria-expanded={openUserMenu}
              aria-haspopup="true"
            >
              <img 
                src={resolveAvatarSrc(user?.foto_perfil)} 
                alt={user?.name || "Usuario"}
                className="user-avatar"
                onError={(e) => {
                  e.target.src = usuarioImg;
                }}
              />
              <span className="user-name">{user?.name || "Usuario"}</span>
              <span className="arrow">{openUserMenu ? "▲" : "▼"}</span>
            </button>

            {openUserMenu && (
              <ul className="dropdown-menu user-menu" role="menu">
                {/* Otros enlaces de usuario desactivados por ahora */}
                <li>
                  <Link to="/perfil" className="menu-link" role="menuitem" onClick={handleLinkClick}>
                    Mi Perfil
                  </Link>
                </li>
                {user?.is_admin && (
                  <li>
                    <Link to="/admin/places" className="menu-link admin-link" role="menuitem" onClick={handleLinkClick}>
                      Gestión de Lugares (Admin)
                    </Link>
                  </li>
                )}
                {user?.tipo_usuario === 'empresa' && (
                  <li>
                    <Link to="/company/dashboard" className="menu-link company-link" role="menuitem" onClick={handleLinkClick}>
                      Panel de Empresa
                    </Link>
                  </li>
                )}
                <li className="menu-divider"></li>
                <li>
                  <button
                    onClick={handleLogout}
                    className="menu-button logout-btn"
                    role="menuitem"
                  >
                    Cerrar Sesión
                  </button>
                </li>
              </ul>
            )}
          </div>
        </nav>
      </div>
    </header>
  );
};

export default Header2;
