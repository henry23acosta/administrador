<?php
   $host = "localhost";
   $user = "node";
   $clave = "Node_2023";
   $bd = "appopular";

/*$servername = "localhost";
$username = "node";
$password = "Node_2023";
$dbname = "appopular";*/

/*$host = "localhost";
$user = "root";
$clave = "root";
$bd = "appopu2023";*/
   $conexion = mysqli_connect($host,$user,$clave,$bd);
   
    if (mysqli_connect_errno()){
        echo "No se pudo conectar a la base de datos";
        exit();
    }
    mysqli_select_db($conexion,$bd) or die("No se encuentra la base de datos");
    mysqli_set_charset($conexion,"utf8");
?>