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

        // Agregar mensaje de éxito
        $_SESSION['mensaje_exito'] = 'Producto agregado al carrito correctamente.';

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

    public function checkout()
    {
        session_start();
        $carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];

        // Validación de usuario logueado y tipo cliente
        if (
            !isset($_SESSION['id_usuario']) ||
            !isset($_SESSION['tipo_usuario']) ||
            $_SESSION['tipo_usuario'] != 3
        ) {
            $_SESSION['mensaje_error'] = 'Debes iniciar sesión como cliente para comprar.';
            header('Location: /Productos/cart');
            exit;
        }

        // Simulación de pago (validación simple)
        $cardNumber = $_POST['card_number'] ?? '';
        $cardName = $_POST['card_name'] ?? '';
        $expiryDate = $_POST['expiry_date'] ?? '';
        $cvv = $_POST['cvv'] ?? '';

        if (empty($carrito) || !$cardNumber || !$cardName || !$expiryDate || !$cvv) {
            $_SESSION['mensaje_error'] = 'Datos de pago incompletos o carrito vacío.';
            header('Location: /Productos/cart');
            exit;
        }

        $idUsuario = $_SESSION['id_usuario'];
        $nombreCliente = $_SESSION['nombre'] . ' ' . $_SESSION['apellido'];

        // Calcular total
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        // Registrar venta usando el modelo y obtener el ID de la venta
        require_once __DIR__ . '/../models/Venta.php';
        $ventaModel = new \Venta();
        $idVenta = $ventaModel->create($idUsuario, $total);

        // Registrar productos vendidos
        require_once __DIR__ . '/../models/VentaProducto.php';
        $ventaProductoModel = new \VentaProducto();
        foreach ($carrito as $item) {
            $ventaProductoModel->create([
                'IdVenta' => $idVenta,
                'IdProducto' => $item['id'],
                'Cantidad' => $item['cantidad'],
                'Precio' => $item['precio'],
                'Total' => $item['precio'] * $item['cantidad']
            ]);
            // Actualizar cantidad usando el modelo Producto
            $this->model->restarCantidad($item['id'], $item['cantidad']);
        }

        // Generar PDF de comprobante
        require_once __DIR__ . '/../../vendor/autoload.php';
        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, mb_convert_encoding('Comprobante de Compra', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'ID Venta: ' . $idVenta, 0, 1);
        $pdf->Cell(0, 10, 'Cliente: ' . mb_convert_encoding($nombreCliente, 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->Ln(5);

        // Calcular el ancho máximo necesario para la columna "Producto"
        $maxWidth = 90; // ancho mínimo
        foreach ($carrito as $item) {
            $width = $pdf->GetStringWidth(mb_convert_encoding($item['nombre'], 'ISO-8859-1', 'UTF-8')) + 10; // margen extra
            if ($width > $maxWidth) {
                $maxWidth = $width;
            }
        }

        // Encabezados
        $pdf->Cell($maxWidth, 10, 'Producto', 1);
        $pdf->Cell(30, 10, 'Cantidad', 1);
        $pdf->Cell(30, 10, 'Precio', 1);
        $pdf->Cell(30, 10, 'Subtotal', 1);
        $pdf->Ln();

        // Filas
        foreach ($carrito as $item) {
            $pdf->Cell($maxWidth, 10, mb_convert_encoding($item['nombre'], 'ISO-8859-1', 'UTF-8'), 1);
            $pdf->Cell(30, 10, $item['cantidad'], 1);
            $pdf->Cell(30, 10, '$' . number_format($item['precio'], 2), 1);
            $pdf->Cell(30, 10, '$' . number_format($item['precio'] * $item['cantidad'], 2), 1);
            $pdf->Ln();
        }
        $pdf->Cell($maxWidth + 60, 10, 'Total', 1);
        $pdf->Cell(30, 10, '$' . number_format($total, 2), 1);
        $pdf->Ln();

        // Guardar PDF en carpeta permanente
        $nombreArchivo = 'comprobante_venta_' . $idVenta . '.pdf';
        $rutaCarpeta = __DIR__ . '/../../public/comprobantes/';
        if (!is_dir($rutaCarpeta)) {
            mkdir($rutaCarpeta, 0777, true);
        }
        $rutaArchivo = $rutaCarpeta . $nombreArchivo;
        $pdf->Output('F', $rutaArchivo);

        // Actualizar la venta con la ruta del comprobante
        $ventaModel->updateRutaComprobante($idVenta, '/comprobantes/' . $nombreArchivo);

        // Guardar el ID de la venta en sesión
        $_SESSION['venta_reciente_id'] = $idVenta;

        // Limpiar carrito
        unset($_SESSION['carrito']);

        // Redirigir a /Productos
        header('Location: /Productos');
        exit;
    }

    public function clearComprobante()
    {
        session_start();
        unset($_SESSION['venta_reciente_id']);
        http_response_code(204);
        exit;
    }
}
