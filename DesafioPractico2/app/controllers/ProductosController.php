<?php
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/Controller.php';

class ProductosController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new Producto();
    }

    public function index($pagina = 1)
    {
        $productosPorPagina = 6;
        $pagina = (int)$pagina > 0 ? (int)$pagina : 1;

        $totalProductos = $this->model->countAll();
        $totalPaginas = max(1, ceil($totalProductos / $productosPorPagina));
        $pagina = min($pagina, $totalPaginas);
        $inicio = ($pagina - 1) * $productosPorPagina;

        $productos = $this->model->getPaged($inicio, $productosPorPagina);

        $this->render('index.php', [
            'productos' => $productos,
            'paginaActual' => $pagina,
            'totalPaginas' => $totalPaginas
        ]);
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
