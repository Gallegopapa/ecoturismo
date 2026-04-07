import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import PlacesAdmin from './PlacesAdmin';
import EcohotelsAdmin from './EcohotelsAdmin';
import UsersAdmin from './UsersAdmin';
import ReservationsAdmin from './ReservationsAdmin';
import Header2 from '../components/Header2/Header2';
import Footer from '../components/Footer/Footer';
import './admin.css';

const AdminPanel = () => {
  const [activeTab, setActiveTab] = useState('places');

  return (
    <>
      <Header2 />
      <div className="admin-container">
        <div className="admin-header">
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <h1>Panel ADMIN</h1>
            <Link to="/lugares" className="btn-back-home">
              Volver a Inicio
            </Link>
          </div>
        </div>

        <div className="admin-tabs">
          <button
            className={activeTab === 'places' ? 'active' : ''}
            onClick={() => setActiveTab('places')}
          >
            Lugares
          </button>
          <button
            className={activeTab === 'ecohotels' ? 'active' : ''}
            onClick={() => setActiveTab('ecohotels')}
          >
            Ecohoteles
          </button>
          <button
            className={activeTab === 'users' ? 'active' : ''}
            onClick={() => setActiveTab('users')}
          >
            Usuarios
          </button>
          <button
            className={activeTab === 'reservations' ? 'active' : ''}
            onClick={() => setActiveTab('reservations')}
          >
            Reservas
          </button>
        </div>

        <div className="admin-content">
          {activeTab === 'places' && <PlacesAdmin />}
          {activeTab === 'ecohotels' && <EcohotelsAdmin />}
          {activeTab === 'users' && <UsersAdmin />}
          {activeTab === 'reservations' && <ReservationsAdmin />}
        </div>
      </div>
      <Footer />
    </>
  );
};

export default AdminPanel;
