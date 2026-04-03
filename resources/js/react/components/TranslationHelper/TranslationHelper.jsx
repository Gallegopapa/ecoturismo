import { useEffect } from 'react';
import { useLanguage } from '../../contexts/LanguageContext';
import { translations } from '../../i18n/translations';

/**
 * TranslationHelper
 * 
 * Componente que traduce elementos genéricos de la página
 * de forma automática sin necesidad de actualizar cada componente.
 */
const TranslationHelper = () => {
  const { language } = useLanguage();
  const trans = translations[language] || translations.es;
  const esTranslations = translations.es;

  useEffect(() => {
    // 1. Traducir elementos con atributos data-i18n (textContent)
    document.querySelectorAll('[data-i18n]').forEach(element => {
      const key = element.getAttribute('data-i18n');
      const translation = trans[key];
      if (translation) {
        element.textContent = translation;
      }
    });

    // 2. Traducir titles
    document.querySelectorAll('[data-i18n-title]').forEach(element => {
      const key = element.getAttribute('data-i18n-title');
      const translation = trans[key];
      if (translation) {
        element.title = translation;
      }
    });

    // 3. Traducir placeholders
    document.querySelectorAll('[data-i18n-placeholder]').forEach(element => {
      const key = element.getAttribute('data-i18n-placeholder');
      const translation = trans[key];
      if (translation) {
        element.placeholder = translation;
      }
    });

    // 4. Traducir alt en imágenes
    document.querySelectorAll('[data-i18n-alt]').forEach(element => {
      const key = element.getAttribute('data-i18n-alt');
      const translation = trans[key];
      if (translation) {
        element.alt = translation;
      }
    });

    // 5. Traducir aria-label
    document.querySelectorAll('[data-i18n-aria-label]').forEach(element => {
      const key = element.getAttribute('data-i18n-aria-label');
      const translation = trans[key];
      if (translation) {
        element.setAttribute('aria-label', translation);
      }
    });

    // 6. Traducir automáticamente elementos con textos conocidos
    // Si encuentra elementos cuyo texto está en las traducciones españolas,
    // los traduce automáticamente
    translateKnownTexts();

    function translateKnownTexts() {
      // Mapear textos españoles a sus claves de traducción
      const textMap = {
        'Inicio': 'home',
        'Lugares': 'places',
        'Ecohoteles': 'ecohotels',
        'Nosotros': 'about',
        'Contacto': 'contact',
        'Iniciar sesión': 'login',
        'Registrarse': 'register',
        'Cerrar sesión': 'logout',
        'Bienvenido': 'welcome',
        'Cargando...': 'loading',
        'Error': 'error',
        'Éxito': 'success',
        'Guardar': 'save',
        'Cancelar': 'cancel',
        'Confirmar': 'confirm',
        'Eliminar': 'delete',
        'Editar': 'edit',
        'Buscar': 'search',
        'Filtrar': 'filter',
      };

      // Buscar todos los elementos de texto
      document.querySelectorAll('*').forEach(element => {
        // Solo procesar elementos de texto que no sean especiales
        if (element.children.length === 0 && element.textContent.trim()) {
          const text = element.textContent.trim();
          
          // Verificar si este texto está en el mapeo
          if (textMap[text]) {
            const key = textMap[text];
            const translation = trans[key];
            
            if (translation && translation !== text) {
              // Solo cambiar si es diferente y no es el panel de accesibilidad
              if (!element.closest('.accessibility-panel')) {
                element.textContent = translation;
              }
            }
          }
        }
      });
    }

  }, [language, trans]);

  return null;
};

export default TranslationHelper;
