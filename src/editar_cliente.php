<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "Clientes";
$sql = mysqli_query($conexion, "SELECT dp.Id_rol,dp.idusuario,p.idpermisos,p.nombre FROM detalle_permiso dp INNER JOIN permisos p WHERE dp.idusuario = '$id_user' AND dp.idpermisos = p.idpermisos AND p.nombre = '$permiso';");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Status: 301 Moved Permanently");
    header("Location: permisos.php");
    echo"<script language='javascript'>window.location='permisos.php'</script>;";
    exit();
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) ||empty($_POST['identificacion']) || empty($_POST['telefono']) || empty($_POST['correo']) || empty($_POST['direccion'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todo los campos son requeridos</div>';
    } else {
        $idcliente = $_POST['id'];
        $identificacion = $_POST['identificacion'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        $direccion = $_POST['direccion'];
        
            $sql_update = mysqli_query($conexion, "UPDATE cliente SET nombre = '$nombre' , identificacion= '$identificacion', telefono = '$telefono', correo ='$correo',direccion = '$direccion' WHERE idCliente = $idcliente");

            if ($sql_update) {
                $alert = '<div class="alert alert-success" role="alert">Cliente Actualizado correctamente</div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">Error al Actualizar el Cliente</div>';
            }
    }
}
// Mostrar Datos

if (empty($_REQUEST['id'])) {
    header("Location: clientes.php");
}
$idcliente = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM cliente WHERE idCliente = $idcliente");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header("Location: clientes.php");
} else {
    if ($data = mysqli_fetch_array($sql)) {
        $idcliente = $data['idCliente'];
        $nombre = $data['nombre'];
        $identificacion = $data['identificacion'];
        $telefono = $data['telefono'];
        $correo = $data['correo'];
        $direccion = $data['direccion'];
    }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Cliente
                </div>
                <div class="card-body">
                    <form class="" action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <input type="hidden" name="id" value="<?php echo $idcliente; ?>">
                        <div class="form-group">
                            <label for="direccion">CI/RUC</label>
                            <input type="text" placeholder="Ingrese Cedula o Ruc" name="identificacion" class="form-control" id="identificacion" value="<?php echo $identificacion; ?>">
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" placeholder="Ingrese Nombre" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre; ?>">
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="number" placeholder="Ingrese Teléfono" name="telefono" class="form-control" id="telefono" value="<?php echo $telefono; ?>">
                        </div>
                        <div class="form-group">
                            <label for="telefono">E-mail</label>
                            <input type="text" placeholder="Ingrese Email" name="correo" class="form-control" id="correo" value="<?php echo $correo; ?>">
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" placeholder="Ingrese Direccion" name="direccion" class="form-control" id="direccion" value="<?php echo $direccion; ?>">
                        </div>
                        
                        <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar Cliente</button>
                        <a href="clientes.php" class="btn btn-danger">Atras</a>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>