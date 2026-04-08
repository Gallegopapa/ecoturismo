import './bootstrap';
import { createRoot } from 'react-dom/client';
import { createBrowserRouter, RouterProvider } from 'react-router-dom';
import './react/index.css';
import './react/styles/accessibility-clean.css';

// Importar AuthProvider
import { AuthProvider } from './react/context/AuthContext';
import { ProtectedRoute } from './react/components/ProtectedRoute';
import ErrorBoundary from './react/components/ErrorBoundary';

// CONTEXTOS DE ACCESIBILIDAD E IDIOMA
import { AccessibilityProvider } from './react/contexts/AccessibilityContext.jsx';
import { LanguageProvider } from './react/contexts/LanguageContext.jsx';

// Importar componentes
import App from './react/App.jsx';
import ContactPage from './react/contact/Contacto.jsx';
import PlacesPage from './react/places/page.jsx';
import CommentsPage from './react/comments/page.jsx';
import Loginpage from './react/login/page.jsx';
import ForgotPasswordPage from './react/forgot-password/page.jsx';
import ForgotPasswordSentPage from './react/forgot-password/sent.jsx';
import ResetPasswordPage from './react/reset-password/page.jsx';
import PagLogueados from './react/pagLogueados.jsx';
import Comments2Page from './react/comments2/page.jsx';
import PerfilPage from './react/perfil/page.jsx';
import ReservationsPage from './react/reservations/page.jsx';
import FavoritesPage from './react/favorites/page.jsx';
import MapPage from './react/map/page.jsx';
import PlaceDetailPage from './react/places/detail/page.jsx';
// import SettingsPage from './react/settings/Page.jsx';
// Páginas legales
import CookiesPage from './react/legal/Cookies.jsx';
import TerminosDeUsoPage from './react/legal/TerminosDeUso.jsx';
import PoliticaDePrivacidadPage from './react/legal/PoliticaDePrivacidad.jsx';
import CopyrightTotal from './react/legal/CopyrightTotal.jsx';

// Páginas de lugares
import ParaisosAcuaticos from './react/places2/paraisosAcuaticos/page.jsx';
import LugaresMontanosos from './react/places2/lugaresMontanosos/page.jsx';
import ParquesYMas from './react/places2/parquesYMas/page.jsx';
import TerritoriosDelCafe from './react/places2/territoriosDelCafe/page.jsx';

// Panel de administración
import AdminPanel from './react/admin/AdminPanel.jsx';
import CompanyDashboard from './react/admin/CompanyDashboard.jsx';

// Ecohoteles
import EcohotelsPage from './react/ecohotels/page.jsx';
import EcohotelDetailPage from './react/ecohotels/detail/page.jsx';

// FAQ
import FAQPage from './react/faq/page.jsx';

// Chatbot eliminado

// COMPONENTE DE PANEL DE ACCESIBILIDAD
import AccessibilityPanel from './react/components/AccessibilityPanel/AccessibilityPanel.jsx';

// Crear router
const router = createBrowserRouter([
  {
    path: '/',
    element: <App />,
  },
  {
    path: '/cookies',
    element: <CookiesPage />,
  },
  {
    path: '/terminos-de-uso',
    element: <TerminosDeUsoPage />,
  },
  {
    path: '/politica-de-privacidad',
    element: <PoliticaDePrivacidadPage />,
  },
  {
    path: '/CopyrightTotal',
    element: <CopyrightTotal />,
  },
  {
    path: '/contacto',
    element: <ContactPage />,
  },
  {
    path: '/contact',
    element: <ContactPage />,
  },
  {
    path: '/contacto2',
    element: <ContactPage />,
  },
  {
    path: '/preguntas-frecuentes',
    element: <FAQPage />,
  },
  {
    path: '/faq',
    element: <FAQPage />,
  },
  {
    path: '/lugares',
    element: <PlacesPage />,
  },
  {
    path: '/lugares/:id',
    element: <PlaceDetailPage />,
  },
  {
    path: '/ecohoteles',
    element: <EcohotelsPage />,
  },
  {
    path: '/ecohoteles/:id',
    element: <EcohotelDetailPage />,
  },
  {
    path: '/mapa',
    element: <MapPage />,
  },
  {
    path: '/comentarios',
    element: <CommentsPage />,
  },
  {
    path: '/comentarios2',
    element: <Comments2Page />,
  },
  {
    path: '/login',
    element: <Loginpage />,
  },
  {
    path: '/registro',
    element: <Loginpage />, // Usar el mismo componente o crear uno separado
  },
  {
    path: '/forgot-password',
    element: <ForgotPasswordPage />,
  },
  {
    path: '/forgot-password/sent',
    element: <ForgotPasswordSentPage />,
  },
  {
    path: '/reset-password',
    element: <ResetPasswordPage />,
  },
  {
    path: '/pagLogueados',
    element: (
      <ProtectedRoute>
        <PagLogueados />
      </ProtectedRoute>
    ),
  },
  {
    path: '/configuracion',
    element: (
      <ProtectedRoute>
        <PerfilPage />
      </ProtectedRoute>
    ),
  },
  {
    path: '/perfil',
    element: (
      <ProtectedRoute>
        <PerfilPage />
      </ProtectedRoute>
    ),
  },
  {
    path: '/reservas',
    element: (
      <ProtectedRoute>
        <ReservationsPage />
      </ProtectedRoute>
    ),
  },
  {
    path: '/favoritos',
    element: (
      <ProtectedRoute>
        <FavoritesPage />
      </ProtectedRoute>
    ),
  },
  // Rutas de lugares
  {
    path: '/paraisosAcuaticos',
    element: <ParaisosAcuaticos />,
  },
  {
    path: '/lugaresMontanosos',
    element: <LugaresMontanosos />,
  },
  {
    path: '/parquesYMas',
    element: <ParquesYMas />,
  },
  {
    path: '/territoriosDelCafe',
    element: <TerritoriosDelCafe />,
  },
  {
    path: '/admin/panel',
    element: (
      <ProtectedRoute requireAdmin={true}>
        <AdminPanel />
      </ProtectedRoute>
    ),
  },
  {
    path: '/company/dashboard',
    element: (
      <ProtectedRoute requireCompany={true}>
        <CompanyDashboard />
      </ProtectedRoute>
    ),
  },

  // Fallback para rutas no definidas
  {
    path: '*',
    element: <App />,
  },
]);

// Montar la aplicación React
const rootElement = document.getElementById('root');
if (rootElement) {
  createRoot(rootElement).render(
    <ErrorBoundary>
      <AccessibilityProvider>
        <LanguageProvider>
          <AuthProvider>
            <>
              <RouterProvider router={router} />
              <AccessibilityPanel />
            </>
          </AuthProvider>
        </LanguageProvider>
      </AccessibilityProvider>
    </ErrorBoundary>
  );
} else {
  console.error('No se encontró el elemento #root');
}
