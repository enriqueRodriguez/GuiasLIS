<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximumscale=1">
    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Venta de autos</title>
    <!--[if lt IE 9]>
 <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
 <![endif]-->
</head>

<body>
    <div class="container">
        <header>
            <h1>Autos disponibles</h1>
        </header>

        <?php
        spl_autoload_register(function ($class) {
            if (is_file("class/{$class}.class.php")) {
                include_once("class/{$class}.class.php");
            } else {
                die("class/{$class}.class.php No existe en el proyecto");
            }
        });

        //Creando los objetos para cada tipo de auto. Notar que se están
        //asignando a elementos de una matriz que tendrá por nombre $movil
        $movil[0] = new auto("Peugeot", "307", "Gris", "img/peugeot.jpg");
        $movil[1] = new auto("Renault", "Clio", "Rojo", "img/renaultclio.jpg");
        $movil[2] = new auto("BMW", "X3", "Negro", "img/bmwserie6.jpg");
        $movil[3] = new auto("Toyota", "Avalon", "Blanco", "img/toyota.jpg");
        //Esta llamada mostrará los valores por defecto en los argumentos
        //del método constructor.
        $movil[4] = new auto();
        ?>

        <!-- Formulario para seleccionar el auto a mostrar -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-inline">
                    <div class="form-group mr-2">
                        <label for="autoSelect" class="mr-2">Seleccionar auto:</label>
                        <select name="autoSelect" id="autoSelect" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Seleccione un auto --</option>
                            <?php
                            // Generando las opciones del select con los autos disponibles
                            for ($i = 0; $i < count($movil); $i++) {
                                $selected = (isset($_POST['autoSelect']) && $_POST['autoSelect'] == $i) ? 'selected' : '';
                                $auto = $movil[$i]->getAuto();
                                echo "<option value=\"$i\" $selected>$auto</option>";
                            }
                            ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <?php
            // Mostrar auto seleccionado o mensaje si no se ha seleccionado ninguno
            if (isset($_POST['autoSelect']) && $_POST['autoSelect'] !== '') {
                $indice = (int)$_POST['autoSelect'];
                if (isset($movil[$indice])) {
                    $movil[$indice]->mostrar();
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                echo '<div class="col-12"><div class="alert alert-warning">Por favor seleccione un auto para ver su información.</div></div>';
            } else {
                // Primera carga de página - mostrar mensaje informativo
                echo '<div class="col-12"><div class="alert alert-info">Seleccione un auto del menú desplegable para ver su información.</div></div>';
            }
            ?>
        </div>
    </div>
</body>

</html>