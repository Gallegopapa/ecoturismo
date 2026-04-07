const API_PLACES = '/api/admin/places'
const API_USERS = '/api/admin/users'

// Mapeo nombre de lugar -> imagen local
const PLACE_IMAGE_MAP = {
    'lago de la pradera': '/imagenes/Lago.jpeg',
    'la laguna del otún': '/imagenes/laguna.jpg',
    'laguna del otún': '/imagenes/laguna.jpg',
    'chorros de don lolo': '/imagenes/lolo-2.jpg',
    'termales de santa rosa': '/imagenes/termaales.jpg',
    'parque acuático consota': '/imagenes/consota.jpg',
    'balneario los farallones': '/imagenes/farallones.jpeg',
    'cascada los frailes': '/imagenes/frailes3.jpg',
    'río san josé': '/imagenes/sanjose3.jpg',
    'rio san jose': '/imagenes/sanjose3.jpg',
    'alto del nudo': '/imagenes/nudo.jpg',
    'alto del toro': '/imagenes/toro.jpg',
    'la divisa de don juan': '/imagenes/divisa3.jpeg',
    'cerro batero': '/imagenes/batero.jpg',
    'reserva forestal la nona': '/imagenes/lanona5.jpg',
    'reserva natural cerro gobia': '/imagenes/gobia.jpg',
    'kaukita bosque reserva': '/imagenes/kaukita3.jpg',
    'kaukitá bosque reserva': '/imagenes/kaukita3.jpg',
    'reserva natural dmi agualinda': '/imagenes/distritomanejo8.jpg',
    'parque nacional natural tatamá': '/imagenes/tatama.jpg',
    'parque nacional natural tatama': '/imagenes/tatama.jpg',
    'parque las araucarias': '/imagenes/araucarias.jpg',
    'parque regional natural cuchilla de san juan': '/imagenes/cuchilla.jpg',
    'parque natural regional santa emilia': '/imagenes/santaemilia2.jpg',
    'jardín botánico utp': '/imagenes/jardin.jpeg',
    'jardin botanico utp': '/imagenes/jardin.jpeg',
    'jardín botánico de marsella': '/imagenes/jardinmarsella2.jpg',
    'jardin botanico de marsella': '/imagenes/jardinmarsella2.jpg',
    'piedras marcadas': '/imagenes/piedras5.jpg',
    'barbas bremen': '/imagenes/paisaje5.jpg',
    'santuario otún quimbaya': '/imagenes/paisaje2.jpg',
    'santuario otun quimbaya': '/imagenes/paisaje2.jpg',
    'bioparque mariposario bonita farm': '/imagenes/ukumari.jpg',
    'parque bioflora en finca turística los rosales': '/imagenes/parquecafe.jpg',
    'parque bioflora en finca turistica los rosales': '/imagenes/parquecafe.jpg',
    'eco hotel paraiso real': '/imagenes/paisaje4.jpg',
    'termales de san vicente': '/imagenes/termales.jpg',
    'voladero el zarzo': '/imagenes/mirador5.jpg',
}

function normalizeName(str) {
    if (!str) return ''
    return str.toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/\s+/g, ' ')
        .trim()
}

function resolvePlaceImage(place) {
    const img = place.image ? String(place.image).trim() : ''
    // Si tiene imagen de storage, usarla primero
    if (img && (img.startsWith('/storage/') || img.includes('/storage/places/'))) {
        return img
    }
    // Si tiene imagen en /imagenes/, usarla
    if (img && img.startsWith('/imagenes/')) {
        return img
    }
    // Buscar por nombre en el mapeo
    const normalized = normalizeName(place.name)
    if (PLACE_IMAGE_MAP[normalized]) {
        return PLACE_IMAGE_MAP[normalized]
    }
    // También intentar normalización con tildes
    const normalizedWithTildes = place.name ? place.name.toLowerCase().trim() : ''
    if (PLACE_IMAGE_MAP[normalizedWithTildes]) {
        return PLACE_IMAGE_MAP[normalizedWithTildes]
    }
    return null
}

const $ = id => document.getElementById(id)

// Obtener token CSRF
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || ''
}

// Función para mostrar mensajes
function showMessage(message, type = 'success') {
    const statusMsg = $('status-message')
    statusMsg.textContent = message
    statusMsg.className = `status-message ${type}`
    setTimeout(() => {
        statusMsg.className = 'status-message'
    }, 3000)
}

// Tabs
const tabPlaces = $('tab-places')
const tabUsers = $('tab-users')
const placesPanel = document.getElementById('places-panel')
const usersPanel = document.getElementById('users-panel')

tabPlaces.addEventListener('click', () => {
    tabPlaces.classList.add('active')
    tabUsers.classList.remove('active')
    placesPanel.style.display = 'block'
    usersPanel.style.display = 'none'
})

