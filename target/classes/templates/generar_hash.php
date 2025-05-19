<?php
// Generar hash de contraseña
// Este archivo se puede utilizar para obtener el hash
// de una contraseña que luego se puede insertar en la base de datos

// Se debe guardar este archivo fuera de la carpeta privada 
// para poder encontrarlo y utilizarlo cuando se necesite en el host local

$contrasena = 'Admin123';
$contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
echo $contrasena_hash;
?>