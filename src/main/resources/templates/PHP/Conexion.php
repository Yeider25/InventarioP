<?php

$host = "localhost"; // Cambia si usas otro host
$user = "root";      // Usuario de la base de datos
$password = "";      // Contraseña del usuario
$database = "proyecto"; // Nombre de la base de datos

$conexion = new mysqli($host, $user, $password, $database);



/*Implementar esta parte del codigo que se requiera dado el caso de un error al momento de inciar sesion 
ya que al momento de hacer modificaciones esto seria necesario para poder generar el acceso al usuario */
/* if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
} else {
    echo "Conexión exitosa.";
}  */

/* $conexion=mysqli_connect("localhost","root","","proyecto");  */



?>