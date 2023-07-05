<?php
session_start();
if (!empty($_SESSION['active'])) {
    header('location: src/');
} else {
    if (!empty($_POST)) {
        $alert = '';
        if (empty($_POST['usuario']) || empty($_POST['password'])) {
            $alert = '<div class="alert alert-danger text-center">Ingrese su Usuario o Contraseña</div>';
        } else {
            require_once "conexion.php";
            $user = mysqli_real_escape_string($conexion, $_POST['usuario']);
            $password = md5(mysqli_real_escape_string($conexion, $_POST['password']));
            $query = mysqli_query($conexion, "SELECT * FROM login WHERE user = '$user' AND password = '$password' AND (Id_rol = 1   OR Id_rol = 3)");
            mysqli_close($conexion);
            $resultado = mysqli_num_rows($query);
            if ($resultado > 0) {
                
                $dato = mysqli_fetch_array($query);
                $_SESSION['active'] = true;
                $_SESSION['idUser'] = $dato['idusuario'];
                $_SESSION['user'] = $dato['user'];
                $_SESSION['idnegocio'] = $dato['id_negocio'];
                $_SESSION['Id_rol'] = $dato['Id_rol'];
                header('location: src/');
            } else {
                $alert = '<div class="alert alert-danger text-center">Usuario o Contraseña Incorrecta</div>';
                session_destroy();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Ventas</title>
        <link href="./assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="assets/fontawesome/css/fontawesome.css" rel="stylesheet" />
        <link href="assets/fontawesome/css/brands.css" rel="stylesheet" />
        <link href="./assets/fontawesome/css/solid.css" rel="stylesheet" />
        <link href="./assets/css/login.css" rel="stylesheet" />
        <link href="./assets/fontawesome/css/regular.css" rel="stylesheet" />
        <script src="./assets/bootstrap/js/bootstrap.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="body">
        <div class="content-login">
            <div class="login">
                <div class="card">
                    <div class="card-header text-center">
                        <h5 class="">Iniciar Sesión</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label class="form-label" for="usuario">Usuario</label>
                                <div class="input-group input-group-sm mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="password">Contraseña</label>
                                <div class="input-group input-group-sm mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"><i class="fas fa-key"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña">
                                </div>
                            </div>
                            <?php echo isset($alert) ? $alert : ''; ?>
                            <div>
                            </div>
                            <div class="d-grid gap-2">
                                <button href="#"  class="btn-neon" type="submit" type="button">
                                <span id="span1"></span>
                                <span id="span2"></span>
                                <span id="span3"></span>
                                <span id="span4"></span>LOGIN</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
        <script src="./assets/js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
        <script src="./assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="./assets/js/scripts.js"></script>
    </body>
</html>