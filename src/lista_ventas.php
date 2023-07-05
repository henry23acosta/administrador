<?php
include_once "includes/header.php";
require_once "../conexion.php";
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
$query = mysqli_query($conexion, "SELECT f.idFactura,f.total,f.fecha_emision,f.usuario, c.nombre FROM factura f INNER JOIN cliente c WHERE f.idCliente=c.idCliente;");
?>
<table class="table table-light" id="tbl">
    <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Fecha</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?php echo $row['idFactura']; ?></td>
                <td><?php echo $row['nombre']; ?></td>
                <td><?php echo $row['total']; ?></td>
                <td><?php echo $row['fecha_emision']; ?></td>
                <td>
                    <a href="pdf/generar.php?cl=<?php echo $row['id_cliente'] ?>&v=<?php echo $row['id'] ?>" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php include_once "includes/footer.php"; ?>