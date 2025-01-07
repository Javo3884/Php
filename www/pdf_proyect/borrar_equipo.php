<?php
require 'db_config.php'; // Incluir la configuración de la base de datos

if (isset($_GET['id'])) {
    $id = $_GET['id']; // Obtener el ID del equipo a eliminar

    // Consulta para eliminar el equipo
    $query = "DELETE FROM equipos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id); // Vincular el ID

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir con mensaje de éxito
        header("Location: index.php?mensaje=Equipo eliminado correctamente.");
        exit;
    } else {
        // Si hay error, mostrar mensaje de error
        echo "Error al eliminar el equipo: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
