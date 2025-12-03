<?php
$conn = new mysqli("localhost", "root", "", "escueladb");
if ($conn->connect_error) die('<div class="alert alert-danger">Error de conexión: ' . $conn->connect_error . '</div>');

// Insertar nuevo alumno
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insertar'])) {
    $dni = trim($_POST['dni']);
    $legajo = trim($_POST['legajo']);
    $nombre = trim($_POST['nombre']);
    $curso = trim($_POST['curso']);

    if (empty($dni) || empty($legajo) || empty($nombre) || empty($curso)) {
        echo '<div class="alert alert-danger">Por favor, ingresa todos los valores.</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO persona (dni, n_legajo, nombre, curso) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $dni, $legajo, $nombre, $curso);
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Datos insertados correctamente.</div>';
        } else {
            echo '<div class="alert alert-danger">Error al insertar los datos: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}

// Listar alumnos
$resultado = $conn->query("SELECT pk_alumno, n_legajo, dni, nombre, curso FROM persona ORDER BY pk_alumno DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos - ABM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">Lista de Alumnos</h1>
    <a href="index.php" class="btn btn-outline-secondary mb-3">← Volver al Inicio</a>

    <?php
    if ($resultado && $resultado->num_rows > 0) {
        echo '<table class="table table-striped table-bordered">';
        echo '<thead class="table-dark"><tr><th>ID</th><th>Legajo</th><th>DNI</th><th>Nombre</th><th>Curso</th></tr></thead><tbody>';
        while ($fila = $resultado->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($fila["pk_alumno"]) . '</td>';
            echo '<td>' . htmlspecialchars($fila["n_legajo"]) . '</td>';
            echo '<td>' . htmlspecialchars($fila["dni"]) . '</td>';
            echo '<td>' . htmlspecialchars($fila["nombre"]) . '</td>';
            echo '<td>' . htmlspecialchars($fila["curso"]) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-info">No se encontraron alumnos registrados.</div>';
    }
    ?>

    <hr class="mt-5">
    <h2>Ingresar Nuevo Alumno</h2>
    <form action="alumnos.php" method="POST">
        <div class="mb-3"><label>DNI</label><input type="text" name="dni" class="form-control" required></div>
        <div class="mb-3"><label>Legajo</label><input type="text" name="legajo" class="form-control" required></div>
        <div class="mb-3"><label>Nombre</label><input type="text" name="nombre" class="form-control" required></div>
        <div class="mb-3"><label>Curso</label><input type="text" name="curso" class="form-control" required></div>
        <button type="submit" name="insertar" class="btn btn-primary">Insertar Datos</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
