<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
$servername = "localhost";
$username = "node";
$password = "Node_2023";
$dbname = "appopular";

/*$servername = "localhost";
$username = "node";
$password = "root";
$dbname = "appopu2023";*/


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
            $image_data = base64_encode($row['imagenes']);
            $image_src = 'data:image/jpeg;base64,' . $image_data;
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

$productos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[$row['idProductos']]['nombre'] = $row['nombre'];
        $productos[$row['idProductos']]['costo'] = $row['costo'];
        $productos[$row['idProductos']]['talla'] = $row['talla'];
        $productos[$row['idProductos']]['stock'] = $row['stock'];
        $productos[$row['idProductos']]['total_ventas'] = $row['total_ventas'];
        $productos[$row['idProductos']]['imagenes'] = explode(',', $row['urlimg']);

        // Prepend 'http://localhost:3000' to each image URL
        //https://www.appopular.me${r[0].urlimg}
        foreach ($productos[$row['idProductos']]['imagenes'] as &$url) {
            $url = 'https://www.appopular.me${r[0].urlimg}' . $url;
        }
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
    <link rel="stylesheet" href="assets/css/promo.css">
    
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
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="tiendasDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Productos
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


<div class="container mt-5 carousel-container">
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
        <div class="progress-bar-container">
            <div class="progress-bar-fill"></div>
        </div>
    </div>
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
    
    <div id="historia" class="history-section mt-5">
        <h2>Novedades</h2>
        <p>
            El Centro Comercial Popular en Tulcán, es un lugar en el cual se puede encontrar todo tipo de mercadería como: camisas, camisetas, chaquetas, zapatos, pantalones, medias, ropa interior, además de ropa de cama como cobijas, fundas de almohada y sábanas, ropa para niños y adultos, a muy buenos precios al por mayor y al detal.
        </p>
        <div class="history-image text-center">
            <img src="./assets/img/line1.jpg" alt="Centro Comercial Popular" class="img-fluid">
            <img src="./assets/img/line2.jpg" alt="Centro Comercial Popular" class="img-fluid">
            <img src="./assets/img/line 3.jpg" alt="Centro Comercial Popular" class="img-fluid">
        </div>
    </div>
</div>

    <!-- Recursos adicionales -->
<div class="container mt-5">
    <div class="row">
        <div class="col-md-3 text-center">
            <div class="resource-card">
                <div class="icon">
                    <img src="./assets/img/noticias.png" alt="Noticias">
                </div>
                <h4>Noticias</h4>
                <p>Actualidades de los mercados de Tulcan e información de la gestión que realiza el Presidente del Centro Comercial Popular.</p>
            </div>
        </div>
        <div class="col-md-3 text-center">
            <div class="resource-card">
                <div class="icon">
                    <img src="./assets/img/megafono.png" alt="Convocatorias y requerimientos">
                </div>
                <h4>Convocatorias y requerimientos</h4>
                <p>Convocatorias, requerimientos, proformas y más solicitudes para la gestión del comercio del C.C.P.</p>
            </div>
        </div>
        <div class="col-md-3 text-center">
            <div class="resource-card">
                <div class="icon">
                    <img src="./assets/img/analitica.png" alt="Mercados de Quito">
                </div>
                <h4>Mercados de Tulcán</h4>
                <p>Historia, descripción, ubicación y productos que expende cada uno de los mercados, ferias y plataformas.</p>
            </div>
        </div>
        <div class="col-md-3 text-center">
            <div class="resource-card">
                <div class="icon">
                    <img src="./assets/img/centro-comercial.png" alt="Centros Comerciales del Ahorro">
                </div>
                <h4>Centros Comerciales del Ahorro</h4>
                <p>Descripción, ubicación y productos que expende cada uno de estos Centros de Comercio.</p>
            </div>
        </div>
    </div>
</div>

<div>
<div class="locate-us text-center mt-5">
        <a href="https://www.google.com/maps/place/Centro+Comercial+Popular/@0.8147552,-77.7166297,17z/data=!3m1!4b1!4m6!3m5!1s0x8e2968cc29cbaebf:0x44cc88a68640f092!8m2!3d0.8147552!4d-77.7140548!16s%2Fg%2F11gz_gwwz?entry=ttu" target="_blank" class="location-link">
            <i class="fas fa-map-marker-alt"></i> Ubícanos
        </a>
    </div>
 </div>

<!-- Botón de volver arriba -->
<button id="backToTopBtn" title="Go to top">
    <i class="fas fa-arrow-up"></i>
</button>

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

