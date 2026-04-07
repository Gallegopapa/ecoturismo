# 🧪 Pruebas Automatizadas — Laravel Ecoturismo

Este documento describe las pruebas automatizadas implementadas en el proyecto. Se utilizan **PHPUnit** junto con el helper de Laravel para pruebas de Feature y Unit.

---

## ▶️ Ejecutar las pruebas

```bash
php artisan test
```

Para ejecutar un archivo específico:

```bash
php artisan test tests/Feature/AuthFeatureTest.php
```

---

## 📁 Estructura

```
tests/
├── Feature/
│   ├── AuthFeatureTest.php
│   ├── ProfileFeatureTest.php
│   ├── FavoriteFeatureTest.php
│   ├── ReviewFeatureTest.php
│   ├── ReviewProfanityTest.php
│   ├── ReservationFeatureTest.php
│   └── CategoryAdminFeatureTest.php
└── Unit/
    └── NoProfanityTest.php
```

---

## 🔐 Autenticación — `AuthFeatureTest`

Cubre el flujo completo de registro, login, logout y verificación de token.

### Registro
| Prueba | Qué valida |
|---|---|
| `test_usuario_puede_registrarse_con_datos_validos` | Registro exitoso devuelve 201 con token y datos del usuario |
| `test_registro_devuelve_token_bearer_utilizable` | El token generado permite acceder a rutas protegidas |
| `test_registro_rechaza_correo_no_gmail` | Solo se aceptan correos `@gmail.com` |
| `test_registro_rechaza_nombre_duplicado` | No se permite nombre ya registrado (case-insensitive) |
| `test_registro_rechaza_correo_duplicado` | No se permite correo ya registrado |
| `test_registro_rechaza_contrasenas_no_coincidentes` | La confirmación debe coincidir con la contraseña |
| `test_registro_rechaza_contrasena_muy_corta` | Mínimo 8 caracteres |
| `test_registro_rechaza_contrasena_muy_larga` | Máximo 15 caracteres |
| `test_registro_rechaza_nombre_con_caracteres_especiales` | Solo se permiten caracteres alfanuméricos |
| `test_registro_rechaza_nombre_muy_corto` | Mínimo 3 caracteres |
| `test_registro_requiere_todos_los_campos` | Campos vacíos retornan 422 |

### Login
| Prueba | Qué valida |
|---|---|
| `test_usuario_puede_iniciar_sesion_con_correo` | Login con email y contraseña correctos |
| `test_usuario_puede_iniciar_sesion_con_nombre_usuario` | Login usando nombre de usuario |
| `test_usuario_puede_iniciar_sesion_usando_campo_email` | Acepta el campo `email` además de `login` |
| `test_login_no_distingue_mayusculas` | Email/nombre son case-insensitive |
| `test_login_falla_con_contrasena_incorrecta` | Contraseña incorrecta devuelve 401 |
| `test_login_falla_con_usuario_inexistente` | Usuario no registrado devuelve 401 |
| `test_login_requiere_contrasena` | Sin contraseña devuelve 422 |
| `test_login_requiere_campo_usuario` | Sin usuario devuelve 422 |
| `test_login_invalida_tokens_anteriores` | Un nuevo login revoca los tokens previos |

### Logout
| Prueba | Qué valida |
|---|---|
| `test_usuario_autenticado_puede_cerrar_sesion` | Cierra la sesión actual, devuelve 200 |
| `test_usuario_autenticado_puede_cerrar_todas_las_sesiones` | Revoca todas las sesiones activas |
| `test_invitado_no_puede_cerrar_sesion` | Sin token devuelve 401 |

### Usuario actual & Token
| Prueba | Qué valida |
|---|---|
| `test_endpoint_usuario_devuelve_datos_autenticado` | `/api/user` devuelve datos del usuario |
| `test_invitado_no_puede_acceder_a_endpoint_usuario` | Invitado recibe 401 |
| `test_token_valido_pasa_verificacion` | Token válido responde `valid: true` |
| `test_token_ausente_falla_verificacion` | Sin token devuelve 401 en `/api/verify-token` |

---

## 👤 Perfil — `ProfileFeatureTest`

Cubre ver perfil, actualizar datos, cambiar contraseña y eliminar cuenta.

### Ver perfil
| Prueba | Qué valida |
|---|---|
| `test_usuario_autenticado_puede_ver_perfil` | Devuelve perfil con estructura correcta |
| `test_invitado_no_puede_ver_perfil` | Invitado recibe 401 |

### Actualizar datos
| Prueba | Qué valida |
|---|---|
| `test_usuario_puede_actualizar_numero_telefono` | Actualiza el teléfono y lo persiste en BD |
| `test_usuario_puede_actualizar_nombre_usuario` | Cambia nombre a uno único |
| `test_usuario_no_puede_usar_nombre_duplicado` | Nombre ya existente devuelve 422 |
| `test_usuario_puede_actualizar_correo` | Cambia a un correo `@gmail.com` libre |
| `test_usuario_no_puede_usar_correo_no_gmail` | Correo que no sea gmail devuelve 422 |
| `test_usuario_no_puede_usar_correo_duplicado` | Correo ya registrado devuelve 422 |
| `test_invitado_no_puede_actualizar_perfil` | Invitado recibe 401 |

