# 📝 Registro de Avances del Proyecto (Historial de Commits)

## Commit 4: Implementación US-AUTH-04 (Recuperación de Contraseña)
**Fecha:** 03 Abril 2026
**Archivos extraídos y montados:**
- Backend: `PasswordResetController.php`, `ResetPasswordNotification.php` y la migración `2026_02_06_000001_create_password_reset_tokens_table.php`.
- Frontend: Módulos completos en React `forgot-password/page.jsx`, `forgot-password/sent.jsx`, y `reset-password/page.jsx`.
**Integración:**
- Endpoints: Se inyectaron `POST /api/password/forgot` y `POST /api/password/reset` en `routes/api.php` bajo la zona pública.
- React Router: Se anclaron las tres nuevas vistas de recuperación a `main.jsx` (`/forgot-password`, `/forgot-password/sent`, `/reset-password`).
**Estado de la Tarea:** Terminada. El ciclo de recuperación de contraseña está ensamblado y aislado. Listo para el `git commit`.

---

## Commit 3: Implementación US-AUTH-03 (Logout)
**Fecha:** 03 Abril 2026
**Archivos de Backend restaurados:**
- Endpoints: Se reinstauraron los endpoints de terminación de sesión `POST /api/logout` y `POST /api/logout-all` en `routes/api.php` bajo el middleware `auth:sanctum`.
- Controladores: Se inyectaron nuevamente los métodos `logout()` y `logoutAll()` en `AuthController` utilizando las herramientas de Laravel Sanctum para aniquilar tokens actuales y globales.
**Archivos de React:** 
- Aislamiento en UI de Usuarios Autenticados: Se inspeccionó el componente dinámico `Header2.jsx` (UI de cabecera post-login) y se purgó de su navegación todo contenido futuro (reservas, ecohoteles, lugares, admin panel, perfiles), limitándolo exclusivamente a ofrecer la funcionalidad de **Cerrar Sesión**.
- Lógica Frontend: La función nativa `logout` se mantiene intacta en `AuthContext.jsx` despachando la petición, eliminando atributos del `localStorage` y redirigiendo agresivamente a la ruta pública (`/`).
**Estado de la Tarea:** Terminada. El ciclo de autenticación cerrado funciona atómicamente. Todo listo para el `git commit`.

---

## Commit 2: Implementación US-AUTH-02 (Login de Usuarios)
**Fecha:** 03 Abril 2026
**Archivos de Backend restaurados:**
- Endpoints: Se habilitó formalmente `POST /api/login` en `routes/api.php`.
- Controladores: Se reintrodujo orgánicamente el método `login()` en `AuthController` utilizando la lógica que fue previamente encapsulada en develop.
**Archivos de React:** 
- La capa de Interfaz (`login/page.jsx`) y el Contexto (`AuthContext.jsx`) ya contaban previamente con la funcionalidad base estructurada, por lo cual se reactivaron plenamente.
**Limpieza realizada:** 
- Mantenimiento del aislamiento: Continúan censurados los métodos de sesión extendidos (`logout`, `me`, `verifyToken`) a la espera de sus respectivas OUS.
**Estado de la Tarea:** Terminada. El login devuelve correctamente el Bearer Token y los atributos `tipo_usuario` / `is_admin`. Todo listo para el `git commit`.

---

## Commit 1: Implementación US-AUTH-01 (Registro de Usuarios)
**Fecha:** 03 Abril 2026
**Archivos base extraídos e integrados:**
- Framework base: `composer.json`, `package.json`, `vite.config.js`, `.env.example`, `app.blade.php`, configuraciones.
- Archivos de React extraídos: `App.jsx`, `main.jsx`, componentes base (`Header`, `Footer`), vistas de Autenticación (`login/page.jsx`, `context/AuthContext.jsx`).
- Archivos de Backend extraídos: Modelos (`Usuarios`), Controladores (`AuthController`), Migraciones esenciales, `routes/api.php` y `routes/web.php`.
**Limpieza realizada (Ingeniería Inversa):**
- Recorte exhaustivo de `routes/web.php` y `routes/api.php`, limitando la aplicación backend exclusivamente a la funcionalidad de rendereo de React y al endpoint de registro `POST /api/register`.
- Depuración de todos los métodos futuros (ej. login, reservas) en la lógica del controlador `AuthController`.
- Se mutilaron de forma intencional los links de futuros features del `Header`, para limitar la aplicación solo al componente de registro, tal como se solicitó en el alcance de US-AUTH-01.
**Estado de la Tarea:** Terminada. Todo listo para la primera prueba atómica y el `git commit`.

---

# 📦 Module: Authentication---

## US-AUTH-01: User Registration

| Field | Details |
|-------|---------|
| **Role** | Visitor (unauthenticated) |
| **Priority** | High |

> "As a visitor, I want to register a new account to be able to access personalized features such as reservations, favorites and reviews."

### Description
The visitor completes the registration form with username, email, password and password confirmation. The system validates all fields, creates the account and returns a Bearer token that logs the user in immediately.

