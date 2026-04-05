# 🚀 INICIO RÁPIDO

## Para iniciar el proyecto:

### 1️⃣ Primera vez (Instalación)

```bash
# Instalar dependencias
composer install
npm install

# Configurar entorno
copy .env.example .env
php artisan key:generate
php artisan migrate
```

### 2️⃣ Iniciar servidores

**Opción más fácil (un solo comando):**
```bash
npm run serve
```

**O manualmente (dos terminales):**

Terminal 1:
```bash
php artisan serve
```

Terminal 2:
```bash
npm run dev
```

### 3️⃣ Abrir navegador

Ir a: **http://localhost:8000**

---

## ✨ ¿Qué cambió?

- ✅ Ahora el frontend es 100% React
- ✅ Las validaciones están en los controllers (backend)
- ✅ Ya no necesitas las vistas `.blade.php` (excepto `app.blade.php`)
- ✅ Es una Single Page Application (SPA)

---

## 📚 Documentación completa:

- [INICIO_PROYECTO_REACT.md](INICIO_PROYECTO_REACT.md) - Guía completa
- [VALIDACIONES_MIGRADAS.md](VALIDACIONES_MIGRADAS.md) - Todas las validaciones
- [GUIA_INTEGRACION_REACT.md](GUIA_INTEGRACION_REACT.md) - Ejemplos de código
