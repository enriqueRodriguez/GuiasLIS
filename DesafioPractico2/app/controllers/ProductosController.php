<?php
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/Controller.php';

class ProductoController extends Controller
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Producto($pdo);
    }

    public function index()
    {
        $productos = $this->model->getAll();
        $this->render('index.php', ['productos' => $productos]);
    }

    public function show($id)
    {
        $producto = $this->model->getById($id);
        $this->render('show.php', ['producto' => $producto]);
    }

    public function store($data)
    {
        $this->model->create($data);
    }
}
