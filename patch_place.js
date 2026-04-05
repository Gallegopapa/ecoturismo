const fs = require('fs');
const path = require('path');

const filePath = path.join(__dirname, 'app', 'Models', 'Place.php');
let content = fs.readFileSync(filePath, 'utf8');

// Eliminar FALLBACK_IMAGES_BY_NAME
content = content.replace(/\/\*\*[\s\S]*?private const FALLBACK_IMAGES_BY_NAME = \[[\s\S]*?\];/g, '');

// Reemplazar los metodos getImageAttribute y getFallbackImageByName
const newMethods = `
    /**
     * Accessor para image - devuelve ruta normalizada y validada
     * Sin inyección de imágenes aleatorias
     */
    public function getImageAttribute($value)
    {
        if (!$value) {
            return null;
        }

        $value = trim((string) $value);
        if (empty($value)) {
            return null;
        }

        // Si ya es una URL completa
        if (preg_match('/^https?:\\/\\//', $value)) {
            return $value;
        }

        // PRIORIDAD 1: Si comienza con /imagenes/, devolver tal cual
        if (strpos($value, '/imagenes/') === 0) {
            return $value;
        }

        // Si es almacenamiento de storage public
        if (strpos($value, '/storage/') === 0 || strpos($value, 'storage/') === 0) {
            $cleanPath = str_replace(['storage/', '/storage/'], '', $value);
            return Storage::disk('public')->url(ltrim($cleanPath, '/'));
        }

        // Normalizar rutas sin barra inicial
        if (strpos($value, 'imagenes/') === 0) {
            return asset($value);
        }

        if (strpos($value, 'storage/places/') === 0) {
            return Storage::disk('public')->url(substr($value, strlen('storage/')));
        }

        if (strpos($value, 'places/') === 0) {
            return Storage::disk('public')->url($value);
        }

        // Por defecto, si el front la mandó solo como un string de archivo
        if (!str_contains($value, '/')) {
            return '/imagenes/' . ltrim($value, '/');
        }

        return $value;
    }
`;

content = content.replace(/\/\*\*[\s\S]*?public function getImageAttribute[\s\S]*?return self::FALLBACK_IMAGES_BY_NAME\[\$normalized\] \?\? null;\s*\}/g, newMethods);

fs.writeFileSync(filePath, content, 'utf8');
console.log("Model Place.php updated successfully.");
