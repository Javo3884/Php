<?php
require 'db_config.php'; // Incluir la configuración de la base de datos

if (isset($_GET['id'])) {
    $id = $_GET['id']; // Obtener el ID del equipo a editar

    // Obtener los datos del equipo
    $query = "SELECT * FROM equipos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $equipo = $result->fetch_assoc();

    if (!$equipo) {
        echo "Equipo no encontrado.";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $estado = $_POST['estado'];
    $descripcion = $_POST['descripcion'];

    // Si hay un nuevo archivo PDF, se procesa
    $pdf_adjunto = null;
    if (isset($_FILES['pdf_adjunto']) && $_FILES['pdf_adjunto']['error'] === 0) {
        $target_dir = "uploads/"; // Directorio donde se almacenan los archivos PDF
        $target_file = $target_dir . basename($_FILES["pdf_adjunto"]["name"]);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verificar si es un PDF
        if ($file_type == "pdf") {
            if (move_uploaded_file($_FILES["pdf_adjunto"]["tmp_name"], $target_file)) {
                $pdf_adjunto = basename($_FILES["pdf_adjunto"]["name"]);
            } else {
                echo "Error al cargar el archivo PDF.";
                exit;
            }
        } else {
            echo "El archivo no es un PDF válido.";
            exit;
        }
    }

    // Actualizar la información del equipo
    $query = "UPDATE equipos SET estado = ?, descripcion = ?, pdf_adjunto = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $estado, $descripcion, $pdf_adjunto, $id);

    if ($stmt->execute()) {
        header("Location: index.php?mensaje=Equipo actualizado correctamente.");
    } else {
        echo "Error al actualizar el equipo: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Equipo</title>
</head>
<body>
<h2>Editar Equipo</h2>
<form action="editar_equipo.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
    <div>
        <label for="estado">Estado:</label>
        <input type="text" id="estado" name="estado" value="<?php echo $equipo['estado']; ?>" required>
    </div>
    <div>
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required><?php echo $equipo['descripcion']; ?></textarea>
    </div>
    <div>
        <label for="pdf_adjunto">PDF Adjunto:</label>
        <input type="file" id="pdf_adjunto" name="pdf_adjunto" accept="application/pdf">
    </div>
    <div>
        <button type="submit">Actualizar</button>
    </div>
</form>
</body>
</html>
