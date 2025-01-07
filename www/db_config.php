<?php
// Parámetros de conexión a la base de datos
$servername = "localhost";  // o tu IP de servidor, como '127.0.0.1'
$username = "root";         // tu usuario de MySQL (por defecto "root" en localhost)
$password = "12341234";             // tu contraseña de MySQL (deja vacío si no tienes)
$dbname = "pdf_proyect";    // el nombre de la base de datos que creaste

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
//echo "Conexión exitosa";
?>