### Preconditions
- The user is not authenticated.
- The registration form is accessible at the `/login` route (registration tab).
- The `POST /api/register` endpoint is available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | Data is valid | User submits the form | System returns HTTP 201 with Bearer token and user data |
| AC2 | Username already exists (case-insensitive) | User submits the form | System returns HTTP 422 with field-level error |
| AC3 | Email is already registered | User submits the form | System returns HTTP 422 with field-level error |
| AC4 | Email does not end in `@gmail.com` | User submits the form | System returns HTTP 422 indicating only Gmail accounts are accepted |
| AC5 | Password has fewer than 8 characters | User submits the form | System returns HTTP 422 |
| AC6 | Password has more than 15 characters | User submits the form | System returns HTTP 422 |
| AC7 | Passwords do not match | User submits the form | System returns HTTP 422 |
| AC8 | Username contains special characters | User submits the form | System returns HTTP 422 (alphanumeric only) |
| AC9 | Username has fewer than 3 characters | User submits the form | System returns HTTP 422 |
| AC10 | A required field is empty | User submits the form | System returns HTTP 422 |
| AC11 | Registration was successful | Token is returned | Token grants access to protected routes immediately |

### Endpoint
`POST /api/register`

### Notes / Restrictions
- Only `@gmail.com` addresses are accepted.
- Username must be unique case-insensitively.
- Minimum password length: 8; maximum: 15.
- Token is persisted in `localStorage` on the frontend.

---

## US-AUTH-02: Login

| Field | Details |
|-------|---------|
| **Role** | Registered User / Company User / Admin |
| **Priority** | High |

> "As a registered user, I want to log in with my email or username to be able to access my account and perform authenticated actions."

### Description
The user enters their identifier (email or username) and password. The system authenticates the user, invalidates previous tokens and returns a new Bearer token along with the user profile including role indicators.

### Preconditions
- The user has a registered account.
- The `POST /api/login` endpoint is available.
- No active session exists in `localStorage`.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | Valid email and correct password | User submits the form | System returns HTTP 200 with Bearer token and user object |
| AC2 | Valid username and correct password | User submits the form | System returns HTTP 200 with Bearer token |
| AC3 | Identifier has different capitalization (e.g.: `USER@gmail.com`) | User submits the form | System authenticates successfully (case-insensitive) |
| AC4 | Password is incorrect | User submits the form | System returns HTTP 401 |
| AC5 | Identifier is not registered | User submits the form | System returns HTTP 401 |
| AC6 | Password field is empty | User submits the form | System returns HTTP 422 |
| AC7 | Identifier field is empty | User submits the form | System returns HTTP 422 |
| AC8 | Login is successful | — | All previous user tokens are revoked |
| AC9 | Login is successful | — | Frontend persists the token in `localStorage` and redirects to `/pagLogueados` |
| AC10 | Login is successful | — | User object includes `is_admin` and `tipo_usuario` fields to route to the correct dashboard |

### Endpoint
`POST /api/login`

### Notes / Restrictions
- The login field accepts both `login` and `email` as parameter names.
- Login invalidates all previous tokens (single-session design).

---

## US-AUTH-03: Logout

| Field | Details |
|-------|---------|
| **Role** | Authenticated User / Company User / Admin |
| **Priority** | High |

> "As an authenticated user, I want to log out so that my session is terminated and my account is protected on shared devices."

### Description
The user triggers logout. The system revokes the current Bearer token (or all tokens) and the frontend clears the `localStorage` session.

### Preconditions
- The user is authenticated with a valid Bearer token.
- The `POST /api/logout` and `POST /api/logout-all` endpoints are available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | User is authenticated | Calls `POST /api/logout` | Current token is revoked and system returns HTTP 200 |
| AC2 | User is authenticated | Calls `POST /api/logout-all` | All active user tokens are revoked and system returns HTTP 200 |
| AC3 | User is a visitor (no token) | Calls `POST /api/logout` | System returns HTTP 401 |
| AC4 | Logout is successful | — | Frontend removes token from `localStorage` and redirects to `/login` or `/` |
| AC5 | Token was revoked | Any subsequent request uses that token | System returns HTTP 401 |

### Endpoints
`POST /api/logout` · `POST /api/logout-all`

### Notes / Restrictions
- The frontend must clear `localStorage` on logout regardless of the API response status, to avoid stale session states.

---

## US-AUTH-04: Password Recovery

| Field | Details |
|-------|---------|
| **Role** | Registered User |
| **Priority** | Medium |

> "As a registered user, I want to recover access to my account via email to be able to reset a forgotten password without losing my data."

### Description
The user provides their registered email. The system sends a reset link. The user clicks the link and sets a new password.

### Preconditions
- The user has a registered account with a valid email.
- SMTP is configured and operational on the Laravel backend.
- The `POST /api/password/forgot` and `POST /api/password/reset` endpoints are available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | Email is registered and valid | User sends `POST /api/password/forgot` | System sends the reset email and returns HTTP 200 |
| AC2 | Email is not registered | User submits the form | System returns an appropriate response without revealing whether the email exists |
| AC3 | Reset token is valid and new password is correct | User sends `POST /api/password/reset` | Password is updated and user can log in with new credentials |
| AC4 | Reset token is expired or invalid | User sends `POST /api/password/reset` | System returns HTTP 422 |
| AC5 | Link was generated | — | Link in the email is single-use and expires after a defined time |

