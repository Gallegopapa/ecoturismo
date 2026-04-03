import { createRoot } from "react-dom/client";
import { createBrowserRouter, RouterProvider } from "react-router-dom";
import "./index.css";
import "./styles/accessibility-clean.css"; // Estilos limpios de accesibilidad
import App from "./App.jsx";
import Loginpage from "./login/page.jsx";
import PagLogueados from "./pagLogueados.jsx";

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
    path: "/pagLogueados",
    element: <PagLogueados />,
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

