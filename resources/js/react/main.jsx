import { createRoot } from "react-dom/client";
import { createBrowserRouter, RouterProvider } from "react-router-dom";
import "./index.css";
import "./styles/accessibility-clean.css"; // Estilos limpios de accesibilidad
import App from "./App.jsx";
import ContactPage from "./contact/Contacto.jsx";
import PlacesPage from "./places/page.jsx";
import CommentsPage from "./comments/page.jsx";
import Loginpage from "./login/page.jsx";
import PagLogueados from "./pagLogueados.jsx";
import Comments2Page from "./comments2/page.jsx";
import CookiesPage from "./legal/Cookies.jsx";
import TerminosDeUsoPage from "./legal/TerminosDeUso.jsx";
import PoliticaDePrivacidadPage from "./legal/PoliticaDePrivacidad.jsx";
import CopyrightTotal from "./legal/CopyrightTotal.jsx";

// NUEVAS PÁGINAS
import ParaisosAcuaticos from "./places2/paraisosAcuaticos/page.jsx";
import LugaresMontanosos from "./places2/lugaresMontanosos/page.jsx";
import ParquesYMas from "./places2/parquesYMas/page.jsx";
import TerritoriosDelCafe from "./places2/territoriosDelCafe/page.jsx";
import PlaceDetailPage from "./places/detail/page.jsx";
import CompanyDashboard from "./admin/CompanyDashboard.jsx";
import AdminPanel from "./admin/AdminPanel.jsx";
import EcohotelsPage from "./ecohotels/page.jsx";
import EcohotelDetailPage from "./ecohotels/detail/page.jsx";
import MapPage from "./map/page.jsx";

// CONTEXTOS DE ACCESIBILIDAD E IDIOMA
import { AccessibilityProvider } from "./contexts/AccessibilityContext.jsx";
import { LanguageProvider } from "./contexts/LanguageContext.jsx";

// COMPONENTE DE PANEL DE ACCESIBILIDAD
import AccessibilityPanel from "./components/AccessibilityPanel/AccessibilityPanel.jsx";
import TranslationHelper from "./components/TranslationHelper/TranslationHelper.jsx";

console.log('🔥🔥🔥 MAIN.JSX CARGADO - Router creándose - VERSION 2.0 🔥🔥🔥');
console.log('Rutas disponibles: /', '/lugares', '/ecohoteles', '/admin');

const router = createBrowserRouter([
  {
    path: "/",
    element: <App />,
  },
  {
    path: "/contact",
    element: <ContactPage />,
  },
  {
    path: "/contacto",
    element: <ContactPage />,
  },
  {
    path: "/contact2",
    element: <ContactPage />,
  },
  {
    path: "/contacto2",
    element: <ContactPage />,
  },
  {
    path: "/lugares/:id",
    element: <PlaceDetailPage />,
  },
  {
    path: "/lugares",
    element: <PlacesPage />,
  },
  {
    path: "/places",
    element: <PlacesPage />,
  },
  {
    path: "/ecohoteles",
    element: <EcohotelsPage />,
  },
  {
    path: "/ecohoteles/:id",
    element: <EcohotelDetailPage />,
  },
  {
    path: "/mapa",
    element: <MapPage />,
  },
  {
    path: "/comments",
    element: <CommentsPage />,
  },
  {
    path: "/comentarios",
    element: <CommentsPage />,
  },
  {
    path: "/comments2",
    element: <Comments2Page />,
  },
  {
    path: "/comentarios2",
    element: <Comments2Page />,
  },
  {
    path: "/login",
    element: <Loginpage />,
  },
  {
    path: "/pagLogueados",
    element: <PagLogueados />,
  },
  {
    path: "/company/dashboard",
    element: <CompanyDashboard />,
  },
  {
    path: "/admin",
    element: <AdminPanel />,
  },

  // 🌿 RUTAS DEL MENÚ LUGARES
  {
    path: "/paraisosAcuaticos",
    element: <ParaisosAcuaticos />,
  },
  {
    path: "/lugaresMontanosos",
    element: <LugaresMontanosos />,
  },
  {
    path: "/parquesYMas",
    element: <ParquesYMas />,
  },
  {
    path: "/territoriosDelCafe",
    element: <TerritoriosDelCafe />,
  },
  {
    path: "/cookies",
    element: <CookiesPage />,
  },
  {
    path: "/terminos-de-uso",
    element: <TerminosDeUsoPage />,
  },
  {
    path: "/politica-de-privacidad",
    element: <PoliticaDePrivacidadPage />,
  },
  {
    path: "/CopyrightTotal",
    element: <CopyrightTotal />,
  },
]);

createRoot(document.getElementById("root")).render(
  <AccessibilityProvider>
    <LanguageProvider>
      <RouterProvider router={router} />
      <AccessibilityPanel />
      <TranslationHelper />
    </LanguageProvider>
  </AccessibilityProvider>
);
