<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversor de Unidades</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Conversor de Unidades</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input type="number" class="form-control" id="cantidad" name="cantidad" value="<?php echo isset($_POST['cantidad']) ? htmlspecialchars($_POST['cantidad']) : '0'; ?>" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="unidad_origen">Unidad de Origen:</label>
                <select class="form-control" id="unidad_origen" name="unidad_origen" onchange="this.form.submit()">
                    <option value="metros" <?php if (isset($_POST['unidad_origen']) && $_POST['unidad_origen'] == 'metros') echo 'selected'; ?>>Metros</option>
                    <option value="yardas" <?php if (isset($_POST['unidad_origen']) && $_POST['unidad_origen'] == 'yardas') echo 'selected'; ?>>Yardas</option>
                    <option value="pies" <?php if (isset($_POST['unidad_origen']) && $_POST['unidad_origen'] == 'pies') echo 'selected'; ?>>Pies</option>
                    <option value="varas" <?php if (isset($_POST['unidad_origen']) && $_POST['unidad_origen'] == 'varas') echo 'selected'; ?>>Varas</option>
                </select>
            </div>
            <div class="form-group">
                <label for="unidad_destino">Unidad de Destino:</label>
                <select class="form-control" id="unidad_destino" name="unidad_destino" onchange="this.form.submit()">
                    <?php
                    $opciones = ["metros", "yardas", "pies", "varas"];
                    $unidad_origen = isset($_POST['unidad_origen']) ? $_POST['unidad_origen'] : 'metros';
                    foreach ($opciones as $opcion) {
                        if ($opcion != $unidad_origen) {
                            $selected = (isset($_POST['unidad_destino']) && $_POST['unidad_destino'] == $opcion) ? 'selected' : '';
                            echo "<option value='$opcion' $selected>" . ucfirst($opcion) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Convertir</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cantidad']) && isset($_POST['unidad_origen']) && isset($_POST['unidad_destino'])) {
            $cantidad = $_POST['cantidad'];
            $unidad_origen = $_POST['unidad_origen'];
            $unidad_destino = $_POST['unidad_destino'];

            if (!is_numeric($cantidad) || $cantidad < 0) {
                echo "<div class='alert alert-danger mt-3'>Por favor, ingrese una cantidad válida.</div>";
            } else {
                $cantidad = floatval($cantidad);

                $resultado = convertir($cantidad, $unidad_origen, $unidad_destino);
                $resultado_formateado = number_format($resultado, 2);
                echo "<div class='alert alert-success mt-3'>Resultado: $cantidad $unidad_origen son $resultado_formateado $unidad_destino</div>";
            }
        }

        function convertir($cantidad, $unidad_origen, $unidad_destino)
        {
            $conversiones = [
                'metros' => ['yardas' => 1.09361, 'pies' => 3.28084, 'varas' => 1.19631],
                'yardas' => ['metros' => 0.9144, 'pies' => 3, 'varas' => 1.09361],
                'pies' => ['metros' => 0.3048, 'yardas' => 0.333333, 'varas' => 0.357143],
                'varas' => ['metros' => 0.836127, 'yardas' => 0.9144, 'pies' => 2.77778]
            ];

            if ($unidad_origen == $unidad_destino) {
                return $cantidad;
            }

            return $cantidad * $conversiones[$unidad_origen][$unidad_destino];
        }
        ?>
    </div>
</body>

</html>