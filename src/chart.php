<?php
include("../conexion.php");
if ($_POST['action'] == 'sales') {
    $arreglo = array();
    $query = mysqli_query($conexion, "SELECT nombre, stock FROM productos WHERE stock <= 10 ORDER BY stock ASC LIMIT 10");
    while ($data = mysqli_fetch_array($query)) {
        $arreglo[] = $data;
    }
    echo json_encode($arreglo);
    die();
}
if ($_POST['action'] == 'polarChart') {
    $arreglo = array();
    $query = mysqli_query($conexion, "SELECT d.idProductos, p.nombre, sum(d.cantidad) AS total FROM factura_detalle d  INNER JOIN productos p  WHERE p.idProductos = d.idProductos group by p.idProductos ORDER BY total DESC LIMIT 5");
    while ($data = mysqli_fetch_array($query)) {
        $arreglo[] = $data;
    }
    echo json_encode($arreglo);
    die();
}
?>
