import { useLanguage } from '../contexts/LanguageContext';
import { getTranslation } from './translations';

/**
 * Hook personalizado para usar traducciones
 * 
 * Uso:
 * const { t, language } = useTranslation();
 * <h1>{t('welcome')}</h1>
 * 
 * @returns {Object} - Objeto con función t() y language actual
 */
export const useTranslation = () => {
  const { language } = useLanguage();

  /**
   * Función de traducción
   * @param {string} key - Clave de traducción
   * @returns {string} - Texto traducido
   */
  const t = (key) => {
    return getTranslation(key, language);
  };

  return { t, language };
};
