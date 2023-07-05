<?php
include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso ='Categorias';
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
  if (empty($_POST['Nombre']) || empty($_POST['Descripcion'])) {
    $alert = '<div class="alert alert-primary" role="alert">
              Todo los campos son requeridos
            </div>';
  } else {
    $idCategoria = $_GET['id'];
    $Nombre = $_POST['Nombre'];
    $Descripcion = $_POST['Descripcion'];
    $query_update = mysqli_query($conexion, "UPDATE categoria SET Nombre = '$Nombre', Descripcion= '$Descripcion'  WHERE idCategoria = $idCategoria");
    if ($query_update) {
      $alert = '<div class="alert alert-primary" role="alert">
              Categoria Modificado
            </div>';
    } else {
      $alert = '<div class="alert alert-primary" role="alert">
                Error al Modificar
              </div>';
    }
  }
}

// Validar Categoria

if (empty($_REQUEST['id'])) {
  header("Location: categoria.php");
} else {
  $idCategoria = $_REQUEST['id'];
  if (!is_numeric($idCategoria)) {
    header("Location: categoria.php");
  }
  $query_categoria = mysqli_query($conexion, "SELECT * FROM categoria WHERE idCategoria = $idCategoria");
  $result_categoria = mysqli_num_rows($query_categoria);

  if ($result_categoria > 0) {
    $data_categoria = mysqli_fetch_assoc($query_categoria);
  } else {
    header("Location: categoria.php");
  }
}
?>
<div class="row">
  <div class="col-lg-6 m-auto">

    <div class="card">
      <div class="card-header bg-primary text-white">
        Modificar Categoria
      </div>
      <div class="card-body">
        <form action="" method="post">
          <?php echo isset($alert) ? $alert : ''; ?>
          <div class="form-group">
            <label for="codigo">Nombre</label>
            <input type="text" placeholder="Ingrese nuevo nombre" name="Nombre" id="Nombre" class="form-control" value="<?php echo $data_categoria['Nombre']; ?>">
          </div>
          <div class="form-group">
            <label for="producto">Descripcion</label>
            <input type="text" class="form-control" placeholder="Ingrese Descripcion" name="Descripcion" id="Descripcion" value="<?php echo $data_categoria['Descripcion']; ?>">
          </div>
          <input type="submit" value="Actualizar Categoria" class="btn btn-primary">
          <a href="categoria.php" class="btn btn-danger">Atras</a>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once "includes/footer.php"; ?>