### Endpoints
`POST /api/password/forgot` · `POST /api/password/reset`

### Notes / Restrictions
- SMTP configuration is required.
- System must not reveal whether an email is registered (security best practice).

---

## US-AUTH-05: Token Verification

| Field | Details |
|-------|---------|
| **Role** | Frontend Application (internal use) |
| **Priority** | Medium |

> "As a frontend application, I want to verify whether the stored token is still valid to be able to automatically redirect unauthenticated users to the login page."

### Description
On application load or route change, the frontend calls the token verification endpoint. If the token is valid, the user continues. If not, the frontend clears the session and redirects to login.

### Preconditions
- A token is stored in `localStorage`.
- The `GET /api/verify-token` endpoint is available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | Bearer token is valid | `GET /api/verify-token` is called | System returns HTTP 200 with `{ valid: true }` |
| AC2 | Token is expired or invalid | `GET /api/verify-token` is called | System returns HTTP 401 |
| AC3 | Verification returns 401 | — | Frontend clears `localStorage` and redirects to `/login` |
| AC4 | Verification has not completed | — | Frontend does not display protected content |

### Endpoints
`GET /api/verify-token` · `GET /api/user`

### Notes / Restrictions
- This is primarily a frontend internal mechanism. `GET /api/user` can serve the same purpose when full user data is needed.

---

# 📦 Module: User Profile

---

## US-PROF-01: View Own Profile

| Field | Details |
|-------|---------|
| **Role** | Authenticated User |
| **Priority** | Medium |

> "As an authenticated user, I want to view my profile information to be able to confirm that my stored data is correct."

### Preconditions
- User is authenticated.
- `GET /api/profile` is available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | User is authenticated | `GET /api/profile` is called | System returns HTTP 200 with fields: `name`, `email`, `telefono`, `foto_perfil`, `fecha_registro` |
| AC2 | User is a visitor (no token) | `GET /api/profile` is called | System returns HTTP 401 |
| AC3 | No profile photo has been uploaded | — | `foto_perfil` field returns `null` |

### Endpoint
`GET /api/profile`

### Notes / Restrictions
- Profile photo is served via `GET /api/profile/photo/{filename}` (public endpoint).

---

## US-PROF-02: Update Profile Information

| Field | Details |
|-------|---------|
| **Role** | Authenticated User |
| **Priority** | Medium |

> "As an authenticated user, I want to update my username, email and phone number so that my contact information is up to date."

### Description
The user edits one or more profile fields and submits. Without a photo, the request uses `PUT /api/profile` (JSON). With a new photo, it uses `POST /api/profile` with FormData and the `_method=PUT` override for Laravel compatibility.

### Preconditions
- User is authenticated.
- `PUT /api/profile` and `POST /api/profile` (with FormData) are available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | Username is unique | User updates it | System persists the change and returns HTTP 200 |
| AC2 | Username is already taken by another user | User submits the form | System returns HTTP 422 |
| AC3 | Email is a valid `@gmail.com` and not in use | User updates it | System persists the change and returns HTTP 200 |
| AC4 | Email does not end in `@gmail.com` | User submits the form | System returns HTTP 422 |
| AC5 | Phone number is valid | User updates it | System persists the change and returns HTTP 200 |
| AC6 | User is a visitor (no token) | `PUT /api/profile` is called | System returns HTTP 401 |
| AC7 | FormData request includes profile image | `POST /api/profile` is called with `_method=PUT` | Image is stored and `foto_perfil` URL is updated |

### Endpoints
`PUT /api/profile` · `POST /api/profile` (FormData)

### Notes / Restrictions
- Using `POST` with `_method=PUT` is necessary because `multipart/form-data` requests cannot reliably use the `PUT` verb on all servers.

---

## US-PROF-03: Change Password

| Field | Details |
|-------|---------|
| **Role** | Authenticated User |
| **Priority** | Medium |

> "As an authenticated user, I want to change my password to be able to maintain the security of my account."

### Preconditions
- User is authenticated.
- `PUT /api/profile/password` is available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | Current password is correct and new password is valid (8–15 chars) | User submits the form | System updates the password and returns HTTP 200 |
| AC2 | Current password is incorrect | User submits the form | System returns HTTP 422 |
| AC3 | New password is identical to the current one | User submits the form | System returns HTTP 422 |
| AC4 | New password has fewer than 8 characters | User submits the form | System returns HTTP 422 |
| AC5 | New password confirmation does not match | User submits the form | System returns HTTP 422 |
| AC6 | User is a visitor (no token) | `PUT /api/profile/password` is called | System returns HTTP 401 |

### Endpoint
`PUT /api/profile/password`

---

## US-PROF-04: Delete Own Account

| Field | Details |
|-------|---------|
| **Role** | Authenticated User |
| **Priority** | Low |

> "As an authenticated user, I want to permanently delete my account so that my data is removed from the platform."

