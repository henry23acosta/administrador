<?php
include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "admin";
$sql = mysqli_query($conexion, "SELECT * FROM rol r INNER JOIN autentificacion a  WHERE r.Id_rol = a.Id_rol  AND a.idusuario = $id_user AND r.Descripcion = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
  header("Location: permisos.php");
}
if (!empty($_POST)) {
  $alert = "";
  if (empty($_POST['nombre']) ||  empty($_POST['ruc']) || empty($_POST['direccion']) || empty($_POST['telefono']) || empty($_POST['correo']) ) {
    $alert = '<div class="alert alert-primary" role="alert">
              Todo los campos son requeridos
            </div>';
  } else {
    $id_negocio = $_GET['id'];
    $nombre = $_POST['nombre'];
    $ruc = $_POST['ruc'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $query_update = mysqli_query($conexion, "UPDATE negocio SET nombre = '$nombre', ruc='$ruc', direccion= '$direccion', telefono='$telefono', correo='$correo'  WHERE id_negocio = $id_negocio");
    if ($query_update) {
      $alert = '<div class="alert alert-primary" role="alert">
              Negocio Modificado
            </div>';
    } else {
      $alert = '<div class="alert alert-primary" role="alert">
                Error al Modificar
              </div>';
    }
  }
}

// Validar Negocio

if (empty($_REQUEST['id'])) {
  header("Location: negocio.php");
} else {
  $id_negocio = $_REQUEST['id'];
  if (!is_numeric($id_negocio)) {
    header("Location: negocio.php");
  }
  $query_negocio = mysqli_query($conexion, "SELECT * FROM negocio WHERE id_negocio = $id_negocio");
  $result_negocio = mysqli_num_rows($query_negocio);

  if ($result_negocio > 0) {
    $data_negocio = mysqli_fetch_assoc($query_negocio);
  } else {
    header("Location: negocio.php");
  }
}
?>
<div class="row">
  <div class="col-lg-6 m-auto">

    <div class="card">
      <div class="card-header bg-primary text-white">
        Modificar Negocio
      </div>
      <div class="card-body">
        <form action="" method="post">
          <?php echo isset($alert) ? $alert : ''; ?>
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" placeholder="Ingrese nuevo nombre" name="nombre" id="nombre" class="form-control" value="<?php echo $data_negocio['nombre']; ?>">
          </div>
          <div class="form-group">
            <label for="ruc">Ruc</label>
            <input type="text" class="form-control" placeholder="Ingrese Ruc" name="ruc" id="ruc" value="<?php echo $data_negocio['ruc']; ?>">
          </div>
          <div class="form-group">
            <label for="direccion">Direccion</label>
            <input type="text" placeholder="Ingrese  Direccion" name="direccion" id="direccion" class="form-control" value="<?php echo $data_negocio['direccion']; ?>">
          </div>
          <div class="form-group">
            <label for="telefono">Telefono</label>
            <input type="text" class="form-control" placeholder="Ingrese Telefono" name="telefono" id="telefono" value="<?php echo $data_negocio['telefono']; ?>">
          </div>
          <div class="form-group">
            <label for="correo">Correo</label>
            <input type="text" placeholder="Ingrese Correo" name="correo" id="correo" class="form-control" value="<?php echo $data_negocio['correo']; ?>">
          </div>
          <input type="submit" value="Actualizar Negocio" class="btn btn-primary">
          <a href="negocio.php" class="btn btn-danger">Atras</a>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once "includes/footer.php"; ?>