<?php
require 'db_config.php'; // Configuración de la base de datos
require 'libs/vendor/autoload.php'; // Librería para generar PDFs (ejemplo: Dompdf)

// Verifica si se enviaron los datos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];

    // Directorio para guardar el PDF
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $pdfPath = $uploadDir . md5(uniqid(rand(), true)) . '.pdf';
    $imagenesSubidas = [];

    // Generar contenido HTML para el PDF
    //$html = '<h1>Equipo</h1>';
    //$html .= '<p><strong>Descripción:</strong> ' . htmlspecialchars($descripcion) . '</p>';
    //$html .= '<p><strong>Estado:</strong> ' . htmlspecialchars($estado) . '</p>';
    //$html .= '<h2>Imágenes:</h2>';

    // Manejo de imágenes subidas
    if (isset($_FILES['imagenes']) && count($_FILES['imagenes']['tmp_name']) > 0) {
        foreach ($_FILES['imagenes']['tmp_name'] as $index => $tmpName) {
            $fileData = file_get_contents($tmpName);
            $base64 = base64_encode($fileData); // Codifica la imagen en Base64
            $mimeType = mime_content_type($tmpName); // Detecta el tipo MIME de la imagen

            // Inserta la imagen en el contenido del PDF
            $html .= '<div><img src="data:' . $mimeType . ';base64,' . $base64 . '" style="width: 100%; margin-bottom: 20px;"></div>';
        }
    }

    // Generar el PDF usando Dompdf
    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Guardar el PDF en el servidor
    file_put_contents($pdfPath, $dompdf->output());

    // Guardar la información en la base de datos
    $query = "INSERT INTO equipos (descripcion, estado, ruta_pdf) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $descripcion, $estado, $pdfPath);

    if ($stmt->execute()) {
        echo "Equipo creado con éxito.";
        header("Location: index.php"); // Redirige al listado
    } else {
        echo "Error al crear el equipo: " . $conn->error;
    }
}
?>
