<?php
require_once __DIR__ . '/../models/VentaProducto.php';
require_once __DIR__ . '/Controller.php';

class VentaProductoController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new VentaProducto();
    }

    public function index()
    {
        $ventasProductos = $this->model->getAll();
        $this->render('index.php', ['ventasProductos' => $ventasProductos]);
    }

    public function show($id)
    {
        $ventaProducto = $this->model->getById($id);
        $this->render('show.php', ['ventaProducto' => $ventaProducto]);
    }

    public function store($data)
    {
        $this->model->create($data);
    }
}
