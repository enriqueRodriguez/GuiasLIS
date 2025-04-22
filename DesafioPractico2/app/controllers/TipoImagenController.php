<?php
require_once __DIR__ . '/../models/TipoImagen.php';
require_once __DIR__ . '/Controller.php';

class TipoImagenController extends Controller
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new TipoImagen($pdo);
    }

    public function index()
    {
        $tipos = $this->model->getAll();
        $this->render('index.php', ['tipos' => $tipos]);
    }

    public function show($id)
    {
        $tipo = $this->model->getById($id);
        $this->render('show.php', ['tipo' => $tipo]);
    }

    public function store($descripcion)
    {
        $this->model->create($descripcion);
    }
}
