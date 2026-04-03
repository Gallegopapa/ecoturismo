import { Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

export const ProtectedRoute = ({ children, requireAdmin = false, requireCompany = false }) => {
  const { isAuthenticated, loading, isAdmin, user } = useAuth();

  if (loading) {
    return (
      <div style={{ 
        display: 'flex', 
        justifyContent: 'center', 
        alignItems: 'center', 
        height: '100vh' 
      }}>
        <p>Cargando...</p>
      </div>
    );
  }

  if (!isAuthenticated) {
    return <Navigate to="/login" replace />;
  }

  if (requireAdmin && !isAdmin) {
    console.log('Usuario no es admin:', { user, isAdmin, requireAdmin });
    return <Navigate to="/pagLogueados" replace />;
  }

  if (requireCompany && user?.tipo_usuario !== 'empresa') {
    console.log('Usuario no es empresa:', { user, requireCompany });
    return <Navigate to="/pagLogueados" replace />;
  }

  return children;
};

