<?php
// db.php

try {
    // Establece la conexión a la base de datos usando PDO
    $conn = new PDO("mysql:host=13.58.47.110;dbname=myweb';charset=utf8", "root", "");

    // Establece el modo de error a excepciones
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Maneja los errores de conexión
    die("Error de conexión: " . $e->getMessage());
}
?>