<footer class="footer">
<div class="container">
        <div id="sobreNosotros"></div>
        <div class="contact-info">
            <div class="separator"></div>
            <div>Sobre Nosotros</div>
            <div>Centro Comercial Popular<br>R77P+W94, Chimborazo, Tulcán</div>
            <div>-----</div>
            <div>Email</div>
            <div>popularcentrocomercial@gmail.com</div>
            <div>-----</div>
            <div></div>
            <div>Servicio al cliente: 0989964823<br>Telefono: 062981290</div>
            <div class="separator"></div>
        </div>
        
        <div class="column"> </div>
        <div class="column"></div>
        <div class="column"> </div>
        <div class="column"></div>
        <div class="payment-methods">
        <div>Medios de Pagos Aceptados</div>
            <img src="./assets/img/visa.png" alt="Visa">
            <img src="./assets/img/dinnersclub.png" alt="Diners Club">
            <img src="./assets/img/mastercad.png" alt="MasterCard">
            <img src="./assets/img/bancopichincha.png" alt="Banco Pichincha">
            <img src="./assets/img/american.png" alt="American Express">
            <img src="./assets/img/discover.png" alt="Discover">
            <img src="./assets/img/policianacional.png" alt="Policía Nacional">
            <img src="./assets/img/produbanco.png" alt="Davivienda">
            <img src="./assets/img/pacificard.png" alt="Bancolombia">
            <img src="./assets/img/latampass.png" alt="Banco de Bogotá">
        </div>
       
        <div class="column">
        <div class="separator"></div>
            <h4>Nuestra Compañía</h4>
            <div class="separator"></div>
            <ul>
                <li><a href="#">Quiénes somos</a></li>
                <li><a href="#">Nuestra tienda</a></li>
                <li><a href="#">Nuestras marcas</a></li>
                <li><a href="#">Contáctenos</a></li>
                <li><a href="#">Trabaja con nosotros</a></li>
                <li><a href="#">Tarjeta de crédito</a></li>
                <li><a href="#">Certificados tributarios</a></li>
                <li><a href="#">Rifas</a></li>
                <li><a href="#">Fondo de comerciantes y cooperativas</a></li>
            </ul>
        </div>
        <div class="separator"></div>

        <div class="column">
        <div class="separator"></div>
            <h4>Compras en línea</h4>
            <div class="separator"></div>
            <ul>
                <li><a href="#">Preguntas frecuentes</a></li>
                <li><a href="#">Pago seguro</a></li>
                <li><a href="#">Métodos de envío</a></li>
                <li><a href="#">Medios de pago</a></li>
                <li><a href="#">Seguros</a></li>
            </ul>
        </div>
        <div class="separator"></div>

        <div class="column">
        <div class="separator"></div>
            <h4>Servicios</h4>
            <div class="separator"></div>
            <ul>
                <li><a href="#">Instalaciones</a></li>
                <li><a href="#">Agendar servicio de instalación</a></li>
                <li><a href="#">Garantía extendida</a></li>
                <li><a href="#">Garantías y centros de servicios técnico</a></li>
                <li><a href="#">Instalación de App</a></li>
                <li><a href="#">Consulta tus facturas</a></li>
            </ul>
        </div>
        <div>
        <div></div>
        </div>

        <div class="column">
        <div class="separator"></div>
            <h4>Políticas</h4>
            <div class="separator"></div>
            <ul>
                <li><a href="#">Términos y condiciones del canal digital</a></li>
                <li><a href="#">Contrato de compraventa en línea</a></li>
                <li><a href="#">Política de privacidad</a></li>
                <li><a href="#">Solicitud de Informes</a></li>
                <li><a href="#">Política de cookies</a></li>
                <li><a href="#">Política de cambios y devoluciones</a></li>
                <li><a href="#">Código de ética</a></li>
                <li><a href="#">Manual de normas SRI para Terceros</a></li>
                <li><a href="#">Todo lo que debes saber</a></li>
                <li><a href="#">Línea ética</a></li>
            </ul>
        </div>
    </div>

    
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
// Mostrar el botón cuando el usuario hace scroll hacia abajo 20px
window.onscroll = function() {
        scrollFunction();
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("backToTopBtn").style.display = "block";
        } else {
            document.getElementById("backToTopBtn").style.display = "none";
        }
    }

    // Cuando el usuario hace clic en el botón, vuelve hacia arriba
    document.getElementById("backToTopBtn").addEventListener("click", function() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    });
    $(document).ready(function(){
        $('#carouselExampleIndicators').carousel({
            interval: 2000
        });
    });

    document.querySelector('.title-link').addEventListener('click', function(event) {
        event.preventDefault();
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    });

    document.querySelector('.title-link h1').addEventListener('mousedown', function() {
        this.style.color = 'fuchsia';
    });

    document.querySelector('.title-link h1').addEventListener('mouseup', function() {
        setTimeout(() => {
            this.style.color = 'white';
        }, 500); // Change back to original color after 500ms
    });
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();

        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

document.addEventListener('DOMContentLoaded', (event) => {
    const carousel = document.querySelector('#carouselExampleIndicators');
    const carouselInstance = new bootstrap.Carousel(carousel, {
        interval: 5000, // 5 segundos
        pause: false,
        ride: 'carousel'
    });

    carousel.addEventListener('mouseenter', () => {
        carouselInstance.pause();
        resetProgressBar();
    });

    carousel.addEventListener('mouseleave', () => {
        carouselInstance.cycle();
        startProgressBar();
    });

    let intervalId;

    function startProgressBar() {
        const progressBarFill = document.querySelector('.progress-bar-fill');
        progressBarFill.style.width = '0%';

        setTimeout(() => {
            progressBarFill.style.width = '100%';
        }, 100); // Pequeño retraso para iniciar la animación
    }

    function resetProgressBar() {
        clearInterval(intervalId);
        const progressBarFill = document.querySelector('.progress-bar-fill');
        if (progressBarFill) {
            progressBarFill.style.width = '0%';
        }
    }

    carousel.addEventListener('slid.bs.carousel', (event) => {
        resetProgressBar();
        startProgressBar();
    });

    // Iniciar la barra de progreso al cargar la página
    startProgressBar();
});





</script>
</body>
</html>