### Preconditions
- User is authenticated.
- `DELETE /api/profile` is available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | User is authenticated | `DELETE /api/profile` is called | User record is deleted from the database and system returns HTTP 200 |
| AC2 | Account was deleted | — | All user tokens are revoked |
| AC3 | User is a visitor (no token) | `DELETE /api/profile` is called | System returns HTTP 401 |
| AC4 | Deletion is successful | — | Frontend clears `localStorage` and redirects to `/` or `/login` |

### Endpoint
`DELETE /api/profile`

### Notes / Restrictions
- The behavior of related records (reservations, reviews, favorites, contacts) upon user deletion must be defined by foreign key constraints in the database.

---

# 📦 Module: Places and Ecohotels Exploration

---

## US-PLCS-01: Explore Ecotourism Places

| Field | Details |
|-------|---------|
| **Role** | Visitor / Authenticated User |
| **Priority** | High |

> "As a visitor, I want to explore the list of ecotourism places to be able to discover destinations in Risaralda."

### Preconditions
- `GET /api/places` and `GET /api/categories` are available.
- At least one place exists in the database.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | Request is made to `GET /api/places` | — | System returns HTTP 200 with array of places (`id`, `nombre`, `ubicación`, `imagen`, `categorías`) |
| AC2 | A category filter is applied | — | Only places belonging to that category are returned |
| AC3 | User navigates to `/mapa` | — | Interactive map renders markers using the `latitude` and `longitude` fields |
| AC4 | User navigates to thematic routes | — | Places are filtered by their corresponding category slug |

**Thematic routes:** `/paraisosAcuaticos` · `/lugaresMontanosos` · `/parquesYMas` · `/territoriosDelCafe`

### Endpoints
`GET /api/places` · `GET /api/categories` · `GET /api/places/options`

---

## US-PLCS-02: View Place Detail

| Field | Details |
|-------|---------|
| **Role** | Visitor / Authenticated User |
| **Priority** | High |

> "As a visitor, I want to view the detail page of a place to be able to see its full description, photos, available schedules, reviews and make a reservation."

### Preconditions
- `GET /api/places/{id}`, `GET /api/places/{id}/schedules` and `GET /api/places/{id}/reviews` are available.
- The place with the given `id` exists.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | `place_id` is valid | `GET /api/places/{id}` is called | System returns HTTP 200 with the complete place object |
| AC2 | `place_id` is invalid | Endpoint is called | System returns HTTP 404 |
| AC3 | Detail page loads | — | Available schedules from `GET /api/places/{id}/available-schedules` are displayed |
| AC4 | Detail page loads | — | All reviews from `GET /api/places/{id}/reviews` are displayed |
| AC5 | User is authenticated | Views the page | Shows "Book now" and "Add to favorites" buttons |
| AC6 | User is a visitor | Clicks "Book now" | Is redirected to `/login` |

### Endpoints
`GET /api/places/{place}` · `GET /api/places/{place}/schedules` · `GET /api/places/{place}/available-schedules` · `GET /api/places/{id}/reviews`

---

## US-PLCS-03: Explore Ecohotels

| Field | Details |
|-------|---------|
| **Role** | Visitor / Authenticated User |
| **Priority** | High |

> "As a visitor, I want to explore and view ecohotel details to be able to find lodging options in Risaralda."

### Preconditions
- `GET /api/ecohotels` and `GET /api/ecohotels/{id}` are available.
- At least one ecohotel exists in the database.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | Request is made to `GET /api/ecohotels` | — | System returns HTTP 200 with array of ecohotels |
| AC2 | `ecohotel_id` is valid | `GET /api/ecohotels/{id}` is called | System returns HTTP 200 with full details |
| AC3 | Ecohotel detail page loads | — | Reviews from `GET /api/ecohotels/{id}/reviews` are displayed |
| AC4 | `ecohotel_id` is invalid | Endpoint is called | System returns HTTP 404 |

### Endpoints
`GET /api/ecohotels` · `GET /api/ecohotels/{ecohotel}`

### Notes / Restrictions
- Ecohotels are managed exclusively by Admins. The Company module does not cover ecohotels.

---

# 📦 Module: Reservations (User)

---

## US-RES-01: Create a Reservation

| Field | Details |
|-------|---------|
| **Role** | Authenticated User |
| **Priority** | High |

> "As an authenticated user, I want to book a visit to an ecotourism place to be able to secure my spot on a specific date and time."

### Preconditions
- User is authenticated.
- The target place exists and has active schedules.
- `POST /api/reservations` is available.
- No conflicting reservation exists for the same place, date and time.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | Data is valid (`place_id`, `fecha_visita`, `hora_visita`, `personas`, `telefono_contacto`) within an active schedule | User submits the form | System returns HTTP 201 with the created reservation |
| AC2 | Visit date falls on a day outside the place's schedule (e.g.: Sunday if closed) | User submits the form | System returns HTTP 422 |
| AC3 | Requested schedule overlaps with an existing reservation for the same place | User submits the form | System returns HTTP 422 |
| AC4 | A required field is missing | User submits the form | System returns HTTP 422 |
| AC5 | Reservation is successful | — | A `company_reservation` record is automatically created linking the reservation to the responsible company user |
| AC6 | Reservation is successful | — | New reservation appears in the user's history (`GET /api/reservations/my`) with `pending` status |

