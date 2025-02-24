<?php
session_start();

// Constantes
const ALLOWED_CATEGORIES = ['Textil', 'Promocional'];
const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png'];
const XML_PATH = '../data/productos.xml';
const UPLOAD_DIR = '../uploads/productos/';

function validarDatos($datos)
{
    $errores = [];

    // Validar campos requeridos
    foreach (['nombre', 'descripcion'] as $campo) {
        if (empty($datos[$campo])) {
            $errores[] = "El campo " . ucfirst($campo) . " es requerido";
        }
    }

    // Validar campos numéricos
    if (!is_numeric($datos['precio']) || $datos['precio'] <= 0) {
        $errores[] = "El precio debe ser un número positivo";
    }
    if (!ctype_digit($datos['existencias']) || $datos['existencias'] < 0) {
        $errores[] = "Las existencias deben ser un número entero no negativo";
    }
    if (!in_array($datos['categoria'], ALLOWED_CATEGORIES)) {
        $errores[] = "Categoría no válida";
    }

    return $errores;
}

function validarImagen($archivo, $requerida = true)
{
    // Verificar si se subió una imagen
    if (empty($archivo['name'])) {
        return $requerida ? "La imagen es requerida" : "";
    }

    // Verificar tamaño máximo
    if ($archivo['error'] === UPLOAD_ERR_INI_SIZE) {
        return "La imagen excede el tamaño máximo permitido (8MB)";
    }

    // Verificar tipo de archivo
    if (!in_array($archivo['type'], ALLOWED_IMAGE_TYPES)) {
        return "Solo se permiten archivos JPG o PNG";
    }

    return "";
}

function procesarImagen($archivo, $codigo)
{
    // Obtener extensión del archivo
    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $nombreArchivo = $codigo . '_' . time() . '.' . $extension;
    $rutaDestino = UPLOAD_DIR . $nombreArchivo;

    // Mover archivo y retornar ruta relativa
    return move_uploaded_file($archivo['tmp_name'], $rutaDestino) ?
        'uploads/productos/' . $nombreArchivo : false;
}

function guardarXML($xml)
{
    // Configurar documento XML
    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());
    return $dom->save(XML_PATH);
}

function manejarError($errores, $datos, $modalActivo)
{
    // Almacenar datos en sesión para mostrar errores
    $_SESSION['errores'] = $errores;
    $_SESSION['datos_form'] = $datos;
    $_SESSION['modal_activo'] = $modalActivo;
    header('Location: productos.php');
    exit;
}

function agregarProducto($xml, $datos, $archivo)
{
    // Crear nuevo nodo de producto
    $producto = $xml->addChild('producto');
    $producto->addAttribute('codigo', $datos['codigo']);

    // Agregar campos del producto
    foreach (['nombre', 'descripcion', 'categoria', 'precio', 'existencias'] as $campo) {
        $producto->addChild($campo, $datos[$campo]);
    }

    // Procesar y guardar imagen
    if ($rutaImagen = procesarImagen($archivo, $datos['codigo'])) {
        $producto->addChild('imagen', $rutaImagen);
        return guardarXML($xml);
    }
    return false;
}

function editarProducto($xml, $datos, $archivo)
{
    // Buscar y actualizar producto existente
    foreach ($xml->producto as $producto) {
        if ($producto['codigo'] == $datos['codigo']) {
            // Actualizar campos del producto
            foreach (['nombre', 'descripcion', 'categoria', 'precio', 'existencias'] as $campo) {
                $producto->$campo = $datos[$campo];
            }

            // Actualizar imagen si se proporcionó una nueva
            if (!empty($archivo['name'])) {
                if (file_exists('../' . $producto->imagen)) {
                    unlink('../' . $producto->imagen);
                }
                if ($rutaImagen = procesarImagen($archivo, $datos['codigo'])) {
                    $producto->imagen = $rutaImagen;
                }
            }
            return guardarXML($xml);
        }
    }
    return false;
}

// Manejo de solicitudes POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $xml = simplexml_load_file(XML_PATH);
    $accion = $_POST['accion'];

    switch ($accion) {
        case 'agregar':
            // Validar datos del formulario
            $errores = validarDatos($_POST);
            if ($errorImagen = validarImagen($_FILES['imagen'])) {
                $errores[] = $errorImagen;
            }

            if (!empty($errores)) {
                manejarError($errores, $_POST, 'agregar');
            }

            if (!agregarProducto($xml, $_POST, $_FILES['imagen'])) {
                manejarError(['Error al guardar el producto'], $_POST, 'agregar');
            }
            break;

        case 'editar':
            // Validar datos de edición
            $errores = validarDatos($_POST);
            if ($errorImagen = validarImagen($_FILES['imagen'], false)) {
                $errores[] = $errorImagen;
            }

            if (!empty($errores)) {
                manejarError($errores, $_POST, 'editar_' . str_replace('PROD', '', $_POST['codigo']));
            }

            editarProducto($xml, $_POST, $_FILES['imagen']);
            break;

        case 'eliminar':
            // Eliminar producto y su imagen
            foreach ($xml->producto as $producto) {
                if ($producto['codigo'] == $_POST['codigo']) {
                    @unlink('../' . $producto->imagen);
                    $dom = dom_import_simplexml($producto);
                    $dom->parentNode->removeChild($dom);
                    break;
                }
            }
            guardarXML($xml);
            break;
    }

    header('Location: productos.php');
    exit;
}
