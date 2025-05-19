<?php
session_start();
include('../../resources/templates/PHP/Conexion.php'); 


// Verificacion de usuario y su respectivo rol luego de la optencion de los datos 
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta para verificar las credenciales del usuario
    $consulta = "SELECT id, nombre_usuario, rol FROM usuarios WHERE username = ? AND password = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $_SESSION['id'] = $fila['id'];
        $_SESSION['nombre_usuario'] = $fila['nombre_usuario'];
        $_SESSION['rol'] = $fila['rol'];

        // Redirigir según el rol del usuario
        switch ($fila['rol']) {
            case 1:
                // Redirigir a la página de administrador
                header("Location: /InventarioPHP/src/main/resources/templates/Admin/PrincipalAdmin.php");
                break;
            case 2:
                // Redirigir a la página de coordinador
                header("Location: /InventarioPHP/src/main/resources/templates/Coordinador/PrincipalCoordinador.php");
                break;
            case 3:
                // Redirigir a la página de almacenista
                header("Location: /InventarioPHP/src/main/resources/templates/Almacen/PrincipalAlmacen.php");
                break;
            case 4:
                // Redirigir a la página de personal/instructor
                header("Location: /InventarioPHP/src/main/resources/templates/Personal/PrincipalPersonalCENIGRAF.php");
                break;
            default:
                // Redirigir a una página por defecto o mostrar un mensaje de error
                header("Location: /InventarioPHP/src/main/resources/templates/Error/AccesoDenegado.php");
                break;
        }
        exit();
    } else {
        // Credenciales incorrectas
        echo "Nombre de usuario o contraseña incorrectos.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Sweetalert2-->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--CSS-->
    <link rel="stylesheet" href="CSS/style_index_admin.css">
    <!--Icons-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>>
    <script>
    document.addEventListener('DOMContentLoaded', function(event) {
        var errorMessage = localStorage.getItem('error_inactividad');
        if (errorMessage) {
            Swal.fire({
                icon: 'warning',
                title: '¡UPS!',
                text: errorMessage
            });
            localStorage.removeItem('error_inactividad');
        }

        var errorMessage = localStorage.getItem('error_acceso');
        if (errorMessage) {
            Swal.fire({
                icon: 'error',
                title: 'Acceso Denegado',
                text: errorMessage
            });
            localStorage.removeItem('error_acceso');
        }
    });
    </script>
   <!-- Favicon -->
 <link rel="icon" type="image/x-icon" href="../images/logo-de-Sena-sin-fondo-Blanco.png">
    <title>SENA-Gestor de Materiales</title>
</head>
<body>
<div class="container">
    <div class="screen">
        <div class="screen__content">
            <form class="login" action="PHP/Session.php" method="post" id="loginForm">
                <div class="login__field">
                    <i class="login__icon fas fa-user"></i>
                    <input type="text" class="login__input" placeholder="Usuario" name="usuario" id="usuario" required>
                </div>
                <div class="login__field">
                    <i class="login__icon fas fa-lock"></i>
                    <input type="password" class="login__input" placeholder="Contraseña" name="contrasena" required>
                </div>
                <button type="submit" class="button login__submit">
                    <span class="button__text">INICIAR SESIÓN</span>
                    <i class="button__icon fas fa-chevron-right"></i>
                </button>
            </form>
            <div class="social-login">
                <div class="social-icons">
                    <img src="images/logo-de-Sena-sin-fondo-Blanco.png" width="120px">
                </div>
            </div>
        </div>
        <div class="screen__background">
            <span class="screen__background__shape screen__background__shape4"></span>
            <span class="screen__background__shape screen__background__shape2"></span>
            <span class="screen__background__shape screen__background__shape1"></span>
        </div>
    </div>
</div>
<script src="js/Login.js"></script>
</body>
</html>
