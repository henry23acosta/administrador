<?php
$servername = "localhost";
$username = "node";
$password = "Node_2023";
$dbname = "appopular";

/*$servername = "localhost";
$username = "node";
$password = "root";
$dbname = "appopu2023";*/

$conn = new mysqli($servername, $username, $password, $dbname);

$categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';
$precio = isset($_POST['precio']) ? $_POST['precio'] : '';
$talla = isset($_POST['talla']) ? $_POST['talla'] : '';

$query = "SELECT * FROM productos WHERE 1";

if (!empty($categoria)) {
    $query .= " AND categoria = '" . $conn->real_escape_string($categoria) . "'";
}

if (!empty($precio)) {
    $query .= " AND costo <= " . intval($precio);
}

if (!empty($talla)) {
    $query .= " AND talla = '" . $conn->real_escape_string($talla) . "'";
}

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-md-4">';
        echo '<div class="product-card card">';
        echo '<div class="card-body">';
        if (isset($row['urlimg']) && !empty($row['urlimg'])) { // Verificar si la clave 'urlimg' está definida y no está vacía
            $urlImagen = 'https://www.appopular.me' . htmlspecialchars($row['urlimg']);
            echo '<img src="' . $urlImagen . '" class="card-img-top" alt="' . htmlspecialchars($row['nombre']) . '">';
        }
        echo '<h5 class="card-title">' . htmlspecialchars($row['nombre']) . '</h5>';
        echo '<p class="card-text">Costo: ' . htmlspecialchars($row['costo']) . '</p>';
        echo '<p class="card-text">Talla: ' . htmlspecialchars($row['talla']) . '</p>';
        echo '<p class="card-text">Stock: ' . htmlspecialchars($row['stock']) . '</p>';
        echo '<button class="btn btn-primary agregar-carrito" data-id="' . $row['idProductos'] . '" data-nombre="' . htmlspecialchars($row['nombre']) . '" data-costo="' . htmlspecialchars($row['costo']) . '"';
        if (isset($row['urlimg']) && !empty($row['urlimg'])) { // Verificar nuevamente si 'urlimg' está definida y no está vacía
            echo ' data-urlimg="' . $urlImagen . '"';
        }
        echo '>Agregar al carrito</button>';
        echo '</div>'; // Cerrar div.card-body
        echo '</div>'; // Cerrar div.product-card
        echo '</div>'; // Cerrar div.col-md-4
    }
} else {
    echo '<p>No se encontraron productos.</p>';
}

$conn->close();
?>
