import { createContext, useContext, useState, useEffect } from 'react';

/**
 * AccessibilityContext
 * 
 * Contexto global para gestionar las configuraciones de accesibilidad.
 * Maneja:
 * - Tamaño de texto (3 niveles: normal, large, extra-large)
 * - Alto contraste (activado/desactivado)
 * - Subrayado de enlaces (activado/desactivado)
 * - Escala de grises (activado/desactivado)
 * 
 * Todas las configuraciones se guardan en localStorage y se aplican
 * como clases CSS en el elemento <body> del documento.
 */

const AccessibilityContext = createContext();

// Hook personalizado para usar el contexto de accesibilidad
export const useAccessibility = () => {
  const context = useContext(AccessibilityContext);
  if (!context) {
    throw new Error('useAccessibility debe usarse dentro de AccessibilityProvider');
  }
  return context;
};

export const AccessibilityProvider = ({ children }) => {
  // Estados para cada opción de accesibilidad
  const [fontSize, setFontSize] = useState('normal'); // 'normal' | 'large' | 'extra-large'
  // Eliminado highContrast
  const [underlineLinks, setUnderlineLinks] = useState(false);
  const [grayscale, setGrayscale] = useState(false);
  const [dyslexiaFont, setDyslexiaFont] = useState(false);
  const [lineSpacing, setLineSpacing] = useState('normal'); // 'normal' | 'relaxed' | 'very-relaxed'
  const [reduceMotion, setReduceMotion] = useState(false);

  /**
   * Efecto inicial: Limpiar body de clases anteriores
   * Se ejecuta una vez al montar el componente
   */
  useEffect(() => {
    const body = document.body;
    // Remover TODAS las clases de accesibilidad al iniciar
    body.classList.remove(
      'font-large', 'font-extra-large', 'high-contrast', 
      'underline-links', 'grayscale-mode', 'line-spacing-relaxed',
      'line-spacing-very-relaxed', 'reduce-motion'
    );
  }, []);

  /**
   * Efecto inicial: Cargar configuraciones guardadas desde localStorage
   * Se ejecuta una vez al montar el componente
   * IMPORTANTE: Siempre comienza con valores por defecto limpios
   */
  useEffect(() => {
    const loadSettings = () => {
      try {
        const savedSettings = localStorage.getItem('accessibility-settings');
        if (savedSettings) {
          const settings = JSON.parse(savedSettings);
          // Solo cargar si son válidos, de lo contrario usar defaults
          if (settings.fontSize && ['normal', 'large', 'extra-large'].includes(settings.fontSize)) {
            setFontSize(settings.fontSize);
          }
          // Eliminado highContrast del loader
          if (typeof settings.dyslexiaFont === 'boolean') {
            setDyslexiaFont(settings.dyslexiaFont);
          }
          if (typeof settings.underlineLinks === 'boolean') {
            setUnderlineLinks(settings.underlineLinks);
          }
          if (typeof settings.grayscale === 'boolean') {
            setGrayscale(settings.grayscale);
          }
          if (settings.lineSpacing && ['normal', 'relaxed', 'very-relaxed'].includes(settings.lineSpacing)) {
            setLineSpacing(settings.lineSpacing);
          }
          if (typeof settings.reduceMotion === 'boolean') {
            setReduceMotion(settings.reduceMotion);
          }
        }
      } catch (error) {
        console.error('Error al cargar configuraciones de accesibilidad:', error);
        // Si hay error, limpiar localStorage corrupto
        try {
          localStorage.removeItem('accessibility-settings');
        } catch (e) {
          console.error('Error al limpiar localStorage:', e);
        }
      }
    };

    loadSettings();
  }, []);

  /**
   * Efecto: Guardar configuraciones en localStorage cuando cambien
   * Se ejecuta cada vez que cambia alguna configuración
   */
  useEffect(() => {
    const settings = {
      fontSize,
      // highContrast,
      underlineLinks,
      grayscale,
      dyslexiaFont,
      lineSpacing,
      reduceMotion
    };

    try {
      localStorage.setItem('accessibility-settings', JSON.stringify(settings));
    } catch (error) {
      console.error('Error al guardar configuraciones de accesibilidad:', error);
    }
  }, [fontSize, underlineLinks, grayscale, dyslexiaFont, lineSpacing, reduceMotion]);

  /**
   * Efecto: Aplicar clases CSS al <body> según configuraciones activas
   * Cada vez que cambia una configuración, actualiza las clases del body
   */
  useEffect(() => {
    const body = document.body;

    // Limpiar todas las clases de accesibilidad previas
    body.classList.remove('font-large', 'font-extra-large', 'high-contrast', 
                 'underline-links', 'grayscale-mode', 'line-spacing-relaxed',
                 'line-spacing-very-relaxed', 'reduce-motion', 'font-dyslexia');

    // Aplicar clases según configuraciones activas
    if (fontSize === 'large') {
      body.classList.add('font-large');
    } else if (fontSize === 'extra-large') {
      body.classList.add('font-extra-large');
    }

    // if (highContrast) {
    //   body.classList.add('high-contrast');
    // }
    if (dyslexiaFont) {
      body.classList.add('font-dyslexia');
    }

    if (underlineLinks) {
      body.classList.add('underline-links');
    }

    if (grayscale) {
      body.classList.add('grayscale-mode');
    }

    if (lineSpacing === 'relaxed') {
      body.classList.add('line-spacing-relaxed');
    } else if (lineSpacing === 'very-relaxed') {
      body.classList.add('line-spacing-very-relaxed');
    }

    if (reduceMotion) {
      body.classList.add('reduce-motion');
    }
  }, [fontSize, underlineLinks, grayscale, dyslexiaFont, lineSpacing, reduceMotion]);

  /**
   * Aumentar el tamaño de texto
   * Cicla entre: normal → large → extra-large
   */
  const increaseFontSize = () => {
    if (fontSize === 'normal') {
      setFontSize('large');
    } else if (fontSize === 'large') {
      setFontSize('extra-large');
    }
    // Si ya está en extra-large, no hace nada
  };

  /**
   * Disminuir el tamaño de texto
   * Cicla entre: extra-large → large → normal
   */
  const decreaseFontSize = () => {
    if (fontSize === 'extra-large') {
      setFontSize('large');
    } else if (fontSize === 'large') {
      setFontSize('normal');
    }
    // Si ya está en normal, no hace nada
  };

  /**
   * Alternar alto contraste
   */
  // const toggleHighContrast = () => {
  //   setHighContrast(prev => !prev);
  // };
  const toggleDyslexiaFont = () => {
    setDyslexiaFont(prev => !prev);
  };

  /**
   * Alternar subrayado de enlaces
   */
  const toggleUnderlineLinks = () => {
    setUnderlineLinks(prev => !prev);
  };

  /**
   * Alternar escala de grises
   */
  const toggleGrayscale = () => {
    setGrayscale(prev => !prev);
  };

  /**
   * Aumentar espaciado de líneas
   */
  const increaseLineSpacing = () => {
    if (lineSpacing === 'normal') {
      setLineSpacing('relaxed');
    } else if (lineSpacing === 'relaxed') {
      setLineSpacing('very-relaxed');
    }
  };

  /**
   * Disminuir espaciado de líneas
   */
  const decreaseLineSpacing = () => {
    if (lineSpacing === 'very-relaxed') {
      setLineSpacing('relaxed');
    } else if (lineSpacing === 'relaxed') {
      setLineSpacing('normal');
    }
  };

  /**
   * Alternar reducción de movimiento
   */
  const toggleReduceMotion = () => {
    setReduceMotion(prev => !prev);
  };

  /**
   * Resetear todas las configuraciones a valores por defecto
   */
  const resetSettings = () => {
    setFontSize('normal');
    // Eliminado setHighContrast del reset
    setDyslexiaFont(false);
    setUnderlineLinks(false);
    setGrayscale(false);
    setLineSpacing('normal');
    setReduceMotion(false);
  };

  // Valor del contexto que se provee a todos los componentes hijos
  const value = {
    // Estados
    fontSize,
    // Eliminado highContrast del value
    underlineLinks,
    grayscale,
    dyslexiaFont,
    lineSpacing,
    reduceMotion,
    
    // Funciones
    increaseFontSize,
    decreaseFontSize,
    // Eliminado toggleHighContrast del value
    toggleUnderlineLinks,
    toggleGrayscale,
    toggleDyslexiaFont,
    increaseLineSpacing,
    decreaseLineSpacing,
    toggleReduceMotion,
    resetSettings
  };

  return (
    <AccessibilityContext.Provider value={value}>
      {children}
    </AccessibilityContext.Provider>
  );
};
