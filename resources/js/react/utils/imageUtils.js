export const normalizeName = (str) => {
    if (!str) return '';
    return str
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '') // Quitar acentos
        .replace(/\s+/g, ' ') // Normalizar espacios
        .trim();
};

export const mapeoImagenesDeterministico = {
    // Paraísos Acuáticos
    'Lago De La Pradera': '/imagenes/Lago.jpeg',
    'La Laguna Del Otún': '/imagenes/laguna.jpg',
    'Laguna Del Otún': '/imagenes/laguna.jpg',
    'Chorros De Don Lolo': '/imagenes/lolo-2.jpg',
    'Termales de Santa Rosa': '/imagenes/termaales.jpg',
    'Parque Acuático Consota': '/imagenes/consota.jpg',
    'Balneario Los Farallones': '/imagenes/farallones.jpeg',
    'Cascada Los Frailes': '/imagenes/frailes3.jpg',
    'Río San José': '/imagenes/sanjose3.jpg',
    'Rio San Jose': '/imagenes/sanjose3.jpg',
    // Lugares Montañosos
    'Alto Del Nudo': '/imagenes/nudo.jpg',
    'Alto Del Toro': '/imagenes/toro.jpg',
    'La Divisa De Don Juan': '/imagenes/divisa3.jpeg',
    'Cerro Batero': '/imagenes/batero.jpg',
    'Reserva Forestal La Nona': '/imagenes/lanona5.jpg',
    'Reserva Natural Cerro Gobia': '/imagenes/gobia.jpg',
    'Kaukitá Bosque Reserva': '/imagenes/kaukita3.jpg',
    'Kaukita Bosque Reserva': '/imagenes/kaukita3.jpg',
    'Reserva Natural DMI Agualinda': '/imagenes/distritomanejo8.jpg',
    // Parques y Más
    'Parque Nacional Natural Tatamá': '/imagenes/tatama.jpg',
    'Parque Nacional Natural Tatama': '/imagenes/tatama.jpg',
    'Parque Las Araucarias': '/imagenes/araucarias.jpg',
    'Parque Regional Natural Cuchilla de San Juan': '/imagenes/cuchilla.jpg',
    'Parque Natural Regional Santa Emilia': '/imagenes/santaemilia2.jpg',
    'Jardín Botánico UTP': '/imagenes/jardin.jpeg',
    'Jardin Botanico UTP': '/imagenes/jardin.jpeg',
    'Jardín Botánico De Marsella': '/imagenes/jardinmarsella2.jpg',
    'Jardin Botanico De Marsella': '/imagenes/jardinmarsella2.jpg',
    'Piedras marcadas': '/imagenes/piedras5.jpg',
    'Piedras Marcadas': '/imagenes/piedras5.jpg',
    'Barbas Bremen': '/imagenes/paisaje5.jpg',
    'Santuario Otún Quimbaya': '/imagenes/paisaje2.jpg',
    'Santuario Otun Quimbaya': '/imagenes/paisaje2.jpg',
    'Bioparque Mariposario Bonita Farm': '/imagenes/ukumari.jpg',
    'Parque Bioflora En Finca Turística Los Rosales': '/imagenes/parquecafe.jpg',
    'Parque Bioflora En Finca Turistica Los Rosales': '/imagenes/parquecafe.jpg',
    'Eco Hotel Paraiso Real': '/imagenes/paisaje4.jpg',
    'Termales de San Vicente': '/imagenes/termales.jpg',
    'Voladero El Zarzo': '/imagenes/mirador5.jpg',
};

// Función de fallback para onError: devuelve imagen local dado el nombre del lugar
export const getLocalFallbackImage = (name) => {
    if (!name) return '/imagenes/placeholder.svg';
    const direct = mapeoImagenesDeterministico[name];
    if (direct) return direct;
    const normalized = normalizeName(name);
    const found = Object.entries(mapeoImagenesDeterministico).find(
        ([k]) => normalizeName(k) === normalized
    );
    return found ? found[1] : '/imagenes/placeholder.svg';
};


