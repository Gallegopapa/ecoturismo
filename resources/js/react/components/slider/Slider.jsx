import React from 'react'

const Slider = () => {
  return (
    <>
        <div className="slider-container">
          <div className="slider">
            <div className="slide">
              <img src="/imagenes/slideone.jpg" alt="imagen 1" />
            </div>
            <div className="slide">
              <img src="/imagenes/slidetwo.jpg" alt="imagen 2" />
            </div>
            <div className="slide">
              <img src="/imagenes/nudo4.jpg" alt="imagen 3" />
            </div>
          </div>

          <div className="intro-text">
            <h1>Bienvenidos a RisaraldaEcoTurismo</h1>
            <h3>Encuentre en un solo lugar información detallada sobre los atractivos ecoturísticos de Risaralda, clasificados por temática y con recursos útiles para planificar su visita.</h3>
          </div>
        </div>
    </>
  )
}

export default Slider