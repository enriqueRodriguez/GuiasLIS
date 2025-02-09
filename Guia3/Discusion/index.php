<?php
session_start();
if (!isset($_SESSION['alumnos'])) {
    $_SESSION['alumnos'] = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Listado de Alumnos</title>
    <style>
        th,
        td {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1 style="text-align: center;">Listado de Alumnos</h1>

    <div style="text-align: center; margin-bottom: 20px;">
        <button class="btn btn-primary" onclick="window.location.href='agregar.php'">Agregar Alumno</button>
    </div>

    <div class="container">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Nota 1</th>
                    <th>Nota 2</th>
                    <th>Nota 3</th>
                    <th>Promedio</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($_SESSION['alumnos'] as $alumno) {
                    $promedio = ($alumno['nota1'] + $alumno['nota2'] + $alumno['nota3']) / 3;
                    echo "<tr>";
                    echo "<td style='text-align: left;'>" . $alumno['nombre'] . "</td>";
                    echo "<td style='text-align: left;'>" . $alumno['apellido'] . "</td>";
                    echo "<td>" . $alumno['nota1'] . "</td>";
                    echo "<td>" . $alumno['nota2'] . "</td>";
                    echo "<td>" . $alumno['nota3'] . "</td>";
                    echo "<td>" . number_format($promedio, 2) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>