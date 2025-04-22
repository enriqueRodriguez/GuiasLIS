<?php
require_once __DIR__ . '/../models/TipoUsuario.php';
require_once __DIR__ . '/Controller.php';

class TipoUsuarioController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new TipoUsuario();
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
        // Puedes redirigir o renderizar una vista de éxito
    }
}
