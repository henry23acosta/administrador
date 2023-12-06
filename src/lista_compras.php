<?php
include_once "includes/header.php";
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "Clientes";
$idnegocio = $_SESSION['idnegocio'];
$sql = mysqli_query($conexion, "SELECT dp.Id_rol, dp.idusuario, p.idpermisos, p.nombre FROM detalle_permiso dp INNER JOIN permisos p WHERE dp.idusuario = '$id_user' AND dp.idpermisos = p.idpermisos AND p.nombre = '$permiso';");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Status: 301 Moved Permanently");
    header("Location: permisos.php");
    echo "<script language='javascript'>window.location='permisos.php'</script>;";
    exit();
}

$query = mysqli_query($conexion, "SELECT c.idcompra, c.total, c.fecha_emision, p.nombre AS proveedores FROM compra c INNER JOIN proveedores p ON c.idProveedores = p.idProveedores;"); // Modificamos la consulta para obtener datos de la tabla de compra y el proveedor.

?>
<table class="table table-light" id="tbl">
    <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Proveedor</th> <!-- Cambiamos "Cliente" por "Proveedor" en la cabecera de la tabla. -->
            <th>Total</th>
            <th>Fecha de Compra</th> <!-- Cambiamos "Fecha" por "Fecha de Compra". -->
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?php echo $row['idcompra']; ?></td> <!-- Cambiamos "idFactura" por "idCompra". -->
                <td><?php echo $row['proveedores']; ?></td> <!-- Cambiamos "nombre" por "proveedor". -->
                <td><?php echo $row['total']; ?></td>
                <td><?php echo $row['fecha_emision']; ?></td> <!-- Cambiamos "fecha_emision" por "fecha_compra". -->
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php include_once "includes/footer.php"; ?>
