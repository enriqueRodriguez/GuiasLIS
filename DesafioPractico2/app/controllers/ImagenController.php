<?php
require_once __DIR__ . '/../models/Imagen.php';
require_once __DIR__ . '/Controller.php';

class ImagenController extends Controller
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Imagen($pdo);
    }

    public function index()
    {
        $imagenes = $this->model->getAll();
        $this->render('index.php', ['imagenes' => $imagenes]);
    }

    public function show($id)
    {
        $imagen = $this->model->getById($id);
        $this->render('show.php', ['imagen' => $imagen]);
    }

    public function store($ruta, $idTipoImagen)
    {
        $this->model->create($ruta, $idTipoImagen);
    }
}
