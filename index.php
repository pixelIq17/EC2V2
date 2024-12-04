<?php
// Incluye la conexión a la base de datos
include('db.php');
// Función para formatear el tamaño del archivo
function formatSize($size) {
    if ($size >= 1073741824) {
        return number_format($size / 1073741824, 2) . ' GB';
    } elseif ($size >= 1048576) {
        return number_format($size / 1048576, 2) . ' MB';
    } elseif ($size >= 1024) {
        return number_format($size / 1024, 2) . ' KB';
    } else {
        return $size . ' bytes';
    }
}

// Subir archivo
if (isset($_POST['submit'])) {
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $name = $_FILES['photo']['name'];
        $size = $_FILES['photo']['size'];
        $type = $_FILES['photo']['type'];
        $temp = $_FILES['photo']['tmp_name'];
        $date = date('Y-m-d H:i:s');
        $sizeFormatted = formatSize($size);

        $target_dir = "files/";
        $target_file = $target_dir . basename($name);

        if (move_uploaded_file($temp, $target_file)) {
            try {
                $stmt = $conn->prepare("INSERT INTO upload (name, size, date) VALUES (:name, :size, :date)");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':size', $sizeFormatted);
                $stmt->bindParam(':date', $date);

                if ($stmt->execute()) {
                    header("Location: index.php");
                    exit();
                } else {
                    die("Error al insertar el archivo en la base de datos.");
                }
            } catch (PDOException $e) {
                die("Error al insertar archivo: " . $e->getMessage());
            }
        } else {
            die("Error al mover el archivo.");
        }
    } else {
        die("Error al subir el archivo.");
    }
}

// Eliminar archivo
if (isset($_GET['del'])) {
    $del = $_GET['del'];

    $stmt = $conn->prepare("SELECT * FROM upload WHERE id = :id");
    $stmt->bindParam(':id', $del);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $fileName = $row['name'];
        $filePath = "files/" . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $stmt = $conn->prepare("DELETE FROM upload WHERE id = :id");
        $stmt->bindParam(':id', $del);
        $stmt->execute();

        header("Location: index.php");
        exit();
    } else {
        die("El archivo no existe.");
    }
}

// Listar archivos
$stmt = $conn->query("SELECT * FROM upload ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestor de Archivos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
        }
        .container {
            margin-top: 50px;
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
        }
        .card-body {
            background-color: #fff;
        }
        .btn {
            font-size: 1.1rem;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-info, .btn-danger {
            width: 100%;
        }
        .alert {
            background-color: #17a2b8;
            color: #fff;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Cabecera -->
    <div class="alert alert-info mb-4">
        <strong>Gestor de Archivos</strong>
    </div>

    <!-- Formulario de subida de archivos -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Subir un nuevo archivo</h3>
        </div>
        <div class="card-body">
            <form enctype="multipart/form-data" method="post">
                <div class="input-group mb-3">
                    <input type="file" name="photo" required class="form-control">
                    <button type="submit" name="submit" class="btn btn-success">Subir Archivo <i class="fas fa-upload"></i></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de archivos -->
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Archivos Subidos</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Archivo</th>
                        <th>Fecha</th>
                        <th>Tamaño</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['size']; ?></td>
                        <td>
                            <a href="download.php?filename=<?php echo $row['name']; ?>" class="btn btn-info">
                                <i class="fas fa-download"></i> Descargar
                            </a>
                            <a href="index.php?del=<?php echo $row['id']; ?>" class="btn btn-danger mt-2">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Scripts de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
