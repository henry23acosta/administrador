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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/promo.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Compra</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        h2 {
            color: #0066cc;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
            color: #333;
        }
        input[type="text"], select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #0066cc;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056a7;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Datos del Cliente:</h2>
    <form id="compraForm" action="procesar_compra.php" method="POST">
        <div>
            <label for="nombre_cliente">Nombre del Cliente:</label>
            <input type="text" id="nombre_cliente" name="nombre_cliente" placeholder="Nombre del Cliente" required>
        </div>
        <div>
            <label for="fecha">Fecha:</label>
            <input type="text" id="fecha" name="fecha" value="<?php echo $fecha; ?>" readonly>
        </div>
        <div>
            <label for="tarjeta">Tarjeta a Utilizar:</label>
            <select id="tarjeta" name="tarjeta">
                <option value="visa">Visa</option>
                <option value="mastercard">Mastercard</option>
            </select>
        </div>
        <div>
            <label for="nombre_compra">Productos a comprar:</label>
            <input type="text" id="nombre_compra" name="nombre_compra" value="<?php echo isset($_GET['nombresProductos']) ? $_GET['nombresProductos'] : ''; ?>" readonly>
        </div>
        <div>
            <label for="total">TOTAL DE COMPRA:</label>
            <input type="text" id="total" name="total" value="<?php echo $_GET['total']; ?>" readonly>
        </div>
        <div>
            <label for="numero_tarjeta">Número de Tarjeta:</label>
            <input type="text" id="numero_tarjeta" name="numero_tarjeta" placeholder="Número de Tarjeta" required>
        </div>
        <input type="submit" value="Confirmar Compra">
    </form>
</div>

</body>
</html>

<script>
$(document).ready(function() {
    $('#compraForm').submit(function(event) {
        // Obtener los nombres de los productos
        let nombresProductos = carrito.map(product => product.nombre).join(', ');

        // Obtener el total
        let total = carrito.reduce((sum, product) => sum + (product.costo * product.cantidad), 0).toFixed(2);

        // Establecer los valores en los campos ocultos
        $('#nombre_compra').val(nombresProductos);
        $('#total').val(total);
    });
});
</script>
