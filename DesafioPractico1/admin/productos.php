<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Capturar errores de la sesión
$errores = isset($_SESSION['errores']) ? $_SESSION['errores'] : [];
$datosForm = isset($_SESSION['datos_form']) ? $_SESSION['datos_form'] : [];
$modalActivo = isset($_SESSION['modal_activo']) ? $_SESSION['modal_activo'] : '';

// Cargar el archivo XML y verificar que se cargó correctamente
$xml = simplexml_load_file('../data/productos.xml');
if ($xml === false) {
    die('Error al cargar el archivo XML');
}

// Convertir SimpleXML a array para mejor manejo
$productosArray = [];
foreach ($xml->producto as $producto) {
    $productosArray[] = $producto;
}

// Configuración de paginación
$productosPorPagina = 8;
$totalProductos = count($productosArray);
$totalPaginas = ceil($totalProductos / $productosPorPagina);
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

// Asegurar que la página actual esté dentro de los límites
$paginaActual = max(1, min($paginaActual, $totalPaginas));

// Calcular el índice de inicio para la página actual
$inicio = ($paginaActual - 1) * $productosPorPagina;

// Obtener los productos para la página actual
$productosEnPagina = array_slice($productosArray, $inicio, $productosPorPagina);

// Obtener el último código de producto para generar el siguiente
$ultimoCodigo = "00000";
foreach ($xml->producto as $producto) {
    $codigo = substr($producto['codigo'], 4);
    if ($codigo > $ultimoCodigo) {
        $ultimoCodigo = $codigo;
    }
}
$siguienteCodigo = "PROD" . str_pad($ultimoCodigo + 1, 5, "0", STR_PAD_LEFT);

// Limpiar las variables de sesión
if (isset($_SESSION['errores'])) unset($_SESSION['errores']);
if (isset($_SESSION['datos_form'])) unset($_SESSION['datos_form']);
if (isset($_SESSION['modal_activo'])) unset($_SESSION['modal_activo']);

session_write_close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TextilExport - Administración de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
</head>

