<?php
include_once "includes/header.php";
include "../conexion.php";

// Define la función subirImagen()
function subirImagen($imagen) {
    $tipo_mime = mime_content_type($imagen["tmp_name"]); // Obtener tipo MIME
    $permitidos = ['image/jpeg', 'image/png', 'image/gif'];

    if (in_array($tipo_mime, $permitidos)) {
        $imagen_blob = file_get_contents($imagen["tmp_name"]);
        return array($imagen_blob, $tipo_mime);
    } else {
        // Tipo de archivo no permitido
        return false;
    }
}

// Código de conexión a la base de datos y función subirImagen
$database = "appopular";
$servername = "localhost";
$username = "Node_2023";
$password = "root";

/*$host = "localhost";
$user = "node";
$clave = "Node_2023";
$bd = "appopular";*/


// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Lógica para insertar, editar y eliminar registros de banners
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Insertar nuevo banner
    if (isset($_POST["submit"])) {
        $imagen_resultado = subirImagen($_FILES["imagen"]);
        if ($imagen_resultado !== false) {
            list($imagen_blob, $tipo_mime) = $imagen_resultado;
            $sql = "INSERT INTO banners (imagenes, tipo_mime, nombre_archivo, fechacreacion) VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("bss", $imagen_blob, $tipo_mime, $_FILES["imagen"]["name"]);
                $stmt->send_long_data(0, $imagen_blob);
                $stmt->execute();
                $stmt->close();
                echo "Imagen subida exitosamente.";
            } else {
                die("Error al preparar la consulta: " . $conn->error);
            }
        } else {
            echo "Tipo de archivo no permitido.";
        }
    }

    // Eliminar banner existente
    if (isset($_POST["eliminar"])) {
        $id_banner = $_POST["id"];
        $sql = "DELETE FROM banners WHERE id_banner = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_banner);
        $stmt->execute();
        $stmt->close();
        echo "Banner eliminado exitosamente.";
    }

    // Editar banner existente
    if (isset($_POST["editar"])) {
        $id_banner = $_POST["id"];
        $imagen_resultado = subirImagen($_FILES["nueva_imagen"]);
        if ($imagen_resultado !== false) {
            list($nueva_imagen_blob, $tipo_mime) = $imagen_resultado;
            $sql = "UPDATE banners SET imagenes = ?, tipo_mime = ?, nombre_archivo = ? WHERE id_banner = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("bssi", $nueva_imagen_blob, $tipo_mime, $_FILES["nueva_imagen"]["name"], $id_banner);
                $stmt->send_long_data(0, $nueva_imagen_blob);
                $stmt->execute();
                $stmt->close();
                echo "Banner editado exitosamente.";
            } else {
                die("Error al preparar la consulta: " . $conn->error);
            }
        } else {
            echo "Tipo de archivo no permitido.";
        }
    }
}

// Consulta para obtener los registros de banners
$sql = "SELECT id_banner, imagenes, tipo_mime, fechacreacion FROM banners";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imagenes Promocionales</title>
</head>
<body>
    <h1>Imagenes cargadas en la pagina</h1>

    <!-- Formulario para subir imagen de banner -->
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
        <label for="imagen">Seleccione una imagen:</label>
        <input type="file" name="imagen" id="imagen" accept="image/png, image/jpeg, image/jpg, image/gif" required>
        <button type="submit" name="submit">Subir Imagen</button>
    </form>

    <!-- Lista de imágenes (banners) -->
    <h2>Lista de imagenes</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Fecha de Creación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id_banner"] . "</td>";
                    if (isset($row["tipo_mime"])) {
                        echo "<td><img src='data:" . $row["tipo_mime"] . ";base64," . base64_encode($row["imagenes"]) . "' width='100'></td>";
                    } else {
                        echo "<td>Imagen no disponible</td>";
                    }
                    echo "<td>" . $row["fechacreacion"] . "</td>";
                    echo "<td>
                            <form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post' enctype='multipart/form-data'>
                                <input type='hidden' name='id' value='" . $row["id_banner"] . "'>
                                <label for='nueva_imagen'>Seleccione una nueva imagen (jpg, jpeg, png):</label>
                                <input type='file' name='nueva_imagen' id='nueva_imagen' accept='image/png, image/jpeg, image/jpg' required>
                                <button type='submit' name='editar'>Editar</button>
                            </form>
                            <form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>
                                <input type='hidden' name='id' value='" . $row["id_banner"] . "'>
                                <button type='submit' name='eliminar'>Eliminar</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No hay imágenes</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php include_once "includes/footer.php"; ?>
    <?php
    // Cerrar la conexión
    $conn->close();
    ?>
</body>
</html>

