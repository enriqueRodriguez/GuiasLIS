<?php
require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/Controller.php';

class VentaController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new Venta();
    }

    public function index()
    {
        $ventas = $this->model->getAll();
        $this->render('index.php', ['ventas' => $ventas]);
    }

    public function show($id)
    {
        $venta = $this->model->getById($id);
        $this->render('show.php', ['venta' => $venta]);
    }

    public function store($idUsuario, $total)
    {
        $this->model->create($idUsuario, $total);
    }
}
