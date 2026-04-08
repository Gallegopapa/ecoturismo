# 🚀 PASOS INMEDIATOS - Implementación Sistema Empresa

## 1️⃣ EJECUTAR MIGRACIONES (en PowerShell)

```powershell
cd "c:\Users\juanj\Desktop\Laravel_Ecoturismo"

# Ejecutar todas las migraciones
php artisan migrate

# Output esperado:
# Migrating: 2026_02_09_000001_add_tipo_usuario_to_usuarios
# Migrated: 2026_02_09_000001_add_tipo_usuario_to_usuarios (234ms)
# ... (igual para las 3 migraciones restantes)
```

## 2️⃣ EJECUTAR SEEDER (Razones de Rechazo)

```powershell
php artisan db:seed --class=RejectionReasonsSeeder

# Output esperado:
# Seeding: RejectionReasonsSeeder
# Database seeding completed successfully
```

## 3️⃣ VERIFICAR INSTALACIÓN

```powershell
php artisan tinker

# Copiar y ejecutar una por una:

CompanyReservation::count()
# Debe retornar: 0

RejectionReason::count()
# Debe retornar: 6

RejectionReason::all()
# Debe mostrar 6 razones predefinidas

exit()
```

## 4️⃣ PROBAR CREAR USUARIO EMPRESA

### Opción A: Via Admin Panel React

1. Ir a http://localhost:5173 (tu app React)
2. Login como admin
3. Panel Admin → Usuarios → "Crear Nuevo Usuario"
4. Formulario:
   - Nombre: `empresa_test_1`
   - Email: `test@empresa.com`
   - Tipo Usuario: **Empresa**
   - Lugares: Seleccionar alguno (ej: "Reserva Natural ABC")
   - Rol: Gerente
   - Marcar: ✓ Principal
   - Hacer clic: "Crear Usuario"

5. Copiar contraseña generada (mostrada en alert)

### Opción B: Via API con cURL

```powershell
$headers = @{
    'Authorization' = 'Bearer YOUR_ADMIN_TOKEN'
    'Content-Type' = 'application/json'
}

$body = @{
    name = 'empresa_test_2'
    email = 'test2@empresa.com'
    tipo_usuario = 'empresa'
    lugares = @(
        @{
            place_id = 1
            rol = 'gerente'
            es_principal = $true
        }
    )
} | ConvertTo-Json

Invoke-WebRequest -Uri 'http://localhost:8000/api/admin/users' `
    -Method POST `
    -Headers $headers `
    -Body $body
```

## 5️⃣ CREAR RESERVA DE PRUEBA (Cliente)

1. Ir a http://localhost:5173
2. Login como usuario normal (cliente)
3. Buscar un lugar
4. Hacer reserva para una fecha futura

**Resultado**: Se crea automáticamente un registro en `company_reservations`

## 6️⃣ ACCEDER AL PANEL DE EMPRESA

1. Logout del usuario cliente
2. Login con credenciales de empresa creada en paso 4
3. Ir a: `/company/dashboard` (o la ruta que hayas configurado)
4. Deberías ver:
   - Estadísticas (Pendientes, Aceptadas, Rechazadas)
   - Tu reserva aparece en "Pendientes"
   - Botones: ✓ Aceptar  |  ✕ Rechazar

## 7️⃣ PROBAR FUNCIONALIDADES

### Aceptar Reserva
- Clic en "✓ Aceptar"
- Debe cambiar estado a "Aceptada"
- Contador debe decrementar en Pendientes e incrementar en Aceptadas

### Rechazar Reserva
- Clic en "✕ Rechazar"
- Modal con selector de razón
- Seleccionar razón (ej: "No hay disponibilidad")
- Agregar comentario (opcional)
- Clic en "Rechazar Reserva"
- Debe cambiar estado a "Rechazada"

## ✅ CHECKLIST DE VERIFICACIÓN

- [ ] Migraciones ejecutadas sin errores
- [ ] Seeder ejecutado (6 razones creadas)
- [ ] Usuario empresa creado exitosamente
- [ ] Reserva de cliente se crea
- [ ] Company reservation se crea automáticamente
- [ ] Usuario empresa ve su panel
- [ ] Puede aceptar reservas
- [ ] Puede rechazar con razón
- [ ] Estados se actualizan correctamente

## 🐛 SI ALGO FALLA

### Error: "Unknown column 'tipo_usuario'"
```bash
php artisan migrate:refresh --seed
# O rollback + migrate de nuevo
```

### Error: "Table not found: company_reservations"
```bash
php artisan migrate --step
# Ejecutar solo las nuevas migraciones
```

### El usuario empresa no ve reservas
- Verificar que el lugar está asignado correctamente
- Verificar que hay reservas para ese lugar
- Revisar permisos en el controller

### La contraseña generada no funciona
- Copiar exactamente como aparece (sin espacios al inicio/final)
- Verificar que está usando ese usuario en login

## 🎯 PRÓXIMO PASO (Después de verificar)

Una vez que todo funcione:

1. **Actualizar componentes React** en AdminPanel para integrar CreateUserModal
2. **Crear ruta /company/dashboard** en tu app React
3. **Implementar emails** para notificaciones (TODO en controllers)
4. **Agregar auditoría** de cambios (logs)
5. **Testing en staging** antes de producción

---

**Tiempo estimado**: 20-30 minutos para completar todos los pasos
**Requiere**: Usuario admin, acceso a terminal PowerShell, acceso a panel React

