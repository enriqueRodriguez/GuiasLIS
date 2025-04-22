<?php
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/Controller.php';

class CategoriaController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new Categoria();
    }

    public function index()
    {
        $categorias = $this->model->getAll();
        $this->render('index.php', ['categorias' => $categorias]);
    }

    public function show($id)
    {
        $categoria = $this->model->getById($id);
        $this->render('show.php', ['categoria' => $categoria]);
    }

    public function store($descripcion)
    {
        $this->model->create($descripcion);
    }
}
