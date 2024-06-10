<?php
$servername = "localhost";
$username = "node";
$password = "Node_2023";
$dbname = "appopular";

/*$servername = "localhost";
$username = "node";
$password = "root";
$dbname = "appopu2023";*/


// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$productos_por_pagina = 10;

if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $pagina_actual = (int) $_GET['page'];
} else {
    $pagina_actual = 1;
}

$offset = ($pagina_actual - 1) * $productos_por_pagina;

$sql_total = "SELECT COUNT(*) as total FROM productos";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_productos = $row_total['total'];
$total_paginas = ceil($total_productos / $productos_por_pagina);

$sql = "
    SELECT p.idProductos, p.nombre, p.costo, p.talla, p.stock, i.urlimg
    FROM productos p
    JOIN productos_has_imagen phi ON p.idProductos = phi.idProductos
    JOIN imagen i ON phi.idimagen = i.idimagen
    ORDER BY p.idProductos
    LIMIT $productos_por_pagina OFFSET $offset
";
$result = $conn->query($sql);

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
    <style>
        #carrito-compras {
            display: none;
            position: absolute;
            top: 60px;
            right: 20px;
            width: 300px;
            z-index: 1000;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        #carrito-icon {
            cursor: pointer;
            position: relative;
            display: inline-block;
            margin-left: 15px;
        }
        #carrito-icon span {
            position: absolute;
            top: -10px;
            right: -10px;
            background: red;
            color: white;
            border-radius: 50%;
            padding: 3px 7px;
            font-size: 12px;
        }
        #filtro-icon {
            cursor: pointer;
            position: relative;
            display: inline-block;
            margin-left: 15px;
        }
        .cart-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        .item-details {
            flex: 1;
            padding: 0 10px;
        }
        #filtroModal .modal-dialog {
            max-width: 400px;
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
        <form class="form-inline my-2 my-lg-0" method="GET" action="promocion.php">
            <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Buscar" name="query">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
        </form>
    </div>
</nav>

