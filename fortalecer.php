<?php
/*$servername = "localhost";
$username = "node";
$password = "root";
$dbname = "appopu2023";*/

$servername = "localhost";
$username = "node";
$password = "Node_2023";
$dbname = "appopular";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la fecha actual
date_default_timezone_set('America/New_York'); // Establecer la zona horaria según tu ubicación
$fecha = date('Y-m-d H:i:s');

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/promo.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <title>Análisis FODA</title>
    <style>
        .foda-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 10px;
            padding: 20px;
        }
        .foda-box {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            background-color: #f9f9f9;
        }
        .foda-box img {
            max-width: 200px; /* Ajusta este valor para hacer las imágenes más grandes */
            margin-bottom: 10px;
        }
        .history-section {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            margin-top: 0; /* Reduce el margen superior */
        }
        .history-section h2 {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .history-section p {
            margin-bottom: 15px;
            text-align: justify;
        }
        .history-image img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="title-bar">
    <a href="#" class="title-link">
        <h1>Centro Comercial Popular de Tulcán</h1>
    </a>
    <a href="#" class="logo-link">
        <div class="logo">
            <img src="./assets/img/logopopu.jpg" alt="Logo">
        </div>
    </a>
</div>

<nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="promocion.php">Inicio</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <!-- Your navbar items -->
        </ul>
    </div>
</nav>

<div class="container mt-5">
<h2 class="text-center mb-4">ANÁLISIS DEL CENTRO COMERCIAL POPULAR</h2>
<div id="historia" class="history-section">
        <h2>CONCLUSIONES</h2>
        <p>
            Debido a la situación económica y social que atraviesa la ciudad y el país, sumados a los graves efectos,
            de la pandemia del COVID-19 y la improvisación, influyo en el BAJO impacto para la reactivación comercial
            del C.C.P., PORQUE NO APORTÓ en los resultados esperados, al ser una gran incertidumbre en la organización,
            predomina el desaliento en algunos comerciantes, pero a la misma vez un gran deseo de activación económica.
        </p>
        <h2>RECOMENDACIONES</h2>
        <p>Mejorar planificación de eventos y elaboracion de un cronograma anual de actividades presupuestadas</p>
        <p>Participación de todas las asociaciones mediante socialización oportuna de los eventos</p>
        <p>Implementar campañas publicitarias por diferentes medios de comunicación, para llegar a mas personas tanto nacionales, como extranjeros</p>
        <div class="history-image text-center">
            <img src="./assets/img/conclu.jpeg" alt="Centro Comercial Popular" class="img-fluid">
        </div>
    </div>
   
    <div class="foda-container">
        <div class="foda-box">
            <img src="./assets/img/fortalezas.jpg" alt="Fortalezas">
            <h3>Fortalezas</h3>
            <p>Implementación de la personería jurídica</p>
            <p>Estar capacitados en educación financiera y atención al cliente</p>
            <p>Estar adaptados con resilencia a calamidades económicas</p>
            <p>Capacidad de organización para defender los derechos</p>
        </div>
        <div class="foda-box">
            <img src="./assets/img/forOport.jpeg" alt="Oportunidades">
            <h3>Oportunidades</h3>
            <p>Concentrar la MODERNIZACIÓN del C.C.P.</p>
            <p>Aprovechar Nuevas Tecnologias TICS</p>
            <p>Aprovechar herramientas de marketing y redes Sociales</p>
            <p>Mediante formalización de los negocios acceder a créditos</p>
        </div>
    </div>
      
    
</div>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>




