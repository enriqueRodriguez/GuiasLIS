<?php
include_once __DIR__ . '/app/controllers/CategoriaController.php';
include_once __DIR__ . '/app/controllers/ImagenController.php';
include_once __DIR__ . '/app/controllers/ProductosController.php';
include_once __DIR__ . '/app/controllers/TipoImagenController.php';
include_once __DIR__ . '/app/controllers/TipoUsuarioController.php';
include_once __DIR__ . '/app/controllers/UsuarioController.php';
include_once __DIR__ . '/app/controllers/VentaController.php';
include_once __DIR__ . '/app/controllers/VentaProductoController.php';
include_once __DIR__ . '/app/controllers/IndexController.php';

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
