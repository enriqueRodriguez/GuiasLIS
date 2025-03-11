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
        <div class="row">
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
            //Mostrando la tabla con los autos disponibles
            for ($i = 0; $i < count($movil); $i++) {
                $movil[$i]->mostrar();
            }
            ?>
        </div>
    </div>
</body>

</html>