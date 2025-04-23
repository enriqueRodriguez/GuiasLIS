<?php
session_start();
$archivo = $_GET['archivo'] ?? '';
$ruta = sys_get_temp_dir() . '/' . basename($archivo);
if (is_file($ruta)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($archivo) . '"');
    readfile($ruta);
    unlink($ruta); // Borra el archivo después de descargar
    exit;
}
echo "Archivo no encontrado.";