<div class="container mt-5">
    <div class="d-flex justify-content-between">
        <div id="filtro-icon">
            <img src="https://img.icons8.com/ios-filled/50/000000/filter.png" alt="Filtro de Productos" data-toggle="modal" data-target="#filtroModal">
        </div>
        <div id="carrito-icon">
            <img src="https://img.icons8.com/ios-filled/50/000000/shopping-cart.png" alt="Carrito de Compras">
            <span id="cart-count">0</span>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <h4>Filtros</h4>
            <form id="filtroForm">
                <div class="form-group">
                    <label for="filtro-categoria">Categoría</label>
                    <select class="form-control" id="filtro-categoria" name="categoria">
                        <option value="">Todas</option>
                        <option value="categoria1">Categoría 1</option>
                        <option value="categoria2">Categoría 2</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filtro-precio">Precio Máximo</label>
                    <input type="number" class="form-control" id="filtro-precio" name="precio">
                </div>
                <div class="form-group">
                    <label for="filtro-talla">Talla</label>
                    <input type="text" class="form-control" id="filtro-talla" name="talla">
                </div>
                <button type="submit" class="btn btn-primary">Aplicar Filtro</button>
            </form>
        </div>

        <div class="col-md-9">
            <h1>Lista de Productos</h1>
            <div id="lista-productos" class="row">
                <!-- Productos cargados aquí por PHP -->
                <?php if (!empty($result) && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="col-md-4">
                            <div class="product-card card">
                                <?php if (!empty($row['urlimg'])): ?>
                                    <img src="<?php echo 'https://www.appopular.me' . htmlspecialchars($row['urlimg']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['nombre']); ?></h5>
                                    <p class="card-text">Costo: <?php echo htmlspecialchars($row['costo']); ?></p>
                                    <p class="card-text">Talla: <?php echo htmlspecialchars($row['talla']); ?></p>
                                    <p class="card-text">Stock: <?php echo htmlspecialchars($row['stock']); ?></p>
                                    <button class="btn btn-primary agregar-carrito" data-id="<?php echo $row['idProductos']; ?>" data-nombre="<?php echo htmlspecialchars($row['nombre']); ?>" data-costo="<?php echo htmlspecialchars($row['costo']); ?>" data-urlimg="<?php echo 'https://www.appopular.me' . htmlspecialchars($row['urlimg']); ?>">Agregar al carrito</button>
                                </div>
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
                    <!-- Paginación generada por PHP -->
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
    </div>
</div>

<div class="modal fade" id="filtroModal" tabindex="-1" aria-labelledby="filtroModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filtroModalLabel">Filtrar Productos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="filtroFormModal">
                    <div class="form-group">
                        <label for="filtro-categoria-modal">Categoría</label>
                        <select class="form-control" id="filtro-categoria-modal" name="categoria">
                            <option value="">Todas</option>
                            <option value="categoria1">Categoría 1</option>
                            <option value="categoria2">Categoría 2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="filtro-precio-modal">Precio Máximo</label>
                        <input type="number" class="form-control" id="filtro-precio-modal" name="precio">
                    </div>
                    <div class="form-group">
                        <label for="filtro-talla-modal">Talla</label>
                        <input type="text" class="form-control" id="filtro-talla-modal" name="talla">
                    </div>
                    <button type="submit" class="btn btn-primary">Aplicar Filtro</button>
                    <!-- Botón de confirmar compra dentro del formulario -->
                    <button type="button" class="btn btn-success mt-3" id="confirmar-compra-modal">Confirmar Compra</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Carrito de compras -->
<div id="carrito-compras" class="p-3 border-bottom">
    <h4>Carrito de Compras</h4>
    <ul id="lista-carrito" class="list-group">
        <!-- Productos en el carrito -->
    </ul>
    <button class="btn btn-danger mt-3" id="vaciar-carrito">Vaciar Carrito</button>
    <!-- Incluye el valor total en un input oculto -->
    <input type="hidden" id="total-compra" value="0">
    <!-- Botón de confirmar compra fuera del formulario -->
    <button class="btn btn-success mt-3" id="confirmar-compra">Confirmar Compra</button>
</div>


<script>
$(document).ready(function() {
    let carrito = [];

    $('#carrito-icon').click(function() {
        $('#carrito-compras').toggle();
    });

    $(document).click(function(event) {
        if (!$(event.target).closest('#carrito-compras, #carrito-icon').length) {
            $('#carrito-compras').hide();
        }
    });

    $('#filtroForm, #filtroFormModal').submit(function(event) {
        event.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            url: 'filtrar_productos.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#lista-productos').html(response);
                $('#filtroModal').modal('hide');
            }
        });
    });

    $(document).on('click', '.agregar-carrito', function() {
        let product = {
            id: $(this).data('id'),
            nombre: $(this).data('nombre'),
            costo: $(this).data('costo'),
            urlimg: $(this).data('urlimg'),
            cantidad: 1
        };

        let found = false;
        for (let i = 0; i < carrito.length; i++) {
            if (carrito[i].id === product.id) {
                carrito[i].cantidad++;
                found = true;
                break;
            }
        }

        if (!found) {
            carrito.push(product);
        }
        actualizarCarrito();
    });

    $('#vaciar-carrito').click(function() {
        carrito = [];
        actualizarCarrito();
    });

    function actualizarCarrito() {
        $('#lista-carrito').empty();
        let total = 0;
        carrito.forEach(function(product) {
            total += product.costo * product.cantidad;
            $('#lista-carrito').append('<li class="list-group-item">' +
                '<img src="' + product.urlimg + '" width="50" height="50">' +
                '<span>' + product.nombre + ' - $' + product.costo + ' x ' + product.cantidad + ' = $' + (product.costo * product.cantidad).toFixed(2) + '</span>' +
                '<button class="btn btn-danger btn-sm eliminar-producto" data-id="' + product.id + '">X</button>' +
            '</li>');
        });
        // Actualiza el valor total en el input oculto
        $('#total-compra').val(total.toFixed(2));
        $('#cart-count').text(carrito.length);
        $('#lista-carrito').append('<li class="list-group-item">Total: $' + total.toFixed(2) + '</li>');
    }

    $(document).on('click', '.eliminar-producto', function() {
        let idProducto = $(this).data('id');
        carrito = carrito.filter(product => product.id !== idProducto);
        actualizarCarrito();
    });

    $('#confirmar-compra').click(function() {
    if (carrito.length > 0) {
        let total = carrito.reduce((sum, product) => sum + (product.costo * product.cantidad), 0);
        let nombresProductos = carrito.map(product => product.nombre); // Obtener solo los nombres de los productos

        // Enviar los datos como objetos regulares
        let formData = new FormData();
        formData.append('nombresProductos', nombresProductos.join(',')); // Convertir a una cadena separada por coma
        formData.append('total', total);

        $.ajax({
            url: 'confirmar_compra.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Redirigir a la página de confirmación de compra
                window.location.href = 'confirmar_compra.php?total=' + total.toFixed(2);
            },
            error: function() {
                alert('Ocurrió un error al confirmar la compra.');
            }
        });
    } else {
        alert('El carrito está vacío');
    }
});


});
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function(){
        $('#carouselExampleIndicators').carousel({
            interval: 2000
        });
    });
</script>