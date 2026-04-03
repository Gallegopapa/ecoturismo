// import React from "react";

const Card = ({urlImg, title, description}) => {
  return (
    <>
        <div className="card">
          <img src={urlImg} alt="Card 1" />
          <p id="bold">{title}</p>
          <p>{description}</p>
        </div>
    </>
  )
};

export default Card;
