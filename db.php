<?php
try {
    $conn = new PDO('mysql:host=13.58.47.110;dbname=myweb', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexion: " . $e->getMessage());
}
?>
