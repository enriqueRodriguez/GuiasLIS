<?php
include_once __DIR__ . '/../app/controllers/ProductosController.php';
include_once __DIR__ . '/../app/controllers/UsuarioController.php';
include_once __DIR__ . '/../app/controllers/ClientesController.php';
include_once __DIR__ . '/../app/controllers/VentaController.php';
include_once __DIR__ . '/../app/controllers/IndexController.php';
include_once __DIR__ . '/../app/controllers/AdministracionController.php';

$url =  $_SERVER['REQUEST_URI'];
$slices = explode('/', $url);

$controller = empty($slices[1]) ? "IndexController" : $slices[1] . "Controller";
$method = empty($slices[2]) ? "index" : $slices[2];
$params = empty($slices[3]) ? [] : array_slice($slices, 3);

if (class_exists($controller) && method_exists($controller, $method)) {
    $cont = new $controller;
    $cont->$method(...$params);
} else {
    echo "<h1>Error: Controlador o método no encontrado</h1>";
}
