import React from "react";
import { Link } from "react-router-dom";
import "./footer.css";

const Footer = () => {
  return (
    <>
      {/* FOOTER */}
          <footer className="containerFooter">
            <div className="footer-inner">
              <p className="footer-copy">&copy; 2025 RisaraldaEcoTurismo</p>
              <p className="footer-links">
                <Link to="/cookies">Cookies</Link>
                <span className="sep">|</span>
                <Link to="/terminos-de-uso">Términos de uso</Link>
                <span className="sep">|</span>
                <Link to="/politica-de-privacidad">Política de privacidad</Link>
                <span className="sep">|</span>
                <Link to="/CopyrightTotal">Copyright EcoTurismo</Link>
              </p>
            </div>
          </footer>
    </>
  );
};

export default Footer;

