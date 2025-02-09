<?php
$alumnos = [
    [
        "nombre" => "Enrique",
        "apellido" => "Rodriguez",
        "carnet" => "er",
        "CUM" => 7,
        "materias" => ["mat1", "mat2", "mat3"]
    ],
    [
        "nombre" => "Jose",
        "apellido" => "Velasquez",
        "carnet" => "jv",
        "CUM" => 5,
        "materias" => ["mat4", "mat5", "mat6"]
    ],
    [
        "nombre" => "Karla",
        "apellido" => "Mendez",
        "carnet" => "jm",
        "CUM" => 8,
        "materias" => ["mat7", "mat8", "mat9"]
    ]
];
?>

<table border="1">
    <tr>
        <th>Nombre: </th>
        <th>Apellido</th>
        <th>Carnet</th>
        <th>CUM</th>
        <th>Materias inscritas</th>
    </tr>
    <?php
    //var_dump($alumnos);
    foreach ($alumnos as $alumno) {
    ?>
        <tr>
            <td><?= $alumno["nombre"] ?></td>
            <td><?= $alumno["apellido"] ?></td>
            <td><?= $alumno["carnet"] ?></td>
            <td><?= $alumno["CUM"] ?></td>
            <td><?= implode(" ",$alumno["materias"]) ?></td>
        </tr>
    <?php
    }
    ?>

</table>