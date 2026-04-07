<?php

$host = 'localhost';
$usuario = 'root';
$contraseña = '';
$base_datos = 'ecoturismo';

$conexion = new mysqli($host, $usuario, $contraseña, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}


?>