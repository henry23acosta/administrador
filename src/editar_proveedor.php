<?php
include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso ='Proveedores';
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
  $idProveedores = $_GET['id'];
  $identificacion = $_POST['identificacion'];
  $nombre = $_POST['nombre'];
  $direccion = $_POST['direccion'];
  $telefono= $_POST['telefono'];
  $email = $_POST['email'];
  $Cuenta_bancaria = $_POST['Cuenta_bancaria'];
  if (empty($identificacion) || empty($nombre) || empty($direccion) || empty($telefono) ||empty($email) ||empty($Cuenta_bancaria) ) {
    $alert = '<div class="alert alert-primary" role="alert">
              Todo los campos son requeridos
            </div>';
  } else {
    $query_update = mysqli_query($conexion, "UPDATE proveedores SET identificacion='$identificacion', nombre='$nombre', direccion='$direccion', telefono='$telefono',email='$email', Cuenta_bancaria='$Cuenta_bancaria'   WHERE idProveedores = $idProveedores");
    if ($query_update) {
      $alert = '<div class="alert alert-primary" role="alert">
                 Proveedor Actualizado correctamente
            </div>';
    } else {
      $alert = '<div class="alert alert-primary" role="alert">
                Error al Modificar
              </div>';
    }
  }
}

// Validar proveedor

if (empty($_REQUEST['id'])) {
  header("Location: proveedores.php");
} else {
  $idProveedores = $_REQUEST['id'];
  if (!is_numeric($idProveedores)) {
    header("Location: proveedores.php");
  }
  $query_proveedor = mysqli_query($conexion, "SELECT * FROM proveedores WHERE idProveedores = $idProveedores");
  $result_proveedor = mysqli_num_rows($query_proveedor);

  if ($result_proveedor > 0) {
    $data_proveedor = mysqli_fetch_assoc($query_proveedor);
  } else {
    header("Location: proveedores.php");
  }
}
?>
<div class="row">
  <div class="col-lg-6 m-auto">

    <div class="card">
      <div class="card-header bg-primary text-white">
        Modificar proveedor
      </div>
      <div class="card-body">
        <form action="" method="post">
          <?php echo isset($alert) ? $alert : ''; ?>
          <div class="form-group">
            <label for="identificacion">Identificacion</label>
            <input type="text" class="form-control" placeholder="Ingrese Identificacion" name="identificacion" id="identificacion" value="<?php echo $data_proveedor['identificacion']; ?>">
          </div>
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" placeholder="Ingrese nombre" name="nombre" id="nombre" class="form-control" value="<?php echo $data_proveedor['nombre']; ?>">
          </div>
          <div class="form-group">
            <label for="direccion">Direccion</label>
            <input type="text" class="form-control" placeholder="Ingrese Direccion" name="direccion" id="direccion" value="<?php echo $data_proveedor['direccion']; ?>">
          </div>
          <div class="form-group">
            <label for="telefono">Telefono</label>
            <input type="text" class="form-control" placeholder="Ingrese Telefono" name="telefono" id="telefono" value="<?php echo $data_proveedor['telefono']; ?>">
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" placeholder="Ingrese Email" name="email" id="email" value="<?php echo $data_proveedor['email']; ?>">
          </div>
          <div class="form-group">
            <label for="Cuenta_bancaria">Cuenta bancaria</label>
            <input type="text" placeholder="IngreseCuenta Bancaria" class="form-control" name="Cuenta_bancaria" id="Cuenta_bancaria" value="<?php echo $data_proveedor['Cuenta_bancaria']; ?>">
          </div>
          <input type="submit" value="Actualizar Proveedor" class="btn btn-primary">
          <a href="proveedores.php" class="btn btn-danger">Atras</a>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once "includes/footer.php"; ?>
