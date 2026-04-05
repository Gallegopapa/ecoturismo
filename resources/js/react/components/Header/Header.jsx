import React, { useState } from "react";
import { Link } from "react-router-dom";
import { useTranslation } from "../../i18n/useTranslation";
import icono from "@/react/components/imagenes/iconoecoturismo.jpg";
import "./Header.css";

const Header = () => {
    const { t } = useTranslation();
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

    const toggleMobileMenu = () => {
        setMobileMenuOpen(!mobileMenuOpen);
    };

    const closeMobileMenu = () => {
        setMobileMenuOpen(false);
    };

    return (
        <>
            {/* HEADER */}
            <header>
                <div className="header-container">
                    <Link to="/" className="logo-principal">
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
                        <span
                            className={
                                mobileMenuOpen ? "hamburger open" : "hamburger"
                            }
                        >
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>

                    <nav
                        className={`navbar ${mobileMenuOpen ? "mobile-open" : ""}`}
                    >
                        <Link
                            to="/comentarios"
                            onClick={closeMobileMenu}
                            data-i18n="reviews"
                        >
                            Reseñas
                        </Link>
                        <Link
                            to="/lugares"
                            onClick={closeMobileMenu}
                            data-i18n="places"
                        >
                            {t("places")}
                        </Link>
                        <Link
                            to="/ecohoteles"
                            onClick={closeMobileMenu}
                            data-i18n="ecohotels"
                        >
                            {t("ecohotels")}
                        </Link>
                        <Link
                            to="/contacto"
                            onClick={closeMobileMenu}
                            data-i18n="contact"
                        >
                            {t("contact")}
                        </Link>
                        <Link
                            to="/login"
                            onClick={closeMobileMenu}
                            data-i18n="login"
                            className="login-btn"
                        >
                            {t("login")}
                        </Link>
                    </nav>
                </div>
            </header>
        </>
    );
};

export default Header;