tabUsers.addEventListener('click', () => {
    tabUsers.classList.add('active')
    tabPlaces.classList.remove('active')
    usersPanel.style.display = 'block'
    placesPanel.style.display = 'none'
    loadUsers()
    // Mostrar campo de contraseña al abrir el panel de usuarios
    const passwordLabel = document.getElementById('user-password-label')
    if (passwordLabel && !$('user-id').value) {
        passwordLabel.style.display = 'block'
    }
})

// Places
const placesTbody = document.querySelector('#places-table tbody')
const placeForm = $('place-form')

async function fetchPlaces() {
    try {
        const res = await fetch(API_PLACES, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Accept': 'application/json'
            }
        })
        
        if (!res.ok) {
            if (res.status === 401) {
                window.location.href = '/login'
                return
            }
            throw new Error('Error al cargar lugares')
        }
        
        const data = await res.json()
        const list = Array.isArray(data) ? data : (data.places || data.data || [])
        renderPlaces(list)
    } catch (error) {
        showMessage('Error al cargar lugares', 'error')
    }
}

function renderPlaces(list) {
    placesTbody.innerHTML = ''
    if (list.length === 0) {
        placesTbody.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:20px;">No hay lugares registrados</td></tr>'
        return
    }
    
    list.forEach(p => {
        const imgSrc = resolvePlaceImage(p)
        const tr = document.createElement('tr')
        tr.innerHTML = `
            <td>${imgSrc ? `<img src="${imgSrc}" class="thumb" alt="${escapeHtml(p.name)}" onerror="this.src='/imagenes/placeholder.svg'"/>` : '<span style="color:#999;">Sin imagen</span>'}</td>
            <td>${escapeHtml(p.name)}</td>
            <td>${escapeHtml(p.location || '')}</td>
            <td>
                <button data-id="${p.id}" class="edit">Editar</button>
                <button data-id="${p.id}" class="delete">Borrar</button>
            </td>`
        placesTbody.appendChild(tr)
    })
}

function escapeHtml(s) {
    if (!s) return ''
    return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
}

placeForm.addEventListener('submit', async (e) => {
    e.preventDefault()
    const id = $('place-id').value
    const formData = new FormData()
    const name = $('name').value.trim()
    
    if (!name) {
        showMessage('El nombre es requerido', 'error')
        return
    }
    
    formData.append('name', name)
    formData.append('location', $('location').value.trim())
    formData.append('description', $('description').value.trim())
    
    const file = $('image').files[0]
    if (file) {
        formData.append('image', file)
    }

    try {
        const url = id ? `${API_PLACES}/${id}` : API_PLACES
        const method = id ? 'PUT' : 'POST'
        
        const res = await fetch(url, {
            method: method,
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: formData
        })

        if (!res.ok) {
            const error = await res.json()
            throw new Error(error.message || 'Error al guardar lugar')
        }

        showMessage(id ? 'Lugar actualizado correctamente' : 'Lugar creado correctamente')
        resetPlaceForm()
        fetchPlaces()
    } catch (error) {
        showMessage(error.message || 'Error al guardar lugar', 'error')
    }
})

$('cancel').addEventListener('click', () => resetPlaceForm())

