<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "appopu2023";

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
$sql = "
    SELECT 
        p.idProductos, 
        p.nombre, 
        p.costo, 
        p.talla, 
        p.stock, 
        GROUP_CONCAT(i.urlimg) as urlimg, 
        SUM(cd.cantidad) as total_ventas
    FROM 
        productos p
    JOIN 
        productos_has_imagen phi ON p.idProductos = phi.idProductos
    JOIN 
        imagen i ON phi.idimagen = i.idimagen
    JOIN 
        compra_detalle cd ON p.idProductos = cd.idProductos
    GROUP BY 
        p.idProductos, p.nombre, p.costo, p.talla, p.stock
    ORDER BY 
        total_ventas DESC
    LIMIT 3
";
$result = $conn->query($sql);

// Organizar los resultados en un array
$productos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[$row['idProductos']]['nombre'] = $row['nombre'];
        $productos[$row['idProductos']]['costo'] = $row['costo'];
        $productos[$row['idProductos']]['talla'] = $row['talla'];
        $productos[$row['idProductos']]['stock'] = $row['stock'];
        $productos[$row['idProductos']]['total_ventas'] = $row['total_ventas'];
        // Separar las URLs concatenadas en un array
        $productos[$row['idProductos']]['imagenes'] = explode(',', $row['urlimg']);
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .title-bar {
            background-color: black;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        .navbar {
            background-color: #333;
        }
        .navbar-nav .nav-link {
            color: white !important;
        }
        .carousel-item img {
            width: 100px;
            height: 400px;
        }
        .product-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin: 10px;
            text-align: center;
        }
        .product-card img {
            width: 100%;
            height: auto;
        }
        .social-links a {
            margin: 0 10px;
            color: #333;
            font-size: 1.5em;
        }
    </style>
</head>
<body>

<div class="title-bar">
    <h1> Centro Comercial Popular de Tulcán</h1>
</div>


<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="/administrador/promocion.php">Inicio</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
        
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="tiendasDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Tiendas
                </a>
                <div class="dropdown-menu" aria-labelledby="tiendasDropdown">
                    <a class="dropdown-item" href="/administrador/product_index.php">Lista de Productos</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="tiendasDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Aplicación
                </a>
                <div class="dropdown-menu" aria-labelledby="tiendasDropdown">
                    <a class="dropdown-item" href="#">Descargar</a>
                    <a class="dropdown-item" href="#">Administrador</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Contacto</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Buscar">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
        </form>
    </div>
</nav>


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



    <div class="container mt-5">
        <h2>Productos Más Vendidos</h2>
        <div class="row">
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $idProducto => $producto): ?>
                    <div class="col-md-4">
                        <div class="product-card">
                            <?php if (!empty($producto['imagenes'])): ?>
                                <?php foreach ($producto['imagenes'] as $imagen): ?>
                                    <img src="<?php echo htmlspecialchars($imagen); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                                    <!-- Mostramos solo la primera imagen -->
                                    <?php break; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <h5><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                            <p>Costo: <?php echo htmlspecialchars($producto['costo']); ?></p>
                            <p>Talla: <?php echo htmlspecialchars($producto['talla']); ?></p>
                            <p>Stock: <?php echo htmlspecialchars($producto['stock']); ?></p>
                            <p>Total Ventas: <?php echo htmlspecialchars($producto['total_ventas']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No se encontraron productos.</p>
            <?php endif; ?>
        </div>
    </div>

    


    

    <div class="mt-5 text-center">
        <h2>Síguenos en nuestras redes sociales</h2>
        <script src="https://kit.fontawesome.com/c732077a9d.js" crossorigin="anonymous"></script>
        <div class="social-links">
            <a href="https://www.facebook.com/profile.php?id=100076249756368&mibextid=ZbWKwL" target="_blank"><i class="fab fa-facebook"></i></a>
            <a href="https://www.whatsapp.com" target="_blank"><i class="fab fa-whatsapp"></i></a>
            <a href="https://www.tiktok.com" target="_blank"><i class="fab fa-tiktok"></i></a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        $('#carouselExampleIndicators').carousel({
            interval: 2000
        });
    });
</script>
</body>
</html>
