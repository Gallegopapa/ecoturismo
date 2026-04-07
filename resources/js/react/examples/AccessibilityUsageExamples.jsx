/**
 * EJEMPLO DE USO DEL SISTEMA DE ACCESIBILIDAD E IDIOMAS
 * 
 * Este archivo muestra cómo integrar el sistema de accesibilidad
 * e internacionalización en cualquier componente de tu aplicación.
 */

import { useTranslation } from '../i18n/useTranslation';
import { useLanguage } from '../contexts/LanguageContext';
import { useAccessibility } from '../contexts/AccessibilityContext';

// ===========================================
// EJEMPLO 1: Componente con Traducciones
// ===========================================

const WelcomeComponent = () => {
  const { t, language } = useTranslation();

  return (
    <div>
      {/* Usar traducciones con la función t() */}
      <h1>{t('welcome')}</h1>
      <p>{t('loading')}</p>
      
      {/* Mostrar idioma actual */}
      <small>Idioma actual: {language}</small>
      
      {/* Botones con textos traducidos */}
      <button>{t('save')}</button>
      <button>{t('cancel')}</button>
      <button>{t('confirm')}</button>
    </div>
  );
};

// ===========================================
// EJEMPLO 2: Navegación Multiidioma
// ===========================================

const Navigation = () => {
  const { t } = useTranslation();

  return (
    <nav>
      <ul>
        <li><a href="/">{t('home')}</a></li>
        <li><a href="/lugares">{t('places')}</a></li>
        <li><a href="/ecohoteles">{t('ecohotels')}</a></li>
        <li><a href="/nosotros">{t('about')}</a></li>
        <li><a href="/contacto">{t('contact')}</a></li>
      </ul>
    </nav>
  );
};

// ===========================================
// EJEMPLO 3: Selector de Idioma Personalizado
// ===========================================

const CustomLanguageSelector = () => {
  const { language, changeLanguage } = useLanguage();
  const { t } = useTranslation();

  return (
    <div className="language-selector-custom">
      <label htmlFor="lang-select">{t('selectLanguage')}:</label>
      <select 
        id="lang-select"
        value={language} 
        onChange={(e) => changeLanguage(e.target.value)}
      >
        <option value="es">{t('spanish')}</option>
        <option value="en">{t('english')}</option>
      </select>
    </div>
  );
};

// ===========================================
// EJEMPLO 4: Botón Rápido de Cambio de Idioma
// ===========================================

const QuickLanguageToggle = () => {
  const { language, toggleLanguage } = useLanguage();
  const { t } = useTranslation();

  return (
    <button 
      onClick={toggleLanguage}
      className="quick-lang-toggle"
      aria-label={`${t('language')}: ${language.toUpperCase()}`}
    >
      {language === 'es' ? '🇪🇸 ES' : '🇬🇧 EN'}
    </button>
  );
};

// ===========================================
// EJEMPLO 5: Control de Accesibilidad Custom
// ===========================================

const AccessibilityControls = () => {
  const { 
    fontSize, 
    highContrast, 
    underlineLinks,
    grayscale,
    increaseFontSize, 
    decreaseFontSize,
    toggleHighContrast,
    toggleUnderlineLinks,
    toggleGrayscale,
    resetSettings 
  } = useAccessibility();

  const { t } = useTranslation();

  return (
    <div className="custom-accessibility-controls">
      <h3>{t('accessibilityOptions')}</h3>
      
      {/* Información de estado actual */}
      <div className="status-info">
        <p>Tamaño de fuente: <strong>{fontSize}</strong></p>
        <p>Alto contraste: <strong>{highContrast ? 'Activo' : 'Inactivo'}</strong></p>
        <p>Subrayar enlaces: <strong>{underlineLinks ? 'Activo' : 'Inactivo'}</strong></p>
        <p>Escala de grises: <strong>{grayscale ? 'Activo' : 'Inactivo'}</strong></p>
      </div>

      {/* Controles personalizados */}
      <div className="controls">
        <button onClick={decreaseFontSize}>
          A- {t('decreaseFontSize')}
        </button>
        <button onClick={increaseFontSize}>
          A+ {t('increaseFontSize')}
        </button>
        <button onClick={toggleHighContrast}>
          {highContrast ? '🎨 Desactivar' : '🎨 Activar'} Contraste
        </button>
        <button onClick={toggleUnderlineLinks}>
          {underlineLinks ? '🔗 Quitar' : '🔗 Activar'} Subrayado
        </button>
        <button onClick={toggleGrayscale}>
          {grayscale ? '🌈 Color' : '⚫ Gris'}
        </button>
        <button onClick={resetSettings} className="reset-btn">
          🔄 {t('resetSettings')}
        </button>
      </div>
    </div>
  );
};

// ===========================================
// EJEMPLO 6: Componente de Formulario Multiidioma
// ===========================================

const ContactForm = () => {
  const { t } = useTranslation();

  return (
    <form>
      <h2>{t('contact')}</h2>
      
      <div className="form-group">
        <label htmlFor="name">
          {/* Si la traducción no existe, retorna la clave */}
          {t('name') || 'Nombre'}
        </label>
        <input 
          type="text" 
          id="name" 
          placeholder={t('name')}
        />
      </div>

      <div className="form-group">
        <label htmlFor="email">
          {t('email') || 'Email'}
        </label>
        <input 
          type="email" 
          id="email" 
          placeholder={t('email')}
        />
      </div>

      <div className="form-group">
        <label htmlFor="message">
          {t('message') || 'Mensaje'}
        </label>
        <textarea 
          id="message" 
          placeholder={t('message')}
        />
      </div>

      <button type="submit">{t('save')}</button>
      <button type="button">{t('cancel')}</button>
    </form>
  );
};

