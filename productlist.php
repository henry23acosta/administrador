<?php
$servername = "localhost";
$username = "node";
$password = "Node_2023";
$dbname = "appopular";

/*$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "appopu2023";*/


// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Número de productos por página
$productos_por_pagina = 10;

// Obtener el número de la página actual
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $pagina_actual = (int) $_GET['page'];
} else {
    $pagina_actual = 1;
}

// Calcular el desplazamiento para la consulta SQL
$offset = ($pagina_actual - 1) * $productos_por_pagina;

// Obtener el total de productos para calcular el número total de páginas
$sql_total = "SELECT COUNT(*) as total FROM productos";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_productos = $row_total['total'];
$total_paginas = ceil($total_productos / $productos_por_pagina);

// Realizar la consulta SQL para obtener los productos de la página actual
$sql = "
    SELECT p.idProductos, p.nombre, p.costo, p.talla, p.stock, i.urlimg
    FROM productos p
    JOIN productos_has_imagen phi ON p.idProductos = phi.idProductos
    JOIN imagen i ON phi.idimagen = i.idimagen
    ORDER BY p.idProductos
    LIMIT $productos_por_pagina OFFSET $offset
";
$result = $conn->query($sql);

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/promo.css">
</head>


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
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="tiendasDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Tiendas
                </a>
                <div class="dropdown-menu" aria-labelledby="tiendasDropdown">
                    <a class="dropdown-item" href="productlist.php">Lista de Productos</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="aplicacionDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Aplicación
                </a>
                <div class="dropdown-menu" aria-labelledby="aplicacionDropdown">
                    <a class="dropdown-item" href="https://drive.google.com/file/d/1R3WZcOpLaK34rW9_WhEQnSmQX__PPDq_/view?usp=sharing">Descargar</a>
                    <a class="dropdown-item" href="index.php">Administrador</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#sobreNosotros">Contacto</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#historia">Novedad</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" method="GET" action="promocion.php">
            <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Buscar" name="query">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
        </form>
    </div>
</nav>


    <div class="container mt-5">
        <h1>Lista de Productos</h1>
        <div class="row">
            <?php if (!empty($result) && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="product-card">
                            <?php if (!empty($row['urlimg'])): ?>
                                <img src="<?php echo htmlspecialchars($row['urlimg']); ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
                            <?php endif; ?>
                            <h5><?php echo htmlspecialchars($row['nombre']); ?></h5>
                            <p>Costo: <?php echo htmlspecialchars($row['costo']); ?></p>
                            <p>Talla: <?php echo htmlspecialchars($row['talla']); ?></p>
                            <p>Stock: <?php echo htmlspecialchars($row['stock']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No se encontraron productos.</p>
            <?php endif; ?>
        </div>
        
        <!-- Enlaces de paginación -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php if ($pagina_actual > 1): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo $pagina_actual - 1; ?>">Anterior</a></li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?php if ($i == $pagina_actual) echo 'active'; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($pagina_actual < $total_paginas): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo $pagina_actual + 1; ?>">Siguiente</a></li>
                <?php endif; ?>
            </ul>
        </nav>
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