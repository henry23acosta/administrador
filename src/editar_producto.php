<?php
include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "admin";
$permiso ='Productos';
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
  if (empty($_POST['nombre']) || empty($_POST['costo']) || empty($_POST['talla'])|| empty($_POST['imagen'])|| empty($_POST['stock'])) {
    $alert = '<div class="alert alert-primary" role="alert">
              Todo los campos son requeridos
            </div>';
  } else {
    $idProductos = $_GET['id'];
    $nombre = $_POST['nombre'];
    $costo = $_POST['costo'];
    $talla = $_POST['talla'];
    $imagen = $_POST['imagen'];
    $stock = $_POST['stock'];
    $query_update = mysqli_query($conexion, "UPDATE productos SET nombre = '$nombre', costo= '$costo', talla='$talla', imagen='$imagen', stock=$stock   WHERE idProductos = $idProductos");
    if ($query_update) {
      $alert = '<div class="alert alert-primary" role="alert">
              Producto Modificado
            </div>';
    } else {
      $alert = '<div class="alert alert-primary" role="alert">
                Error al Modificar
              </div>';
    }
  }
}

// Validar producto

if (empty($_REQUEST['id'])) {
  header("Location: productos.php");
} else {
  $idProductos = $_REQUEST['id'];
  if (!is_numeric($idProductos)) {
    header("Location: productos.php");
  }
  $query_producto = mysqli_query($conexion, "SELECT * FROM productos WHERE idProductos = $idProductos");
  $result_producto = mysqli_num_rows($query_producto);

  if ($result_producto > 0) {
    $data_producto = mysqli_fetch_assoc($query_producto);
  } else {
    header("Location: productos.php");
  }
}
?>
<div class="row">
  <div class="col-lg-6 m-auto">

    <div class="card">
      <div class="card-header bg-primary text-white">
        Modificar producto
      </div>
      <div class="card-body">
        <form action="" method="post">
          <?php echo isset($alert) ? $alert : ''; ?>
          <div class="form-group">
            <label for="codigo">Nombre</label>
            <input type="text" placeholder="Ingrese nuevo nombre" name="nombre" id="nombre" class="form-control" value="<?php echo $data_producto['nombre']; ?>">
          </div>
          <div class="form-group">
            <label for="producto">Costo</label>
            <input type="text" class="form-control" placeholder="Ingrese Costo del producto" name="costo" id="costo" value="<?php echo $data_producto['costo']; ?>">
          </div>
          <div class="form-group">
            <label for="producto">Talla</label>
            <input type="text" class="form-control" placeholder="Ingrese talla del producto" name="talla" id="talla" value="<?php echo $data_producto['talla']; ?>">
          </div>
          <div class="form-group">
            <label for="precio">Imagen</label>
            <input type="text" placeholder="Ingrese Nueva Imagen" class="form-control" name="imagen" id="imagen" value="<?php echo $data_producto['imagen']; ?>">

          </div>
          <div class="form-group">
            <label for="producto">Stock</label>
            <input type="number" class="form-control" placeholder="Ingrese talla del producto" name="stock" id="stock" value="<?php echo $data_producto['stock']; ?>">
          </div>
          <input type="submit" value="Actualizar Producto" class="btn btn-primary">
          <a href="productos.php" class="btn btn-danger">Atras</a>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once "includes/footer.php"; ?>