<body class="admin-page">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../">TextilExport</a>
            <div class="ms-auto">
                <a href="logout.php" class="btn btn-outline-light">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero Section -->
        <div class="hero-section text-center">
            <div class="container">
                <h1 class="display-4">Gestión de Productos</h1>
                <p class="lead">Administra el catálogo de productos textiles y promocionales</p>
            </div>
        </div>

        <!-- Admin Content -->
        <div class="container my-5">
            <!-- Action Button -->
            <div class="text-center mb-4">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarProductoModal">
                    Agregar Producto
                </button>
            </div>

            <!-- Products Table Card -->
            <div class="card admin-card bg-white">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Imagen</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Precio</th>
                                    <th>Existencias</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($productosEnPagina)): ?>
                                    <?php foreach ($productosEnPagina as $producto): ?>
                                        <tr>
                                            <td><?php echo $producto['codigo']; ?></td>
                                            <td>
                                                <img src="../<?php echo $producto->imagen; ?>"
                                                    class="img-thumbnail"
                                                    alt="<?php echo htmlspecialchars($producto->nombre); ?>"
                                                    style="max-width: 50px; height: auto;">
                                            </td>
                                            <td><?php echo htmlspecialchars($producto->nombre); ?></td>
                                            <td><?php echo htmlspecialchars($producto->categoria); ?></td>
                                            <td>$<?php echo number_format((float)$producto->precio, 2); ?></td>
                                            <td><?php echo $producto->existencias; ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#editarProductoModal<?php echo str_replace('PROD', '', $producto['codigo']); ?>">
                                                    Editar
                                                </button>
                                                <form action="manejoProductos.php" method="POST" style="display: inline;">
                                                    <input type="hidden" name="accion" value="eliminar">
                                                    <input type="hidden" name="codigo" value="<?php echo $producto['codigo']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('¿Está seguro de eliminar este producto?')">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No hay productos para mostrar</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <!-- Paginación -->
                        <nav aria-label="Navegación de productos">
                            <ul class="pagination justify-content-center">
                                <!-- Botón Anterior -->
                                <li class="page-item <?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?pagina=<?php echo $paginaActual - 1; ?>" <?php echo ($paginaActual <= 1) ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>Anterior</a>
                                </li>

                                <!-- Números de página -->
                                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                    <li class="page-item <?php echo ($paginaActual == $i) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?pagina=<?php echo $i; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Botón Siguiente -->
                                <li class="page-item <?php echo ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?pagina=<?php echo $paginaActual + 1; ?>" <?php echo ($paginaActual >= $totalPaginas) ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>Siguiente</a>
                                </li>
                            </ul>
                        </nav>

                        <!-- Información de paginación -->
                        <div class="text-center text-muted mt-2">
                            <small>
                                Mostrando <?php echo $inicio + 1; ?> - <?php echo min($inicio + $productosPorPagina, $totalProductos); ?>
                                de <?php echo $totalProductos; ?> productos
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 TextilExport. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Modal Agregar Producto -->
    <div class="modal fade" id="agregarProductoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($errores) && $modalActivo === 'agregar'): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errores as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <form action="manejoProductos.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="accion" value="agregar">
                        <input type="hidden" name="codigo" value="<?php echo $siguienteCodigo; ?>">

                        <div class="mb-3">
                            <label class="form-label">Código</label>
                            <input type="text" class="form-control" value="<?php echo $siguienteCodigo; ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control"
                                value="<?php echo (!empty($errores) && $modalActivo === 'agregar' && isset($datosForm['nombre'])) ? htmlspecialchars($datosForm['nombre']) : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"><?php echo (!empty($errores) && $modalActivo === 'agregar' && isset($datosForm['descripcion'])) ? htmlspecialchars($datosForm['descripcion']) : ''; ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Imagen</label>
                            <input type="file" name="imagen" class="form-control" accept=".jpg,.png">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Categoría</label>
                            <select name="categoria" class="form-select">
                                <option value="Textil" <?php echo (!empty($errores) && $modalActivo === 'agregar' && isset($datosForm['categoria']) && $datosForm['categoria'] === 'Textil') ? 'selected' : ''; ?>>Textil</option>
                                <option value="Promocional" <?php echo (!empty($errores) && $modalActivo === 'agregar' && isset($datosForm['categoria']) && $datosForm['categoria'] === 'Promocional') ? 'selected' : ''; ?>>Promocional</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Precio</label>
                            <input type="text" name="precio" class="form-control"
                                value="<?php echo (!empty($errores) && $modalActivo === 'agregar' && isset($datosForm['precio'])) ? htmlspecialchars($datosForm['precio']) : ''; ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Existencias</label>
                            <input type="text" name="existencias" class="form-control"
                                value="<?php echo (!empty($errores) && $modalActivo === 'agregar' && isset($datosForm['existencias'])) ? htmlspecialchars($datosForm['existencias']) : ''; ?>">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar Producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales de Edición -->
    <?php foreach ($xml->producto as $producto):
        $productoId = str_replace('PROD', '', $producto['codigo']);
    ?>
        <div class="modal fade" id="editarProductoModal<?php echo $productoId; ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <?php if (!empty($errores) && !empty($datosForm) && $modalActivo === 'editar_' . $productoId): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errores as $error): ?>
                                        <li><?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <form action="manejoProductos.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="accion" value="editar">
                            <input type="hidden" name="codigo" value="<?php echo $producto['codigo']; ?>">

                            <div class="mb-3">
                                <label class="form-label">Código</label>
                                <input type="text" class="form-control" value="<?php echo $producto['codigo']; ?>" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control"
                                    value="<?php echo $producto->nombre; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" class="form-control"
                                    rows="3"><?php echo $producto->descripcion; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Imagen Actual</label>
                                <img src="../<?php echo $producto->imagen; ?>" class="img-thumbnail d-block" style="max-width: 200px">
                                <label class="form-label mt-2">Cambiar Imagen</label>
                                <input type="file" name="imagen" class="form-control" accept=".jpg,.png">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Categoría</label>
                                <select name="categoria" class="form-select">
                                    <option value="Textil" <?php echo ($producto->categoria == 'Textil') ? 'selected' : ''; ?>>
                                        Textil
                                    </option>
                                    <option value="Promocional" <?php echo ($producto->categoria == 'Promocional') ? 'selected' : ''; ?>>
                                        Promocional
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Precio</label>
                                <input type="text" name="precio" class="form-control"
                                    value="<?php echo $producto->precio; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Existencias</label>
                                <input type="text" name="existencias" class="form-control"
                                    value="<?php echo $producto->existencias; ?>">
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <?php if ($modalActivo): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = new bootstrap.Modal(document.getElementById('<?php echo $modalActivo === "agregar" ? "agregarProductoModal" : "editarProductoModal" . str_replace("editar_", "", $modalActivo); ?>'));
                modal.show();
            });
        </script>
    <?php endif; ?>
</body>

</html>