<?php
try {
    $conn = new PDO('mysql:host=18.116.63.100;dbname=myweb', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexion: " . $e->getMessage());
}
?>