### Endpoints
`POST /api/reservations` · `GET /api/places/{place}/available-schedules`

### Notes / Restrictions
- Initial status is `pending`.
- Overlap is defined as: same `place_id` + `fecha_visita` + conflicting `hora_visita`.

---

## US-RES-02: View My Reservations

| Field | Details |
|-------|---------|
| **Role** | Authenticated User |
| **Priority** | High |

> "As an authenticated user, I want to view the list of all my reservations to be able to track the status of my past and future visits."

### Preconditions
- User is authenticated.
- `GET /api/reservations/my` is available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | User is authenticated | `GET /api/reservations/my` is called | System returns HTTP 200 with the user's reservations array |
| AC2 | Reservation exists | — | Each object includes: `id`, place name, `fecha_visita`, `hora_visita`, `personas`, `status`, `telefono_contacto` |
| AC3 | User has no reservations | Endpoint is called | System returns HTTP 200 with an empty array |
| AC4 | User is a visitor (no token) | Endpoint is called | System returns HTTP 401 |

### Endpoints
`GET /api/reservations/my` · `GET /api/reservations`

---

## US-RES-03: Cancel a Reservation

| Field | Details |
|-------|---------|
| **Role** | Authenticated User |
| **Priority** | Medium |

> "As an authenticated user, I want to cancel one of my pending reservations so that the time slot is freed and the company is notified."

### Preconditions
- User is authenticated.
- The reservation belongs to the authenticated user.
- `DELETE /api/reservations/{id}` is available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | User owns the reservation | `DELETE /api/reservations/{id}` is called | Reservation is deleted from the database and system returns HTTP 200 |
| AC2 | Reservation belongs to another user | `DELETE` is called | System returns HTTP 403 |
| AC3 | `reservation_id` does not exist | `DELETE` is called | System returns HTTP 404 |
| AC4 | User is a visitor (no token) | `DELETE` is called | System returns HTTP 401 |
| AC5 | Deletion is successful | — | Reservation no longer appears in `GET /api/reservations/my` |

### Endpoint
`DELETE /api/reservations/{reservation}`

---

# 📦 Module: Reviews and Ratings

---

## US-REV-01: Create a Review

| Field | Details |
|-------|---------|
| **Role** | Authenticated User |
| **Priority** | High |

> "As an authenticated user, I want to leave a rating and comment for a place or ecohotel I visited to be able to help other travelers make informed decisions."

### Preconditions
- User is authenticated.
- The target place or ecohotel exists.
- The user has not yet reviewed this place.
- `POST /api/reviews` is available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | Data is valid (`place_id` or `ecohotel_id`, rating 1–5, optional clean comment) | User submits the form | System returns HTTP 201 with the created review |
| AC2 | Comment contains offensive language | User submits the form | System returns HTTP 422 (NoProfanity rule violation) |
| AC3 | User attempts to review the same place a second time | User submits the form | System returns HTTP 422 (unique constraint: one review per user per place) |
| AC4 | Rating is outside the 1–5 range | User submits the form | System returns HTTP 422 |
| AC5 | User is a visitor (no token) | `POST /api/reviews` is called | System returns HTTP 401 |
| AC6 | Review is created | — | Appears in `GET /api/places/{id}/reviews` or `GET /api/ecohotels/{id}/reviews` |

### Endpoints
`POST /api/reviews` · `GET /api/places/{id}/reviews` · `GET /api/ecohotels/{id}/reviews`

### Notes / Restrictions
- The NoProfanity rule also blocks variations with accents, repeated letters and punctuation substitutions.
- `UNIQUE(user_id, place_id)` constraint is enforced at the database level.

---

## US-REV-02: Edit Own Review

| Field | Details |
|-------|---------|
| **Role** | Authenticated User |
| **Priority** | Medium |

> "As an authenticated user, I want to update my existing review to be able to correct or refine my rating and comment."

### Preconditions
- User is authenticated.
- The review belongs to the authenticated user.
- `PUT /api/reviews/{id}` is available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | User owns the review and data is valid | `PUT /api/reviews/{id}` is called | System updates the review and returns HTTP 200 |
| AC2 | User attempts to edit another user's review | `PUT` is called | System returns HTTP 403 |
| AC3 | Updated comment contains offensive language | User submits the form | System returns HTTP 422 |
| AC4 | User is a visitor (no token) | `PUT` is called | System returns HTTP 401 |

### Endpoint
`PUT /api/reviews/{review}`

---

## US-REV-03: Delete a Review

| Field | Details |
|-------|---------|
| **Role** | Authenticated User / Admin |
| **Priority** | Medium |

> "As an authenticated user, I want to delete my own review; as an admin, I want to delete any review to be able to remove inappropriate content from the platform."

