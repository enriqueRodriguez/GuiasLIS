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

    public function addToCart()
    {
        session_start();
        $idProducto = $_POST['id_producto'] ?? null;
        $cantidad = (int)($_POST['cantidad'] ?? 1);

        if (!$idProducto || $cantidad < 1) {
            header('Location: /Productos/index');
            exit;
        }

        // Obtener información del producto
        $producto = $this->model->getById($idProducto);
        if (!$producto) {
            header('Location: /Productos/index');
            exit;
        }

        // Inicializar el carrito si no existe o si no es un array
        if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        // Calcular la cantidad total que tendría el producto en el carrito
        $cantidadEnCarrito = isset($_SESSION['carrito'][$idProducto]) ? $_SESSION['carrito'][$idProducto]['cantidad'] : 0;
        $cantidadDisponible = (int)($producto['Cantidad'] ?? 0);

        // Validar que no se agregue más de lo disponible
        if ($cantidadEnCarrito + $cantidad > $cantidadDisponible) {
            $_SESSION['mensaje_error'] = 'No puedes agregar más productos de los que hay en existencia.';
            header('Location: /Productos/index');
            exit;
        }

        // Si el producto ya está en el carrito, sumar la cantidad
        if (isset($_SESSION['carrito'][$idProducto])) {
            $_SESSION['carrito'][$idProducto]['cantidad'] += $cantidad;
        } else {
            $_SESSION['carrito'][$idProducto] = [
                'id' => $producto['IdProducto'],
                'nombre' => $producto['Nombre'],
                'precio' => $producto['Precio'],
                'cantidad' => $cantidad,
                'ruta' => $producto['Ruta'] ?? ''
            ];
        }

        // Redirigir de vuelta a la página de productos
        header('Location: /Productos/index');
        exit;
    }

    public function removeFromCart()
    {
        session_start();
        $idProducto = $_POST['id_producto'] ?? null;
        if ($idProducto && isset($_SESSION['carrito'][$idProducto])) {
            unset($_SESSION['carrito'][$idProducto]);
        }
        header('Location: /Productos/index');
        exit;
    }

    public function cart()
    {
        session_start();
        $carrito = isset($_SESSION['carrito']) && is_array($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
        $this->render('cart.php', ['carrito' => $carrito]);
    }
}
