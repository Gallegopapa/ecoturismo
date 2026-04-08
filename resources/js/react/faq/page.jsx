import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import Header from '../components/Header/Header';
import Header2 from '../components/Header2/Header2';
import Footer from '../components/Footer/Footer';
import { faqs } from '../data/faqs';
import './faq.css';

const FAQPage = () => {
  const { isAuthenticated } = useAuth();
  const [expandedId, setExpandedId] = useState(null);
  const [selectedCategory, setSelectedCategory] = useState(null);

  const categories = [
    { id: 'plataforma', name: 'Plataforma', label: 'Preguntas sobre la plataforma' },
    { id: 'lugares', name: 'Lugares', label: 'Preguntas sobre lugares y experiencias' },
    { id: 'ecohoteles', name: 'Ecohoteles', label: 'Preguntas sobre ecohoteles' },
    { id: 'reservas', name: 'Reservas', label: 'Preguntas sobre reservas y pagos' }
  ];

  const filteredFaqs = selectedCategory
    ? faqs.filter(faq => faq.category === selectedCategory)
    : faqs;

  const toggleExpand = (id) => {
    setExpandedId(expandedId === id ? null : id);
  };

  return (
    <div className="page-layout">
      {isAuthenticated ? <Header2 /> : <Header />}

      <main className="faq-page">
        <div className="faq-container">
          <div className="faq-header">
            <h1>Preguntas Frecuentes</h1>
            <p>Encuentra respuestas a las preguntas más comunes sobre nuestra plataforma, lugares, ecohoteles y reservas.</p>
          </div>

          <div className="faq-categories-section">
            <h2>Categorías</h2>
            <div className="faq-categories-grid">
              <button
                className={`category-card ${!selectedCategory ? 'active' : ''}`}
                onClick={() => {
                  setSelectedCategory(null);
                  setExpandedId(null);
                }}
                type="button"
              >
                <span className="category-icon">📋</span>
                <span className="category-text">Todas las preguntas</span>
              </button>
              {categories.map(cat => (
                <button
                  key={cat.id}
                  className={`category-card ${selectedCategory === cat.id ? 'active' : ''}`}
                  onClick={() => {
                    setSelectedCategory(cat.id);
                    setExpandedId(null);
                  }}
                  type="button"
                >
                  <span className="category-icon">
                    {cat.id === 'plataforma' && '💻'}
                    {cat.id === 'lugares' && '🏞️'}
                    {cat.id === 'ecohoteles' && '🏨'}
                    {cat.id === 'reservas' && '💳'}
                  </span>
                  <span className="category-text">{cat.name}</span>
                </button>
              ))}
            </div>
          </div>

          <div className="faq-list-section">
            {selectedCategory && categories.find(c => c.id === selectedCategory) && (
              <h2>{categories.find(c => c.id === selectedCategory).label}</h2>
            )}

            <div className="faq-list">
              {filteredFaqs.length > 0 ? (
                filteredFaqs.map(faq => (
                  <div key={faq.id} className="faq-item-page">
                    <button
                      className={`faq-question-page ${expandedId === faq.id ? 'expanded' : ''}`}
                      onClick={() => toggleExpand(faq.id)}
                      type="button"
                    >
                      <span className="question-text">{faq.question}</span>
                      <span className="toggle-icon-page">▼</span>
                    </button>
                    {expandedId === faq.id && (
                      <div className="faq-answer-page">
                        <p>{faq.answer}</p>
                      </div>
                    )}
                  </div>
                ))
              ) : (
                <p className="no-results">No hay preguntas en esta categoría.</p>
              )}
            </div>
          </div>

          <div className="faq-contact-section">
            <h2>¿No encontraste lo que buscabas?</h2>
            <p>Si no encontraste la respuesta que buscabas, puedes contactarnos directamente a través del formulario de contacto.</p>
            <a href="/contacto" className="btn-contact-faq">
              Ir al formulario de contacto
            </a>
          </div>
        </div>
      </main>

      <Footer />
    </div>
  );
};

export default FAQPage;
