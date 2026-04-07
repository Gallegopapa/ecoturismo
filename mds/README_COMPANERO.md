# 🚀 Instrucciones Rápidas para Compañero

## Después de hacer `git pull`:

```bash
php artisan schedules:fix
```

**¡Eso es todo!** Este comando:
- ✅ Crea la tabla si falta (incluso si la migración da error)
- ✅ Agrega horarios a todos los lugares
- ✅ Funciona en todos los casos

## ⚠️ Si `php artisan migrate` da Error

**NO TE PREOCUPES.** Simplemente ejecuta:

```bash
php artisan schedules:fix
```

Este comando maneja automáticamente:
- ✅ Errores de migración
- ✅ Tablas que no existen
- ✅ Migraciones ya "ejecutadas" pero sin tabla
- ✅ Lugares sin horarios

## 📋 Verificar que Funcionó

Deberías ver algo como:

```
✅ La tabla existe.
📊 Total de lugares: 22
✅ Lugares con horarios: 22
📊 Total de horarios activos: 154
✅ Sistema de horarios verificado y corregido.
```

## 🔍 Más Información

- `INSTRUCCIONES_COMPANERO.md` - Instrucciones detalladas
- `SOLUCION_ERROR_MIGRACION.md` - Soluciones a errores específicos

---

**Resumen**: `git pull` → `php artisan schedules:fix` → ✅ Listo