export const fallbacksPorCategoria = {
    'paraisos-acuaticos': [
        { nombre: "Lago De La Pradera", imagen: "/imagenes/Lago.jpeg" },
        { nombre: "La Laguna Del Otún", imagen: "/imagenes/laguna.jpg" },
        { nombre: "Chorros De Don Lolo", imagen: "/imagenes/lolo-2.jpg" },
        { nombre: "Termales de Santa Rosa", imagen: "/imagenes/termaales.jpg" },
        { nombre: "Parque Acuático Consota", imagen: "/imagenes/consota.jpg" },
        { nombre: "Balneario Los Farallones", imagen: "/imagenes/farallones.jpeg" },
        { nombre: "Cascada Los Frailes", imagen: "/imagenes/frailes3.jpg" },
        { nombre: "Río San José", imagen: "/imagenes/sanjose3.jpg" },
    ],
    'lugares-montanosos': [
        { titulo: "Alto Del Nudo", imagen: "/imagenes/nudo.jpg" },
        { titulo: "Alto Del Toro", imagen: "/imagenes/toro.jpg" },
        { titulo: "La Divisa De Don Juan", imagen: "/imagenes/divisa3.jpeg" },
        { titulo: "Cerro Batero", imagen: "/imagenes/batero.jpg" },
        { titulo: "Reserva Forestal La Nona", imagen: "/imagenes/lanona5.jpg" },
        { titulo: "Reserva Natural Cerro Gobia", imagen: "/imagenes/gobia.jpg" },
        { titulo: "Kaukitá Bosque Reserva", imagen: "/imagenes/kaukita3.jpg" },
        { titulo: "Reserva Natural DMI Agualinda", imagen: "/imagenes/distritomanejo8.jpg" },
    ],
    'parques-y-mas': [
        { titulo: "Parque Nacional Natural Tatamá", imagen: "/imagenes/tatama.jpg" },
        { titulo: "Parque Las Araucarias", imagen: "/imagenes/araucarias.jpg" },
        { titulo: "Parque Regional Natural Cuchilla de San Juan", imagen: "/imagenes/cuchilla.jpg" },
        { titulo: "Parque Natural Regional Santa Emilia", imagen: "/imagenes/santaemilia2.jpg" },
        { titulo: "Jardín Botánico UTP", imagen: "/imagenes/jardin.jpeg" },
        { titulo: "Jardín Botánico De Marsella", imagen: "/imagenes/jardinmarsella2.jpg" },
    ],
};

export const resolvePlaceImage = (lugar, categoriaFiltro = "todas", index = 0) => {
    const nombreOriginal = lugar.name || lugar.titulo || lugar.nombre || '';
    const nombreLugar = normalizeName(nombreOriginal);

    // PRIMERO: Verificar si hay imagen válida desde API (storage o /imagenes)
    // Solo aceptar URLs absolutas válidas (que empiecen con / o http)
    const imageField = lugar.image || lugar.imagen;
    const imagenSubida = imageField && (
        imageField.startsWith('/storage/') ||
        imageField.startsWith('/imagenes/') ||
        (imageField.startsWith('http') && (imageField.includes('/storage/places/') || imageField.includes('/imagenes/')))
    ) ? imageField : null;

    // SEGUNDO: Buscar en mapeo determinístico
    let imagenLocal = null;
    if (!imagenSubida) {
        imagenLocal = mapeoImagenesDeterministico[nombreOriginal];

        // Buscar en llaves lowercased
        if (!imagenLocal) {
            const mapeoLower = Object.entries(mapeoImagenesDeterministico).reduce((acc, [k, v]) => {
                acc[normalizeName(k)] = v;
                return acc;
            }, {});
            imagenLocal = mapeoLower[nombreLugar];
        }
    }

    // TERCERO: Si hay categoría (y no es "todas"), buscar en fallbacks de esa categoría
    if (!imagenLocal && !imagenSubida && categoriaFiltro !== "todas") {
        const fallbacks = fallbacksPorCategoria[categoriaFiltro];
        if (fallbacks && fallbacks.length > 0) {
            let fallback = fallbacks.find(fb => (fb.nombre || fb.titulo) === nombreOriginal);

            if (!fallback) {
                fallback = fallbacks.find(fb => normalizeName(fb.nombre || fb.titulo) === nombreLugar);
            }

            if (!fallback) {
                const palabrasClave = nombreLugar.split(' ').filter(p => p.length > 3);
                fallback = fallbacks.find(fb => {
                    const nombreFallback = normalizeName(fb.nombre || fb.titulo);
                    return palabrasClave.some(palabra => nombreFallback.includes(palabra));
                });
            }

            if (!fallback && index < fallbacks.length) {
                fallback = fallbacks[index];
            }

            imagenLocal = fallback?.imagen || null;
        }
    }

    // CUARTO: Buscar en las categorías asignadas al lugar
    if (!imagenLocal && !imagenSubida) {
        const lugarCategorias = lugar.categories || [];
        for (const cat of lugarCategorias) {
            const slug = cat.slug || cat.name?.toLowerCase().replace(/\s+/g, '-');
            const fallbacks = fallbacksPorCategoria[slug];

            if (fallbacks && fallbacks.length > 0) {
                let fallback = fallbacks.find(fb => (fb.nombre || fb.titulo) === nombreOriginal);
                if (!fallback) fallback = fallbacks.find(fb => normalizeName(fb.nombre || fb.titulo) === nombreLugar);
                if (!fallback) {
                    const palabrasClave = nombreLugar.split(' ').filter(p => p.length > 3);
                    fallback = fallbacks.find(fb => {
                        const nombreFallback = normalizeName(fb.nombre || fb.titulo);
                        return palabrasClave.some(palabra => nombreFallback.includes(palabra));
                    });
                }
                if (fallback?.imagen) {
                    imagenLocal = fallback.imagen;
                    break;
                }
            }
        }
    }

    // RETORNO DE LA IMAGEN
    return imagenSubida || imagenLocal || lugar.imagen || "/imagenes/placeholder.svg";
};
