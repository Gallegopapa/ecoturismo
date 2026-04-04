import { createRoot } from "react-dom/client";
import { createBrowserRouter, RouterProvider } from "react-router-dom";
import "./index.css";
import "./styles/accessibility-clean.css"; // Estilos limpios de accesibilidad
import App from "./App.jsx";
import Loginpage from "./login/page.jsx";
import PagLogueados from "./pagLogueados.jsx";
import ForgotPasswordPage from "./forgot-password/page.jsx";
import ForgotPasswordSentPage from "./forgot-password/sent.jsx";
import ResetPasswordPage from "./reset-password/page.jsx";
import PerfilPage from "./perfil/page.jsx";
import CompanyDashboard from "./company/dashboard/page.jsx";
import EditPlace from "./company/places/EditPlace.jsx";
import ManageSchedules from "./company/schedules/ManageSchedules.jsx";
import ManageReservations from "./company/reservations/ManageReservations.jsx";
// CONTEXTOS DE ACCESIBILIDAD E IDIOMA
import { AccessibilityProvider } from "./contexts/AccessibilityContext.jsx";
import { LanguageProvider } from "./contexts/LanguageContext.jsx";

// COMPONENTE DE PANEL DE ACCESIBILIDAD
import AccessibilityPanel from "./components/AccessibilityPanel/AccessibilityPanel.jsx";
import TranslationHelper from "./components/TranslationHelper/TranslationHelper.jsx";

const router = createBrowserRouter([
  {
    path: "/",
    element: <App />,
  },
  {
    path: "/login",
    element: <Loginpage />,
  },
  {
    path: "/registro",
    element: <Loginpage />,
  },
  {
    path: "/pagLogueados",
    element: <PagLogueados />,
  },
  {
    path: "/forgot-password",
    element: <ForgotPasswordPage />,
  },
  {
    path: "/forgot-password/sent",
    element: <ForgotPasswordSentPage />,
  },
  {
    path: "/reset-password",
    element: <ResetPasswordPage />,
  },
  {
    path: "/perfil",
    element: <PerfilPage />,
  },
  {
    path: "/company/dashboard",
    element: <CompanyDashboard />,
  },
  {
    path: "/company/places/:id/edit",
    element: <EditPlace />,
  },
  {
    path: "/company/places/:id/schedules",
    element: <ManageSchedules />,
  },
  {
    path: "/company/reservations",
    element: <ManageReservations />,
  }
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

