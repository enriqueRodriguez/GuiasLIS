<?php
if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $materias = simplexml_load_file("materias.xml");
    $indexToDelete = -1;
    $found = false;
    foreach ($materias->materia as $materia) {
        $indexToDelete++;
        if ($materia->codigo == $codigo) {
            $found = true;
            break;
        }
    }

    if ($found) {
        unset($materias->materia[$indexToDelete]);
        file_put_contents("materias.xml", $materias->asXML());
        header("location:index.php");
    }
}
