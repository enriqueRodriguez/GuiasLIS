<?php 
    $edades =[10,14,25,96,96.7];
    
    echo $edades[0]."<br/>";

    $edades[1] = 28;
    array_push($edades,100);
    $edades[10] = 10;
    array_push($edades,101);
    unset($edades[0]);

    print_r($edades);

    echo "<h2>Recorriendo el arreglo</h2>";

    foreach ($edades as $edad) {
        echo "<p>$edad</p>";
    }

    $tamanio = count($edades);
    echo "<p>El tamaño del arreglo es $tamanio</p>";

    sort($edades);
    $edades = array_reverse($edades);
    print_r($edades);


    $datos_personales=[];
    $datos_personales['nombre']="Enrique";
    $datos_personales["apellido"]= "Rodriguez";
    $datos_personales["estatura"]= 1.80;
    $datos_personales["genero"]= "Masculino";
    print_r($datos_personales);
    echo "<h2>Imprimiendo los elementos del arreglo asociativo</h2>";

    foreach ($datos_personales as $clave => $dato) {
        echo "<p>$clave: $dato</p>";
    }
    
?>