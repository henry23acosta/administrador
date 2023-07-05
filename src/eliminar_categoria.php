<?php
session_start();
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "admin";
$sql = mysqli_query($conexion, "SELECT * FROM rol r INNER JOIN autentificacion a  WHERE r.Id_rol = a.Id_rol  AND a.idusuario = $id_user AND r.Descripcion = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $query_delete = mysqli_query($conexion, "UPDATE categoria SET estado = 0 WHERE idCategoria = $id");
    mysqli_close($conexion);
    header("Location: categoria.php");
}

