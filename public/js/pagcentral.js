let carga = document.getElementById('body');
window.onload = function(){
    carga.style.opacity = '1';
    carga.style.transition = '2s'
}
// setTimeout(()=>{
//     let pregunta = document.getElementById('pregunta');
//     pregunta.style.display = 'block';
//     // pregunta.style.opacity = '1';
// },6000);
let aceptar = document.getElementById('aceptar');
let rechazar = document.getElementById('rechazar');
let cookies = document.getElementById('cookies');
let contacto = document.getElementById('contacto');
let login = document.getElementById('login')
let popup = document.getElementById('popup');

login.addEventListener('click', (e)=>{
    e.preventDefault();
    popup.style.display = 'flex';
    popup.classList.remove('hidden');
    // let pregunta = document.getElementById('pregunta');
    // pregunta.style.display = 'none';
});
document.getElementById('register').addEventListener('click', () => {
    window.location.href = '/Proyecto_Ecoturismo/Proyecto_Formativo/registro.php';
});

document.getElementById('loggin').addEventListener('click', () => {
    window.location.href = '/login.html';
});

document.getElementById('guest').addEventListener('click', () => {
    // Ocultar el popup
    // const popup = document.getElementById('popup');
    popup.classList.add('hidden');
    popup.style.display = 'none';
});
let = boton_si = document.getElementById('si');
let = boton_no = document.getElementById('no');
boton_si.addEventListener('click',function(){
    window.location.href = 'pagcentral2.html'
});
boton_no.addEventListener('click',function(){
    popup.classList.remove('hidden');
    let pregunta = document.getElementById('pregunta');
    pregunta.style.display = 'none';
});
// Manejar los botones del popup
// let lugaresLink = document.getElementById('lugares-link');
// lugaresLink.addEventListener('click', function () {
//     this.style.backgroundColor = '#27ae60'; // Cambia el color de fondo al hacer clic
//     this.style.color = '#fff'; // Cambia el color del texto al hacer clic
//     let menu = document.getElementById('menu-desplegable');

//     // Alternar la visibilidad del menú
//     if (menu.style.display === 'block') {
//         menu.style.display = 'none'; // Ocultar el menú si está visible
//         lugaresLink.style.backgroundColor = 'white'; // Restablecer el color de fondo
//         lugaresLink.style.color = '#34495e'; // Restablecer el color del texto
//     } else {
//         menu.style.display = 'block'; // Mostrar el menú si está oculto
//         // menu.style.transition = '0.5s';
//     }
// });
lugaresLink.addEventListener('mouseover', function () {
    this.style.backgroundColor = '#2ecc71'; // Cambia el color de fondo al pasar el mouse
    this.style.color = '#fff'; // Cambia el color del texto al pasar el mouse
});
lugaresLink.addEventListener('mouseout', function () {
    this.style.backgroundColor = 'rgba(255,255,255,0.01)'; // Restablecer el color de fondo al salir el mouse
    this.style.color = '#34495e'; // Restablecer el color del texto al salir el mouse
});