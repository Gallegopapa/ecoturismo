// Mostrar/Ocultar Contraseña - Login
const mostrarContrasena = document.getElementById('mostrarContraseña');
const inputPassword = document.getElementById('password');

if (mostrarContrasena && inputPassword) {
    mostrarContrasena.addEventListener('click', function(e) {
        e.preventDefault();
        if (inputPassword.type === 'password') {
            inputPassword.type = 'text';
            mostrarContrasena.style.backgroundImage = 'url(/imagenes/abrir-ojo.png)';
        } else {
            inputPassword.type = 'password';
            mostrarContrasena.style.backgroundImage = 'url(/imagenes/cerrar-ojo.png)';
        }
    });
}

// Mostrar/Ocultar Contraseña - Registro (si existe)
const mostrarContrasena2 = document.getElementById('mostrarContraseña2');
const inputPassword2 = document.getElementById('password_confirmation');

if (mostrarContrasena2 && inputPassword2) {
    mostrarContrasena2.addEventListener('click', function(e) {
        e.preventDefault();
        if (inputPassword2.type === 'password') {
            inputPassword2.type = 'text';
            mostrarContrasena2.style.backgroundImage = 'url(/imagenes/abrir-ojo.png)';
        } else {
            inputPassword2.type = 'password';
            mostrarContrasena2.style.backgroundImage = 'url(/imagenes/cerrar-ojo.png)';
        }
    });
}

// Validación de contraseña en registro
const formularioRegistro = document.getElementById('formulario-registro');
if (formularioRegistro) {
    formularioRegistro.addEventListener('submit', function(e) {
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        
        if (password && passwordConfirmation && password.value !== passwordConfirmation.value) {
            e.preventDefault();
            alert('Las contraseñas no coinciden');
            passwordConfirmation.focus();
        }
    });
}
