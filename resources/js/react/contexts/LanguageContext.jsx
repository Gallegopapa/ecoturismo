import { createContext, useContext, useState, useEffect } from 'react';

/**
 * LanguageContext
 * 
 * Contexto global para gestionar el idioma de la aplicación.
 * Maneja:
 * - Idioma actual ('es' o 'en')
 * - Cambio dinámico de idioma
 * - Persistencia en localStorage
 * - Actualización del atributo lang en <html>
 * 
 * El idioma se guarda en localStorage y se restaura automáticamente.
 */

const LanguageContext = createContext();

// Hook personalizado para usar el contexto de idioma
export const useLanguage = () => {
  const context = useContext(LanguageContext);
  if (!context) {
    throw new Error('useLanguage debe usarse dentro de LanguageProvider');
  }
  return context;
};

export const LanguageProvider = ({ children }) => {
  // Estado del idioma actual: 'es' (español) o 'en' (inglés)
  // Por defecto: 'es'
  const [language, setLanguage] = useState('es');

  /**
   * Efecto inicial: Cargar idioma guardado desde localStorage
   * Se ejecuta una vez al montar el componente
   */
  useEffect(() => {
    try {
      const savedLanguage = localStorage.getItem('app-language');
      if (savedLanguage && (savedLanguage === 'es' || savedLanguage === 'en')) {
        setLanguage(savedLanguage);
      }
    } catch (error) {
      console.error('Error al cargar el idioma:', error);
    }
  }, []);

  /**
   * Efecto: Guardar idioma en localStorage y actualizar atributo html lang
   * Se ejecuta cada vez que cambia el idioma
   */
  useEffect(() => {
    try {
      // Guardar en localStorage
      localStorage.setItem('app-language', language);

      // Actualizar el atributo lang del elemento <html>
      // Esto es importante para accesibilidad (lectores de pantalla)
      document.documentElement.lang = language;
    } catch (error) {
      console.error('Error al guardar el idioma:', error);
    }
  }, [language]);

  /**
   * Cambiar el idioma de la aplicación
   * @param {string} newLanguage - 'es' o 'en'
   */
  const changeLanguage = (newLanguage) => {
    if (newLanguage === 'es' || newLanguage === 'en') {
      setLanguage(newLanguage);
    } else {
      console.warn(`Idioma no soportado: ${newLanguage}. Usa 'es' o 'en'.`);
    }
  };

  /**
   * Alternar entre español e inglés
   */
  const toggleLanguage = () => {
    setLanguage(prev => prev === 'es' ? 'en' : 'es');
  };

  // Valor del contexto que se provee a todos los componentes hijos
  const value = {
    language,
    changeLanguage,
    toggleLanguage
  };

  return (
    <LanguageContext.Provider value={value}>
      {children}
    </LanguageContext.Provider>
  );
};
