import React from "react";
import Header from "@/react/components/Header/Header";
import Header2 from "@/react/components/Header2/Header2";
import Footer from "@/react/components/Footer/Footer";
import { useAuth } from "@/react/context/AuthContext";
import "./LegalPage.css";

export default function CopyrightTotal() {
    const { isAuthenticated, loading } = useAuth();

    React.useEffect(() => {
        window.scrollTo({ top: 0, behavior: "smooth" });
    }, []);

    const items = {
        lugaresMontanosos: [
            {
                title: "Alto del Nudo",
                url: "https://www.tripadvisor.com/",
            },
            {
                title: "Alto del Toro",
                url: "https://www.tripadvisor.com/",
            },
            {
                title: "Cerro Batero",
                url: "https://www.tripadvisor.com/",
            },
            {
                title: "Kuakita Bosque Reserva",
                url: "https://www.tripadvisor.com/",
            },
            {
                title: "Divisa de Don Juan",
                url: "https://www.tripadvisor.com/",
            },
            {
                title: "Reserva Forestal La Nona",
                url: "https://www.tripadvisor.com/",
            },
            {
                title: "Reserva Natural Cerro Gobia",
                url: "https://www.tripadvisor.com/",
            },
            {
                title: "Reserva Natural DMI Agualinda",
                url: "https://www.tripadvisor.com/",
            },
            {
                title: "Piedras Marcadas",
                url: "https://www.colparques.net/",
            },
            {
                title: "Cascada Lagrimas Del Indio",
                url: "https://es.wikiloc.com/",
            },
            {
                title: "Santuario Otún Quimbaya",
                url: "https://old.parquesnacionales.gov.co/",
            },
            {
                title: "Barbas Bremen",
                url: "https://www.ruraladventure.co/",
            },
        ],
        paraisosAcuaticos: [
            {
                title: "Farallones",
                url: "https://www.tripadvisor.com/",
            },
            {
                title: "Chorros de don Lolo",
                url: "https://www.tripadvisor.com/",
            },
            {
                title: "Consota",
                url: "https://comfamiliar.com/parque-consota/",
            },
            {
                title: "Laguna del Otun",
                url: "https://www.parquesnacionales.gov.co/",
            },
            {
                title: "Lago de la Pradera",
                url: "https://caracol.com.co/",
            },
            {
                title: "Rio San Jose",
                url: "https://territorioexplora.com.co/",
            },
            {
                title: "Termales de Santa Rosa",
                url: "https://www.booking.com/",
            },
            {
                title: "Cascada Lagrimas Del Indio",
                url: "https://es.wikiloc.com/",
            },
            {
                title: "Termales de San Vicente",
                url: "https://www.parquesdeleje.com/",
            },
        ],
        parquesYMas: [
            {
                title: "Jardín Botánico Marsella",
                url: "https://rutasdelpaisajeculturalcafetero.com/",
            },
            {
                title: "Jardín Botánico UTP",
                url: "https://jardinbotanico.utp.edu.co/",
            },
            {
                title: "Parque Las Araucarias",
                url: "https://camarasantarosa.org/",
            },
            {
                title: "Parque Nacional Natural Tatamá",
                url: "https://www.parquesnacionales.gov.co/",
            },
            {
                title: "Parque Natural Regional Santa Emilia",
                url: "https://www.parquesnacionales.gov.co/",
            },
            {
                title: "Parque Regional Natural Cuchilla de San Juan",
                url: "https://www.parquesnacionales.gov.co/",
            },
            {
                title: "Parque Bioflora en Finca Turistica Los Rosales",
                url: "https://co.hoteles.com/",
            },
            {
                title: "Bioparque Mariposario Bonita Farm",
                url: "https://www.recreatur.co/",
            },
        ],
        ecohoteles: [
            {
                title: "EcoHotel Paraíso Real",
                url: "https://eco-paraiso-real.hotelesejecafetero.net/",
            },
            {
                title: "Finca Catedral Santa Rosa De Cabal RDA",
                url: "https://mapcarta.com/",
            },
        ],
    };

    const sections = [
        { key: "lugaresMontanosos", title: "Copyright Lugares Montañosos" },
        { key: "paraisosAcuaticos", title: "Copyright Paraisos Acuaticos" },
        { key: "parquesYMas", title: "Copyright Parques y Más" },
        { key: "ecohoteles", title: "Copyright Ecohoteles" },
    ];

    return (
        <div className="page-layout">
            {isAuthenticated && !loading ? <Header2 /> : <Header />}
            <div
                className="page-content contenedorTodo"
                style={{ marginTop: "100px" }}
            >
                <h1
                    style={{
                        fontSize: "40px",
                        fontWeight: "700",
                        color: "#27ae60",
                        marginBottom: "15px",
                        textAlign: "center",
                        borderBottom: "3px solid #2ecc71",
                        paddingBottom: "20px",
                    }}
                >
                    ©Copyright De Imagenes
                </h1>
                <p
                    style={{
                        fontSize: "16px",
                        color: "#555",
                        textAlign: "center",
                        maxWidth: "650px",
                        margin: "0 auto 70px",
                        lineHeight: "1.6",
                    }}
                >
                    Aquí encontrarás los créditos y enlaces a las fuentes
                    originales de las imágenes de todos nuestros hermosos
                    destinos turísticos.
                </p>

                {sections.map((section) => (
                    <div key={section.key} style={{ marginBottom: "80px" }}>
                        <h2
                            style={{
                                marginBottom: "35px",
                                paddingLeft: "0px",
                                fontSize: "24px",
                                fontWeight: "700",
                                color: "#27ae60",
                                textTransform: "uppercase",
                                letterSpacing: "0.5px",
                            }}
                        >
                            {section.title}
                        </h2>
                        <div className="copyright-grid">
                            {items[section.key].map((it) => (
                                <div className="copyright-card" key={it.title}>
                                    <div className="copyright-caption">
                                        {it.title}
                                    </div>
                                    <a
                                        className="copyright-link"
                                        href={it.url}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        Ver fuente
                                    </a>
                                </div>
                            ))}
                        </div>
                    </div>
                ))}
            </div>
            <Footer />
        </div>
    );
}
