<?php
// db.php

try {
    // Establece la conexión a la base de datos usando PDO
    $conn = new PDO('mysql:host=localhost;dbname=myweb', 'root', '');
    // Establece el modo de error a excepciones
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Maneja los errores de conexión
    die("Error de conexión: " . $e->getMessage());
}
?>
