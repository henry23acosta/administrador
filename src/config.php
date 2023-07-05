<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "Clientes";
$idnegocio = $_SESSION['idnegocio'];
$sql = mysqli_query($conexion, "SELECT dp.Id_rol,dp.idusuario,p.idpermisos,p.nombre FROM detalle_permiso dp INNER JOIN permisos p WHERE dp.idusuario = '$id_user' AND dp.idpermisos = p.idpermisos AND p.nombre = '$permiso';");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Status: 301 Moved Permanently");
    header("Location: permisos.php");
    echo"<script language='javascript'>window.location='permisos.php'</script>;";
    exit();
}


if ($_POST) {
    $alert = '';
    if (empty($_POST['nombre']) || empty($_POST['user']) || empty($_POST['telefono']) || empty($_POST['correo'])) {
        $alert = '<div class="alert alert-danger" role="alert">
            Todo los campos son obligatorios
        </div>';
    }else{
        $nombre = $_POST['nombre'];
        $user = $_POST['user'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        $id = $_POST['id'];
        $update = mysqli_query($conexion, "UPDATE usuario SET nombre = '$nombre',user = '$user',telefono = '$telefono', correo = '$correo' WHERE idusuario = $id");
        if ($update) {
            $alert = '<div class="alert alert-success" role="alert">
            Datos Modificados
        </div>';

        }
        
    }
}
$query = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $id_user");
$data = mysqli_fetch_assoc($query);
?>

<div class="row">
<div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Datos del Usuariodel C.C.P.
                </div>
                <div class="card-body">
                    <form action="" method="post" class="p-3">
                        <div class="form-group">
                            <label>Nombre:</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo $data['nombre']; ?>" id="nombre" placeholder="Nombre del Usuario" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Usuario:</label>
                            <input type="hidden" name="id" id="id" value="<?php echo $data['idusuario'] ?>">
                            <input type="text" name="user" class="form-control" value="<?php echo $data['user']; ?>" id="user" placeholder="Nombre del Usuario" required class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label>Teléfono:</label>
                            <input type="number" name="telefono" class="form-control" value="<?php echo $data['telefono']; ?>" id="telefono" placeholder="Teléfono del Usuario" required>
                        </div>
                        <div class="form-group">
                            <label>Correo Electrónico:</label>
                            <input type="email" name="correo" class="form-control" value="<?php echo $data['correo']; ?>" id="correo" placeholder="Correo del Usuario" required>
                        </div>
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <div>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Modificar Datos</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
</div>
<?php include_once "includes/footer.php"; ?>