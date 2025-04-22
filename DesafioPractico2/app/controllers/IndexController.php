<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Imagen.php';

class IndexController extends Controller
{
    private $imagenModel;

    public function __construct()
    {
        $this->imagenModel = new Imagen();
    }

    public function index()
    {
        // Obtén las imágenes del carrusel (IdTipoImagen = 1 asumiendo que es el tipo para carrusel)
        $imagenesCarrusel = $this->imagenModel->getImagenesCarrousel(1);

        // Renderiza la vista y pasa las imágenes
        $this->render('index.php', ['imagenesCarrusel' => $imagenesCarrusel]);
    }
}
