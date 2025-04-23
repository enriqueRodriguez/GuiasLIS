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
        $buscar = $_GET['buscar'] ?? '';
        $categoriaSeleccionada = $_GET['categoria'] ?? '';
        $productosPorPagina = 6;
        $pagina = (int)$pagina > 0 ? (int)$pagina : 1;

        if ($buscar !== '' || $categoriaSeleccionada !== '') {
            $productosFiltrados = $this->model->buscarPorNombreYCategoria($buscar, $categoriaSeleccionada);
            $totalProductos = count($productosFiltrados);
            $totalPaginas = max(1, ceil($totalProductos / $productosPorPagina));
            $pagina = min($pagina, $totalPaginas);
            $inicio = ($pagina - 1) * $productosPorPagina;
            $productos = array_slice($productosFiltrados, $inicio, $productosPorPagina);
        } else {
            $totalProductos = $this->model->countAll();
            $totalPaginas = max(1, ceil($totalProductos / $productosPorPagina));
            $pagina = min($pagina, $totalPaginas);
            $inicio = ($pagina - 1) * $productosPorPagina;
            $productos = $this->model->getPaged($inicio, $productosPorPagina);
        }

        $this->render('index.php', [
            'productos' => $productos,
            'paginaActual' => $pagina,
            'totalPaginas' => $totalPaginas,
            'buscar' => $buscar,
            'categorias' => $this->model->getCategorias(),
            'categoriaSeleccionada' => $categoriaSeleccionada
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