### Preconditions
- User is authenticated.
- The review exists.
- `DELETE /api/reviews/{id}` is available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | User owns the review | `DELETE /api/reviews/{id}` is called | Review is deleted and system returns HTTP 200 |
| AC2 | User is an admin | `DELETE /api/reviews/{id}` is called for any review | Review is deleted and system returns HTTP 200 |
| AC3 | User is neither owner nor admin | `DELETE` is called | System returns HTTP 403 |
| AC4 | User is a visitor (no token) | `DELETE` is called | System returns HTTP 401 |
| AC5 | Deletion is successful | — | Review no longer appears in the place or ecohotel listing |

### Endpoint
`DELETE /api/reviews/{review}`

---

# 📦 Module: Favorites

---

## US-FAV-01: Add and Remove Favorites

| Field | Details |
|-------|---------|
| **Role** | Authenticated User |
| **Priority** | Medium |

> "As an authenticated user, I want to save places to my favorites list to be able to quickly access destinations I am interested in revisiting."

### Preconditions
- User is authenticated.
- The target place exists.
- Favorites endpoints are available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | User is authenticated and `place_id` is valid | `POST /api/favorites` is called | Favorite is created and system returns HTTP 201 |
| AC2 | Place is already in favorites | `POST /api/favorites` is called again with the same `place_id` | System returns HTTP 422 (duplicate) |
| AC3 | User is authenticated | `GET /api/favorites` is called | System returns HTTP 200 with their favorites list |
| AC4 | A specific `place_id` is provided | `GET /api/favorites/check/{placeId}` is called | System returns whether the place is or is not in the user's favorites |
| AC5 | Place is in the user's favorites | `DELETE /api/favorites/{placeId}` is called | Favorite is removed and system returns HTTP 200 |
| AC6 | User is a visitor (no token) | Any favorites endpoint is called | System returns HTTP 401 |

### Endpoints
`GET /api/favorites` · `GET /api/favorites/check/{placeId}` · `POST /api/favorites` · `DELETE /api/favorites/{placeId}`

### Notes / Restrictions
- The `UNIQUE(user_id, place_id)` constraint on the favorites table prevents duplicates at the database level.

---

# 📦 Module: Company Dashboard

---

## US-COMP-01: Login and Company Dashboard Access

| Field | Details |
|-------|---------|
| **Role** | Company User |
| **Priority** | High |

> "As a company user, I want to log in and access the company dashboard at `/company/dashboard` to be able to manage my assigned places and reservations."

### Preconditions
- An admin has created the company user and assigned at least one place.
- The frontend route `/company/dashboard` exists.
- The user's `tipo_usuario` field is `'empresa'`.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | Company user credentials are valid | `POST /api/login` is called | System returns HTTP 200 with token and `tipo_usuario = 'empresa'` field |
| AC2 | Login is successful | — | Frontend redirects to `/company/dashboard` |
| AC3 | Company user has no assigned places | Dashboard loads | An informational message is displayed |
| AC4 | A company user attempts to access admin routes (`/admin`) | — | System returns HTTP 403 |
| AC5 | A regular user attempts to access `/company/dashboard` | — | Is blocked or redirected by the frontend route guard |

### Endpoints
`POST /api/login` · `GET /api/company/places` · `GET /api/company/reservations/stats`

---

## US-COMP-02: Manage Company Places

| Field | Details |
|-------|---------|
| **Role** | Company User |
| **Priority** | High |

> "As a company user, I want to view and update the information of my assigned places so that the listings are accurate and up to date."

### Preconditions
- Company user is authenticated.
- At least one place is assigned to the company user.
- Company places endpoints are available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | Company user is authenticated | `GET /api/company/places` is called | System returns HTTP 200 with only the places assigned to them |
| AC2 | `place_id` belongs to the company user | `PUT /api/company/places/{place}` is called with updated data | Place is updated and system returns HTTP 200 |
| AC3 | `place_id` does not belong to the company user | Any modification endpoint is called | System returns HTTP 403 |
| AC4 | Company user attempts to create a place | `POST /api/admin/places` is called | System returns HTTP 403 |
| AC5 | User is a visitor or regular user | Company endpoints are called | System returns HTTP 403 or HTTP 401 |

### Endpoints
`GET /api/company/places` · `GET /api/company/places/{place}` · `PUT /api/company/places/{place}`

---

## US-COMP-03: Manage Place Schedules

| Field | Details |
|-------|---------|
| **Role** | Company User |
| **Priority** | High |

> "As a company user, I want to configure the operating schedules of my places so that the reservation system only allows bookings during valid hours."

### Preconditions
- Company user is authenticated.
- The target place belongs to the company user.
- Schedule CRUD endpoints under `/api/company/places/{place}/schedules` are available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | `place_id` belongs to the company user | `POST /api/company/places/{place}/schedules` is called with day and hour data | Schedule is created and system returns HTTP 201 |
| AC2 | Schedule exists | `PUT /api/company/places/{place}/schedules/{schedule}` is called with updated hours | Schedule is updated and system returns HTTP 200 |
| AC3 | Schedule exists | `DELETE /api/company/places/{place}/schedules/{schedule}` is called | Schedule is deleted and system returns HTTP 200 |
| AC4 | A user attempts to book a place on a day with no active schedule | — | System returns HTTP 422 |
| AC5 | `place_id` does not belong to the company user | Schedule endpoints are called | System returns HTTP 403 |

