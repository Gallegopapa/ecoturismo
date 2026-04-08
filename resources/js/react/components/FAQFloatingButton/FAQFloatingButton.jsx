import { useState } from 'react';
import './FAQFloatingButton.css';
import { faqs } from '../../data/faqs';

export default function FAQFloatingButton() {
  const [isOpen, setIsOpen] = useState(false);
  const [expandedId, setExpandedId] = useState(null);

  const togglePanel = () => setIsOpen(!isOpen);
  const toggleExpand = (id) => setExpandedId(expandedId === id ? null : id);

  return (
    <>
      <button
        className="faq-floating-button"
        onClick={togglePanel}
        aria-label="Preguntas frecuentes"
        title="Preguntas frecuentes"
      >
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
          <circle cx="12" cy="12" r="10" stroke="currentColor" fill="#f1c40f" />
          <text x="8" y="17" fontSize="14" fontWeight="bold" fill="#34495e">?</text>
        </svg>
      </button>
      {isOpen && (
        <aside className="faq-floating-panel">
          <div className="faq-panel-header">
            <h2>Preguntas Frecuentes</h2>
            <button className="close-panel-btn" onClick={togglePanel} aria-label="Cerrar FAQ">×</button>
          </div>
          <div className="faq-list">
            {faqs.map(faq => (
              <div key={faq.id} className="faq-item">
                <button
                  className={`faq-question ${expandedId === faq.id ? 'expanded' : ''}`}
                  onClick={() => toggleExpand(faq.id)}
                  type="button"
                >
                  <span>{faq.question}</span>
                  <span className="toggle-icon">▼</span>
                </button>
                {expandedId === faq.id && (
                  <div className="faq-answer">
                    <p>{faq.answer}</p>
                  </div>
                )}
              </div>
            ))}
          </div>
        </aside>
      )}
    </>
  );
}
