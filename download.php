<?php
if (isset($_GET['filename'])) {
    $filename = $_GET['filename'];
    $file_path = "files/" . $filename;
    if (file_exists($file_path)) {
        $file_size = filesize($file_path);
        $file_name = basename($file_path);
        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$file_name\"");
        header("Content-Length: $file_size");
        header("Cache-Control: must-revalidate");
        header("Pragma: public");
        header("Expires: 0");
        readfile($file_path);
        exit();
    } else {
        die("Archivo no encontrado.");
    }
} else {
    die("No se especifico el archivo para descargar.");
}
?>