### Endpoints
`GET /api/company/places/{place}/schedules` · `POST /api/company/places/{place}/schedules` · `PUT /api/company/places/{place}/schedules/{schedule}` · `DELETE /api/company/places/{place}/schedules/{schedule}`

---

## US-COMP-04: View and Manage Incoming Reservations

| Field | Details |
|-------|---------|
| **Role** | Company User |
| **Priority** | High |

> "As a company user, I want to view all reservations for my places and accept or reject them to be able to efficiently manage the visitor flow."

### Preconditions
- Company user is authenticated.
- At least one reservation exists for their places.
- Company reservation endpoints are available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | Company user is authenticated | `GET /api/company/reservations` is called | Only reservations for their assigned places are returned |
| AC2 | Reservation exists | — | Each object includes: client name, email, phone, `fecha_visita`, `hora_visita`, `personas`, `status` |
| AC3 | Reservation is pending | `POST /api/company/reservations/{id}/accept` is called | Status changes to `accepted` and system returns HTTP 200 |
| AC4 | Reservation is pending or accepted | `POST /api/company/reservations/{id}/reject` is called with a `reason_id` | Status changes to `rejected` and system returns HTTP 200 |
| AC5 | Reservation is rejected | `POST /api/company/reservations/{id}/reopen` is called | Status reverts to `pending` and system returns HTTP 200 |
| AC6 | Company user attempts to manage reservations for places that do not belong to them | — | System returns HTTP 403 |

### Endpoints
`GET /api/company/reservations` · `POST /api/company/reservations/{id}/accept` · `POST /api/company/reservations/{id}/reject` · `POST /api/company/reservations/{id}/reopen`

---

## US-COMP-05: View Reservation Statistics

| Field | Details |
|-------|---------|
| **Role** | Company User |
| **Priority** | Medium |

> "As a company user, I want to view statistics for my reservations to be able to understand demand trends and make informed operational decisions."

### Preconditions
- Company user is authenticated.
- `GET /api/company/reservations/stats` is available.
- At least one reservation exists for their places.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | Company user is authenticated | `GET /api/company/reservations/stats` is called | System returns HTTP 200 with counts: `pending`, `accepted`, `rejected` |
| AC2 | A specific `place_id` is provided | `GET /api/company/reservations/place/{placeId}/stats` is called | System returns statistics for that place only |
| AC3 | `place_id` does not belong to the company user | Place-specific statistics endpoint is called | System returns HTTP 403 |
| AC4 | Statistics are queried | — | Data reflects real-time counts based on current statuses in the database |

### Endpoints
`GET /api/company/reservations/stats` · `GET /api/company/reservations/place/{placeId}/stats`

---

# 📦 Module: Administration Panel

---

## US-ADMN-01: Manage Places (Admin CRUD)

| Field | Details |
|-------|---------|
| **Role** | Admin |
| **Priority** | High |

> "As an admin, I want to create, view, update and delete ecotourism places so that the platform's destination catalog remains accurate."

### Preconditions
- User is authenticated with `is_admin = true`.
- Place administration endpoints are available.
- At least one category exists for assignment.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | User is admin with valid data | `POST /api/admin/places` is called | Place is created and system returns HTTP 201 |
| AC2 | User is admin | `GET /api/admin/places` is called | All places are returned with HTTP 200 |
| AC3 | `place_id` is valid | `PUT /api/admin/places/{place}` is called with updated data | Place is updated and system returns HTTP 200 |
| AC4 | `place_id` is valid | `DELETE /api/admin/places/{place}` is called | Place is deleted and system returns HTTP 200 |
| AC5 | User is not admin | Any admin place endpoint is called | System returns HTTP 403 |
| AC6 | Admin requires it | — | Admin can configure place schedules via the `/api/admin/places/{place}/schedules` endpoints |

### Endpoints
`GET /api/admin/places` · `POST /api/admin/places` · `PUT /api/admin/places/{place}` · `DELETE /api/admin/places/{place}` · `GET /api/admin/places/{place}/schedules` · `POST /api/admin/places/{place}/schedules`

---

## US-ADMN-02: Manage Ecohotels (Admin CRUD)

| Field | Details |
|-------|---------|
| **Role** | Admin |
| **Priority** | High |

> "As an admin, I want to manage ecohotel listings so that visitors have access to accurate lodging information."

### Preconditions
- User is authenticated with `is_admin = true`.
- Ecohotel administration endpoints are available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | User is admin with valid data | `POST /api/admin/ecohotels` is called | Ecohotel is created and system returns HTTP 201 |
| AC2 | User is admin | `GET /api/admin/ecohotels` is called | All ecohotels are returned |
| AC3 | `ecohotel_id` is valid | `PUT` or `DELETE` is called | Corresponding operation is executed and system returns HTTP 200 |
| AC4 | User is not admin | Any admin ecohotel endpoint is called | System returns HTTP 403 |

### Endpoints
`GET /api/admin/ecohotels` · `POST /api/admin/ecohotels` · `PUT /api/admin/ecohotels/{ecohotel}` · `DELETE /api/admin/ecohotels/{ecohotel}`

