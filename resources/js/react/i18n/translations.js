/**
 * Archivo de Traducciones
 * 
 * Sistema simple de internacionalización (i18n) sin librerías externas.
 * 
 * Estructura:
 * - Objeto con claves de idioma ('es', 'en')
 * - Cada idioma contiene un objeto con traducciones
 * - Las claves son nombres descriptivos en camelCase
 * 
 * Uso:
 * import { useTranslation } from './useTranslation';
 * const { t } = useTranslation();
 * <h1>{t('welcome')}</h1>
 */

export const translations = {
  es: {
    // Panel de Accesibilidad
    accessibility: 'Accesibilidad',
    accessibilityOptions: 'Opciones de Accesibilidad',
    increaseFontSize: 'Aumentar tamaño de texto',
    decreaseFontSize: 'Disminuir tamaño de texto',
    highContrast: 'Alto contraste',
    underlineLinks: 'Subrayar enlaces',
    grayscale: 'Escala de grises',
    lineSpacing: 'Espaciado de líneas',
    increaseLineSpacing: 'Aumentar espaciado de líneas',
    decreaseLineSpacing: 'Disminuir espaciado de líneas',
    reduceMotion: 'Reducir movimiento',
    resetSettings: 'Restablecer configuración',
    language: 'Idioma',
    selectLanguage: 'Seleccionar idioma',
    spanish: 'Español',
    english: 'Inglés',
    closePanel: 'Cerrar panel',
    accessibilityPanel: 'Panel de accesibilidad',
    openAccessibilityPanel: 'Abrir panel de accesibilidad',
    
    // Estados de configuración
    active: 'Activado',
    inactive: 'Desactivado',
    fontSizeNormal: 'Normal',
    fontSizeLarge: 'Grande',
    fontSizeExtraLarge: 'Extra grande',
    lineSpacingNormal: 'Normal',
    lineSpacingRelaxed: 'Relajado',
    lineSpacingVeryRelaxed: 'Muy relajado',
    
    // Navegación general
    home: 'Inicio',
    places: 'Lugares',
    ecohotels: 'Ecohoteles',
    reviews: 'Reseñas',
    about: 'Nosotros',
    contact: 'Contacto',
    login: 'Iniciar sesión',
    register: 'Registrarse',
    logout: 'Cerrar sesión',
    
    // Mensajes comunes
    welcome: 'Bienvenido',
    loading: 'Cargando...',
    error: 'Error',
    success: 'Éxito',
    save: 'Guardar',
    cancel: 'Cancelar',
    confirm: 'Confirmar',
    delete: 'Eliminar',
    edit: 'Editar',
    search: 'Buscar',
    filter: 'Filtrar',
    
    // Accesibilidad - descripciones
    fontSizeDescription: 'Ajusta el tamaño del texto para facilitar la lectura',
    highContrastDescription: 'Aumenta el contraste de colores para mejor visibilidad',
    underlineLinksDescription: 'Subraya todos los enlaces para identificarlos fácilmente',
    grayscaleDescription: 'Convierte la interfaz a escala de grises',
    lineSpacingDescription: 'Aumenta el espacio entre líneas de texto',
    reduceMotionDescription: 'Reduce animaciones y transiciones para evitar mareos',
    resetDescription: 'Restaura todas las configuraciones a valores predeterminados',
    languageDescription: 'Cambia el idioma de la interfaz',
    
    // Lugares específicos
    wateryPlaces: 'Lugares Acuáticos',
    mountainPlaces: 'Lugares Montañosos',
    parksAndMore: 'Parques y Más',
    allPlaces: 'Todos los Lugares',
    myProfile: 'Mi Perfil',
    myDashboard: 'Mi Dashboard',
    companyPanel: 'Panel de Empresa',
    myAccount: 'Mi Cuenta',
    logOut: 'Cerrar Sesión',
    myReservations: 'Mis Reservas',
    myFavorites: 'Mis Favoritos',
    adminPanel: 'Panel de Administrador',
    
    // Footer
    cookies: 'Cookies',
    termsOfUse: 'Términos de uso',
    privacyPolicy: 'Política de privacidad',
    copyright: 'Copyright-EcoTurismo',
  },
  
  en: {
    // Accessibility Panel
    accessibility: 'Accessibility',
    accessibilityOptions: 'Accessibility Options',
    increaseFontSize: 'Increase text size',
    decreaseFontSize: 'Decrease text size',
    highContrast: 'High contrast',
    underlineLinks: 'Underline links',
    grayscale: 'Grayscale',
    lineSpacing: 'Line spacing',
    increaseLineSpacing: 'Increase line spacing',
    decreaseLineSpacing: 'Decrease line spacing',
    reduceMotion: 'Reduce motion',
    resetSettings: 'Reset settings',
    language: 'Language',
    selectLanguage: 'Select language',
    spanish: 'Spanish',
    english: 'English',
    closePanel: 'Close panel',
    accessibilityPanel: 'Accessibility panel',
    openAccessibilityPanel: 'Open accessibility panel',
    
    // Configuration states
    active: 'Active',
    inactive: 'Inactive',
    fontSizeNormal: 'Normal',
    fontSizeLarge: 'Large',
    fontSizeExtraLarge: 'Extra large',
    lineSpacingNormal: 'Normal',
    lineSpacingRelaxed: 'Relaxed',
    lineSpacingVeryRelaxed: 'Very relaxed',
    
    // General navigation
    home: 'Home',
    places: 'Places',
    ecohotels: 'Ecohotels',
    reviews: 'Reviews',
    about: 'About',
    contact: 'Contact',
    login: 'Log in',
    register: 'Sign up',
    logout: 'Log out',
    
    // Common messages
    welcome: 'Welcome',
    loading: 'Loading...',
    error: 'Error',
    success: 'Success',
    save: 'Save',
    cancel: 'Cancel',
    confirm: 'Confirm',
    delete: 'Delete',
    edit: 'Edit',
    search: 'Search',
    filter: 'Filter',
    
    // Accessibility - descriptions
    fontSizeDescription: 'Adjust text size for easier reading',
    highContrastDescription: 'Increase color contrast for better visibility',
    underlineLinksDescription: 'Underline all links for easy identification',
    grayscaleDescription: 'Convert interface to grayscale',
    lineSpacingDescription: 'Increase space between text lines',
    reduceMotionDescription: 'Reduce animations and transitions to prevent dizziness',
    resetDescription: 'Restore all settings to default values',
    languageDescription: 'Change interface language',
    
    // Specific places
    wateryPlaces: 'Watery Places',
    mountainPlaces: 'Mountain Places',
    parksAndMore: 'Parks and More',
    allPlaces: 'All Places',
    myProfile: 'My Profile',
    myDashboard: 'My Dashboard',
    companyPanel: 'Company Panel',
    myAccount: 'My Account',
    logOut: 'Log Out',
    myReservations: 'My Reservations',
    myFavorites: 'My Favorites',
    adminPanel: 'Admin Panel',
    
    // Footer
    cookies: 'Cookies',
    termsOfUse: 'Terms of use',
    privacyPolicy: 'Privacy policy',
    copyright: 'Copyright-EcoTurismo',
  }
};

/**
 * Obtener traducción por clave
 * @param {string} key - Clave de traducción
 * @param {string} language - Idioma ('es' o 'en')
 * @returns {string} - Texto traducido
 */
export const getTranslation = (key, language) => {
  const translation = translations[language]?.[key];
  
  if (!translation) {
    console.warn(`Traducción no encontrada: ${key} (${language})`);
    return key; // Retorna la clave si no encuentra traducción
  }
  
  return translation;
};