### Cambiar contraseña
| Prueba | Qué valida |
|---|---|
| `test_usuario_puede_cambiar_contrasena` | Cambio exitoso con contraseña actual correcta |
| `test_cambio_contrasena_falla_con_contrasena_actual_incorrecta` | Contraseña actual errónea devuelve 422 |
| `test_cambio_contrasena_falla_si_es_igual_a_la_actual` | No se puede reutilizar la misma contraseña |
| `test_cambio_contrasena_rechaza_contrasena_muy_corta` | Nueva contraseña muy corta devuelve 422 |
| `test_cambio_contrasena_rechaza_confirmacion_diferente` | Confirmación no coincidente devuelve 422 |
| `test_invitado_no_puede_cambiar_contrasena` | Invitado recibe 401 |

### Eliminar cuenta
| Prueba | Qué valida |
|---|---|
| `test_usuario_puede_eliminar_su_cuenta` | Elimina cuenta y la borra de la BD |
| `test_invitado_no_puede_eliminar_cuenta` | Invitado recibe 401 |

---

## ❤️ Favoritos — `FavoriteFeatureTest`

| Prueba | Qué valida |
|---|---|
| `test_usuario_puede_ver_favoritos` | Lista de favoritos devuelve 200 |
| `test_usuario_puede_agregar_lugar_a_favoritos` | Agrega un lugar y lo persiste en BD |
| `test_usuario_no_puede_agregar_mismo_lugar_dos_veces` | Favorito duplicado devuelve 422 |
| `test_usuario_puede_eliminar_lugar_de_favoritos` | Elimina el favorito de BD |
| `test_invitado_no_puede_acceder_a_favoritos` | Invitado recibe 401 en GET y POST |

---

## ⭐ Reseñas — `ReviewFeatureTest`

| Prueba | Qué valida |
|---|---|
| `test_usuario_no_puede_resena_mismo_lugar_dos_veces` | Segunda reseña al mismo lugar devuelve 422 |
| `test_usuario_puede_actualizar_su_propia_resena` | Actualiza rating y comentario correctamente |
| `test_usuario_no_puede_editar_resena_ajena` | Editar reseña ajena devuelve 403 |
| `test_usuario_puede_eliminar_su_propia_resena` | Elimina reseña propia de BD |
| `test_admin_puede_eliminar_cualquier_resena` | Admin puede borrar reseñas de cualquier usuario |
| `test_usuario_no_puede_eliminar_resena_ajena` | Borrar reseña ajena devuelve 403 |

---

## 🚫 Filtro de palabrotas — `ReviewProfanityTest` & `NoProfanityTest`

Validan la regla personalizada `NoProfanity` que bloquea comentarios ofensivos.

| Prueba | Archivo | Qué valida |
|---|---|---|
| `test_palabrota_bloquea_creacion_de_resena` | Feature | Comentario con groserías devuelve 422 y no se guarda |
| `test_resena_limpia_se_crea_correctamente` | Feature | Comentario limpio crea la reseña (201) |
| `test_bloquea_palabrotas_conocidas` | Unit | La regla rechaza palabras ofensivas con variantes: acentos, puntos, repeticiones de letras |
| `test_permite_texto_limpio` | Unit | La regla permite textos sin groserías |

---

## 📅 Reservas — `ReservationFeatureTest`

| Prueba | Qué valida |
|---|---|
| `test_usuario_puede_ver_sus_reservas` | Lista de reservas propias devuelve 200 |
| `test_usuario_puede_crear_reserva_valida` | Crea reserva en horario disponible (lun–vie 08:00–18:00) |
| `test_usuario_no_puede_reservar_fuera_de_horario` | Reserva en día cerrado (domingo) devuelve 422 |
| `test_usuario_no_puede_reservar_en_horario_solapado` | Reserva solapada con otra existente devuelve 422 |
| `test_usuario_puede_eliminar_su_propia_reserva` | Elimina la reserva y la quita de BD |

---

## 🗂️ Categorías (Admin) — `CategoryAdminFeatureTest`

| Prueba | Qué valida |
|---|---|
| `test_admin_puede_ver_categorias` | Admin accede a `/api/categories` con 200 |
| `test_usuario_normal_no_puede_crear_categorias` | Usuario sin rol admin recibe 403 |
| `test_admin_puede_crear_categoria` | Admin crea categoría y la persiste en BD |
| `test_admin_puede_actualizar_categoria` | Admin actualiza nombre e icono |
| `test_admin_puede_eliminar_categoria` | Admin elimina categoría y la quita de BD |

---

## 📊 Resumen total

| Módulo | Archivo | Tipo | Pruebas |
|---|---|---|:---:|
| Autenticación | `AuthFeatureTest` | Feature | 27 |
| Perfil de usuario | `ProfileFeatureTest` | Feature | 17 |
| Reseñas | `ReviewFeatureTest` | Feature | 6 |
| Filtro palabrotas | `ReviewProfanityTest` | Feature | 2 |
| Filtro palabrotas | `NoProfanityTest` | Unit | 2 |
| Favoritos | `FavoriteFeatureTest` | Feature | 5 |
| Reservas | `ReservationFeatureTest` | Feature | 5 |
| Categorías | `CategoryAdminFeatureTest` | Feature | 5 |
| **Total** | | | **69** |