---

## US-ADMN-03: Manage Users (Admin CRUD)

| Field | Details |
|-------|---------|
| **Role** | Admin |
| **Priority** | High |

> "As an admin, I want to create and manage user accounts including company users to be able to control who has access to the platform and to which places."

### Preconditions
- User is authenticated with `is_admin = true`.
- User administration endpoints are available.
- At least one place exists for company user assignment.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | User is admin | `GET /api/admin/users` is called | All users are returned with HTTP 200 |
| AC2 | Admin provides valid data with `tipo_usuario='empresa'` and a places array | `POST /api/admin/users` is called | Company user is created with place assignments and system returns HTTP 201 |
| AC3 | Company user is created | — | A temporary password is returned in the response for the admin to share |
| AC4 | `user_id` is valid | `PUT /api/admin/users/{user}` is called | User is updated and system returns HTTP 200 |
| AC5 | `user_id` is valid | `DELETE /api/admin/users/{user}` is called | User is deleted and system returns HTTP 200 |
| AC6 | User is not admin | Any admin user endpoint is called | System returns HTTP 403 |

### Endpoints
`GET /api/admin/users` · `POST /api/admin/users` · `PUT /api/admin/users/{user}` · `DELETE /api/admin/users/{user}`

### Notes / Restrictions
- Company users are linked to places via the `place_company_user` pivot table with `rol` and `es_principal` fields.
- The admin UI uses `CreateUserModal.jsx`.

---

## US-ADMN-04: Manage Rejection Reasons

| Field | Details |
|-------|---------|
| **Role** | Admin |
| **Priority** | Medium |

> "As an admin, I want to maintain the list of predefined rejection reasons so that company users have appropriate options when rejecting a reservation."

### Preconditions
- User is authenticated with `is_admin = true`.
- Rejection reason endpoints are available.
- The initial seeder has populated the 6 default reasons.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | Admin provides a valid label | `POST /api/admin/rejection-reasons` is called | Reason is created and system returns HTTP 201 |
| AC2 | Any user makes the request | `GET /api/rejection-reasons` is called (public endpoint) | Full list is returned with HTTP 200 |
| AC3 | Admin provides updated data | `PUT /api/admin/rejection-reasons/{reason}` is called | Reason is updated and system returns HTTP 200 |
| AC4 | Admin makes the request | `DELETE /api/admin/rejection-reasons/{reason}` is called | Reason is deleted and system returns HTTP 200 |
| AC5 | User is not admin | `POST`/`PUT`/`DELETE` rejection reason endpoints are called | System returns HTTP 403 |

### Endpoints
`GET /api/rejection-reasons` · `GET /api/admin/rejection-reasons` · `POST /api/admin/rejection-reasons` · `PUT /api/admin/rejection-reasons/{reason}` · `DELETE /api/admin/rejection-reasons/{reason}`

---

## US-ADMN-05: View All Reservations (Global View)

| Field | Details |
|-------|---------|
| **Role** | Admin |
| **Priority** | Medium |

> "As an admin, I want to view all reservations across all places to be able to monitor platform activity and resolve disputes."

### Preconditions
- User is authenticated with `is_admin = true`.
- `GET /api/admin/reservations` is available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | User is admin | `GET /api/admin/reservations` is called | All reservations across all places and users are returned with HTTP 200 |
| AC2 | Reservation exists | — | Each reservation includes: username, place name, `fecha_visita`, `hora_visita`, `personas`, `status` |
| AC3 | User is not admin | `GET /api/admin/reservations` is called | System returns HTTP 403 |

### Endpoint
`GET /api/admin/reservations`

### Notes / Restrictions
- This is a global read-only view. The admin cannot directly modify reservation status from this endpoint (managed by company users).

---

# 📦 Module: Contact and Messaging

---

## US-CTCT-01: Send a Contact Message

| Field | Details |
|-------|---------|
| **Role** | Visitor / Authenticated User |
| **Priority** | Low |

> "As a visitor, I want to send a contact message to the platform administrators to be able to report issues or request information."

### Preconditions
- The contact form is accessible at `/contact` or `/contacto`.
- `POST /api/contacts` is available.

### Acceptance Criteria

| ID | Given that... | When... | Then... |
|----|--------------|---------|---------|
| AC1 | Contact data is valid (`name`, `email`, `phone`, `message`) | `POST /api/contacts` is called | Contact is stored and system returns HTTP 201 |
| AC2 | Request includes a valid Bearer token | Form is submitted | Contact is linked to the authenticated user (`user_id` field) |
| AC3 | No token in the request | Form is submitted | Contact is stored with `user_id = null` |
| AC4 | A required field is missing | Form is submitted | System returns HTTP 422 |
| AC5 | Admin requires it | — | Admin can retrieve all contacts via `GET /api/contacts` |

### Endpoints
`POST /api/contacts` · `POST /api/messages` · `GET /api/contacts` *(admin only)*

### Notes / Restrictions
- The `contacts` table supports nullable `user_id` for visitor submissions.
- `GET /api/contacts` is exclusively accessible to admins.