placesTbody.addEventListener('click', async (e) => {
    const id = e.target.getAttribute('data-id')
    if (!id) return
    
    if (e.target.classList.contains('delete')) {
        if (!confirm('¿Estás seguro de borrar este lugar?')) return
        
        try {
            const res = await fetch(`${API_PLACES}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                }
            })

            if (!res.ok) {
                throw new Error('Error al eliminar lugar')
            }

            showMessage('Lugar eliminado correctamente')
            fetchPlaces()
        } catch (error) {
            showMessage('Error al eliminar lugar', 'error')
        }
    }
    
    if (e.target.classList.contains('edit')) {
        try {
            const res = await fetch(`${API_PLACES}/${id}`, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Accept': 'application/json'
                }
            })

            if (!res.ok) {
                throw new Error('Error al cargar lugar')
            }

            const data = await res.json()
            const p = data.place || data
            
            $('place-id').value = p.id
            $('name').value = p.name || ''
            $('location').value = p.location || ''
            $('description').value = p.description || ''
            $('form-title').textContent = 'Editar lugar'
        } catch (error) {
            showMessage('Error al cargar lugar', 'error')
        }
    }
})

function resetPlaceForm() {
    $('place-id').value = ''
    $('name').value = ''
    $('location').value = ''
    $('description').value = ''
    $('image').value = ''
    $('form-title').textContent = 'Crear lugar'
}

// Users
const usersTbody = document.querySelector('#users-table tbody')
const userForm = $('user-form')

async function loadUsers() {
    try {
        const res = await fetch(API_USERS, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Accept': 'application/json'
            }
        })
        
        if (!res.ok) {
            if (res.status === 401) {
                window.location.href = '/login'
                return
            }
            throw new Error('Error al cargar usuarios')
        }
        
        const data = await res.json()
        const list = Array.isArray(data) ? data : (data.users || data.data || [])
        renderUsers(list)
    } catch (error) {
        showMessage('Error al cargar usuarios', 'error')
    }
}

function renderUsers(list) {
    usersTbody.innerHTML = ''
    if (list.length === 0) {
        usersTbody.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:20px;">No hay usuarios registrados</td></tr>'
        return
    }
    
    list.forEach(u => {
        const tr = document.createElement('tr')
        tr.innerHTML = `
            <td>${escapeHtml(u.name)}</td>
            <td>${escapeHtml(u.email || '')}</td>
            <td>${escapeHtml(u.is_admin ? 'admin' : 'user')}</td>
            <td>
                <button data-id="${u.id}" class="user-edit">Editar</button>
                <button data-id="${u.id}" class="user-delete">Borrar</button>
            </td>`
        usersTbody.appendChild(tr)
    })
}

userForm.addEventListener('submit', async (e) => {
    e.preventDefault()
    const id = $('user-id').value
    const password = $('user-password').value.trim()
    
    const payload = {
        name: $('user-name').value.trim(),
        email: $('user-email').value.trim(),
        is_admin: $('user-role').value === 'admin'
    }
    
    // Solo incluir contraseña si se proporciona (y no está vacía)
    if (password) {
        payload.password = password
    }
    
    if (!payload.name || !payload.email) {
        showMessage('Nombre y email son requeridos', 'error')
        return
    }

    try {
        const url = id ? `${API_USERS}/${id}` : API_USERS
        const method = id ? 'PUT' : 'POST'
        
        const res = await fetch(url, {
            method: method,
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify(payload)
        })

        if (!res.ok) {
            const error = await res.json()
            throw new Error(error.message || 'Error al guardar usuario')
        }

        const response = await res.json()
        
        // Si se creó un nuevo usuario y se generó una contraseña, mostrarla
        if (!id && response.generated_password) {
            const passwordMsg = `Usuario creado correctamente.\n\n` +
                `CONTRASEÑA GENERADA:\n${response.generated_password}\n\n` +
                `IMPORTANTE: Guarda esta contraseña y compártela con el usuario. No se mostrará nuevamente.`
            alert(passwordMsg)
            showMessage('Usuario creado correctamente. Revisa la contraseña generada.', 'success')
        } else {
            showMessage(id ? 'Usuario actualizado correctamente' : 'Usuario creado correctamente')
        }
        
        resetUserForm()
        loadUsers()
    } catch (error) {
        showMessage(error.message || 'Error al guardar usuario', 'error')
    }
})

$('user-cancel').addEventListener('click', () => resetUserForm())

usersTbody.addEventListener('click', async (e) => {
    const id = e.target.getAttribute('data-id')
    if (!id) return
    
    if (e.target.classList.contains('user-delete')) {
        if (!confirm('¿Estás seguro de borrar este usuario?')) return
        
        try {
            const res = await fetch(`${API_USERS}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                }
            })

            if (!res.ok) {
                throw new Error('Error al eliminar usuario')
            }

            showMessage('Usuario eliminado correctamente')
            loadUsers()
        } catch (error) {
            showMessage('Error al eliminar usuario', 'error')
        }
    }
    
    if (e.target.classList.contains('user-edit')) {
        try {
            const res = await fetch(`${API_USERS}/${id}`, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Accept': 'application/json'
                }
            })

            if (!res.ok) {
                throw new Error('Error al cargar usuario')
            }

            const data = await res.json()
            const u = data.user || data
            
            $('user-id').value = u.id
            $('user-name').value = u.name || ''
            $('user-email').value = u.email || ''
            $('user-password').value = ''
            $('user-role').value = u.is_admin ? 'admin' : 'user'
            
            // Ocultar campo de contraseña al editar (solo se puede cambiar si se proporciona)
            const passwordLabel = document.getElementById('user-password-label')
            if (passwordLabel) {
                passwordLabel.style.display = 'block'
            }
        } catch (error) {
            showMessage('Error al cargar usuario', 'error')
        }
    }
})

function resetUserForm() {
    $('user-id').value = ''
    $('user-name').value = ''
    $('user-email').value = ''
    $('user-password').value = ''
    $('user-role').value = 'user'
    // Mostrar campo de contraseña solo al crear (no al editar)
    const passwordLabel = document.getElementById('user-password-label')
    if (passwordLabel) {
        passwordLabel.style.display = 'block'
    }
}

// Init
fetchPlaces()

