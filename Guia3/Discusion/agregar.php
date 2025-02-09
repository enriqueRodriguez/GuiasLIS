<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $nota1 = $_POST['nota1'];
    $nota2 = $_POST['nota2'];
    $nota3 = $_POST['nota3'];

    $nuevoAlumno = [
        'nombre' => $nombre,
        'apellido' => $apellido,
        'nota1' => $nota1,
        'nota2' => $nota2,
        'nota3' => $nota3
    ];

    $_SESSION['alumnos'][] = $nuevoAlumno;
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Agregar Alumno</title>
</head>

<body>
    <div class="container">
        <h1 style="text-align: center;">Agregar Alumno</h1>
        <form action="agregar.php" method="post">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" class="form-control" id="apellido" name="apellido" required>
            </div>
            <div class="form-group">
                <label for="nota1">Nota 1:</label>
                <input type="number" class="form-control" id="nota1" name="nota1" min="0" max="10" step="0.1" required>
            </div>
            <div class="form-group">
                <label for="nota2">Nota 2:</label>
                <input type="number" class="form-control" id="nota2" name="nota2" min="0" max="10" step="0.1" required>
            </div>
            <div class="form-group">
                <label for="nota3">Nota 3:</label>
                <input type="number" class="form-control" id="nota3" name="nota3" min="0" max="10" step="0.1" required>
            </div>
            <button type="submit" class="btn btn-primary">Agregar</button>
            <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">Volver</button>
        </form>
    </div>
</body>

</html>