<?php
$servername = "localhost";
$username = "node";
$password = "Node_2023";
$dbname = "appopular";

/*$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "appopu2023";*/

/*$host = "localhost";
$user = "node";
$clave = "Node_2023";
$bd = "appopular";*/


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

try {
    $sql = "SELECT imagenes FROM banners";
    $result = $conn->query($sql);

    $images = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Convertir los datos BLOB a base64
            $image_data = base64_encode($row['imagenes']);
            $image_src = 'data:image/jpeg;base64,' . $image_data; // Suponiendo que sean imágenes JPEG
            $images[] = $image_src;
        }
    } else {
        echo "0 resultados";
    }
} catch (mysqli_sql_exception $e) {
    echo "Error en la consulta: " . $e->getMessage();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promociones del Centro Comercial Popular</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .title-bar {
            background-color: black;
            color: white;
            padding: 10px 0;
            text-align: center;
            overflow: hidden; /* Para evitar que el texto se desborde */
            position: relative; /* Para que la animación sea relativa a este contenedor */
        }
        
        .title-text {
            transition: background-color 0.5s, color 0.5s; /* Transición suave del color de fondo y del color del texto */
            background-color: black; /* Color de fondo inicial */
            color: white; /* Color de texto inicial */
            padding: 5px 10px; /* Espaciado alrededor del texto */
            border-radius: 5px; /* Bordes redondeados */
        }
        /* Definición de la animación */
        /*@keyframes scrollText {
            0% {
                transform: translateX(0%);
            }
            100% {
                transform: translateX(100%);
            }
        }*/
        
        /* Ajusta el ancho de la imagen del carrusel */
        .carousel-item img {
            width: 100%;
            height: auto;
        }

        /* Estilo personalizado para el carrusel */
        .carousel-item {
            transition: transform 1s ease; /* Transición suave */
        }
        
        /* Agrega una clase activa personalizada al carrusel */
        .carousel-item.active {
            transform: translateX(0%);
        }
        
        .carousel-item.next {
            transform: translateX(100%);
        }
        
        .carousel-item.prev {
            transform: translateX(-100%);
        }
        
        .carousel-item-right.active,
        .carousel-item-left.active {
            transform: translateX(0);
        }
        
        .carousel-item-right,
        .carousel-item-left {
            transform: translateX(0);
        }
        
        .carousel-item-next,
        .carousel-item-prev {
            position: relative;
            transform: translateX(0);
        }
        
        /* Agrega la transición al elemento activo */
        .carousel-inner .carousel-item-right.active,
        .carousel-inner .carousel-item-left.active {
            transition: transform 1s ease;
        }
        
        /* Agrega la transición a los elementos prev y next */
        .carousel-inner .carousel-item-next,
        .carousel-inner .carousel-item-prev,
        .carousel-inner .carousel-item-right,
        .carousel-inner .carousel-item-left {
            transition: transform 1s ease;
        }
    </style>
</head>
<body>

<div class="title-bar">
    <h1 class="title-text">Promociones del Centro Comercial Popular</h1>
</div>

<div class="container mt-5">
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <?php if (isset($images) && is_array($images) && count($images) > 0): ?>
                <?php for ($i = 0; $i < count($images); $i++): ?>
                    <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $i; ?>" class="<?php echo $i == 0 ? 'active' : ''; ?>"></li>
                <?php endfor; ?>
            <?php endif; ?>
        </ol>
        <div class="carousel-inner">
            <?php if (isset($images) && is_array($images) && count($images) > 0): ?>
                <?php foreach ($images as $index => $image): ?>
                    <div class="carousel-item <?php echo $index == 0 ? 'active' : ''; ?>">
                        <img src="<?php echo $image; ?>" class="d-block w-100" alt="...">
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Cambiar la imagen del carrusel cada 3 segundos
    $(document).ready(function(){
        $('#carouselExampleIndicators').carousel({
            interval: 2000
        });
    });
</script>
</body>
</html>