// ===========================================
// EJEMPLO 7: Alerta con Traducciones
// ===========================================

const Alert = ({ type, message }) => {
  const { t } = useTranslation();

  const getTypeLabel = () => {
    switch (type) {
      case 'success': return t('success');
      case 'error': return t('error');
      default: return '';
    }
  };

  return (
    <div className={`alert alert-${type}`}>
      <strong>{getTypeLabel()}: </strong>
      {message}
    </div>
  );
};

// ===========================================
// EJEMPLO 8: Lista de Lugares con Traducciones
// ===========================================

const PlacesList = ({ places }) => {
  const { t } = useTranslation();

  if (!places || places.length === 0) {
    return <p>{t('loading')}</p>;
  }

  return (
    <div className="places-list">
      <h2>{t('places')}</h2>
      
      {/* Barra de búsqueda */}
      <input 
        type="search" 
        placeholder={t('search')}
      />

      {/* Lista de lugares */}
      <ul>
        {places.map(place => (
          <li key={place.id}>
            <h3>{place.name}</h3>
            <p>{place.description}</p>
            <button>{t('edit')}</button>
            <button>{t('delete')}</button>
          </li>
        ))}
      </ul>
    </div>
  );
};

// ===========================================
// EJEMPLO 9: Modal Multiidioma
// ===========================================

const Modal = ({ isOpen, onClose, children }) => {
  const { t } = useTranslation();

  if (!isOpen) return null;

  return (
    <div className="modal-overlay" onClick={onClose}>
      <div className="modal-content" onClick={(e) => e.stopPropagation()}>
        <button 
          className="modal-close" 
          onClick={onClose}
          aria-label={t('closePanel')}
        >
          ✕
        </button>
        {children}
        <div className="modal-footer">
          <button onClick={onClose}>{t('cancel')}</button>
          <button onClick={onClose}>{t('confirm')}</button>
        </div>
      </div>
    </div>
  );
};

// ===========================================
// EJEMPLO 10: Footer Multiidioma
// ===========================================

const Footer = () => {
  const { t, language } = useTranslation();
  const currentYear = new Date().getFullYear();

  return (
    <footer>
      <div className="footer-content">
        <p>
          © {currentYear} {t('appName') || 'Ecoturismo'}. 
          {language === 'es' 
            ? ' Todos los derechos reservados.' 
            : ' All rights reserved.'}
        </p>
        
        <nav>
          <a href="/terminos-de-uso">{t('termsOfUse') || 'Términos de uso'}</a>
          <a href="/politica-de-privacidad">{t('privacyPolicy') || 'Privacidad'}</a>
          <a href="/cookies">{t('cookies') || 'Cookies'}</a>
        </nav>
      </div>
    </footer>
  );
};

// ===========================================
// EJEMPLO 11: Componente con Condicionales de Idioma
// ===========================================

const PriceDisplay = ({ price }) => {
  const { language } = useLanguage();
  const { t } = useTranslation();

  // Formatear precio según idioma
  const formatPrice = (amount) => {
    if (language === 'es') {
      return `$${amount.toLocaleString('es-ES')} COP`;
    } else {
      return `USD $${amount.toLocaleString('en-US')}`;
    }
  };

  return (
    <div className="price-display">
      <span className="label">{t('price')}:</span>
      <span className="amount">{formatPrice(price)}</span>
    </div>
  );
};

// ===========================================
// EJEMPLO 12: Dashboard con Todo Integrado
// ===========================================

const Dashboard = () => {
  const { t } = useTranslation();
  const { fontSize, highContrast } = useAccessibility();
  const { language } = useLanguage();

  return (
    <div className="dashboard">
      <header>
        <h1>{t('welcome')}</h1>
        <QuickLanguageToggle />
      </header>

      <div className="dashboard-info">
        <p>Configuración actual:</p>
        <ul>
          <li>Idioma: {language.toUpperCase()}</li>
          <li>Tamaño de fuente: {fontSize}</li>
          <li>Alto contraste: {highContrast ? 'Sí' : 'No'}</li>
        </ul>
      </div>

      <Navigation />
      
      <main>
        <h2>{t('places')}</h2>
        {/* Contenido del dashboard */}
      </main>

      <Footer />
    </div>
  );
};

// ===========================================
// EXPORTAR EJEMPLOS
// ===========================================

export {
  WelcomeComponent,
  Navigation,
  CustomLanguageSelector,
  QuickLanguageToggle,
  AccessibilityControls,
  ContactForm,
  Alert,
  PlacesList,
  Modal,
  Footer,
  PriceDisplay,
  Dashboard
};

/**
 * NOTAS FINALES:
 * 
 * 1. Puedes copiar cualquiera de estos componentes a tu aplicación
 * 2. Ajusta los estilos según tu diseño
 * 3. Agrega más traducciones en translations.js según necesites
 * 4. Todos estos componentes funcionan porque main.jsx ya tiene los providers
 * 5. No necesitas hacer ninguna configuración adicional
 */
