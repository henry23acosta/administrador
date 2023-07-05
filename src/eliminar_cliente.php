<?php
session_start();
require("../conexion.php");
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
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $query_delete = mysqli_query($conexion, "UPDATE cliente SET estado = 0 WHERE idCliente = $id");
    mysqli_close($conexion);
    header("Location: clientes.php");
}
