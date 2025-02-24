<?php
// Cargar y leer el archivo XML
$xml = simplexml_load_file('../data/productos.xml');

// Configuración de paginación
$productosPorPagina = 6;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

// Convertir SimpleXML a array para mejor manejo
$productosArray = [];
foreach ($xml->producto as $producto) {
    $productosArray[] = $producto;
}

$totalProductos = count($productosArray);
$totalPaginas = ceil($totalProductos / $productosPorPagina);

// Asegurar que la página actual esté dentro de los límites
$paginaActual = max(1, min($paginaActual, $totalPaginas));

// Calcular el índice de inicio para la página actual
$inicio = ($paginaActual - 1) * $productosPorPagina;

// Obtener los productos para la página actual
$productosEnPagina = array_slice($productosArray, $inicio, $productosPorPagina);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TextilExport - Catálogo de Productos</title>
    <!-- CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS Personalizado -->
    <link href="../assets/css/styles.css" rel="stylesheet">
</head>

<body class="catalog-page">
    <!-- Barra de Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">TextilExport</a>
            <div class="ms-auto">
                <a href="../" class="btn btn-outline-light">Regresar</a>
            </div>
        </div>
    </nav>

    <main>
        <!-- Sección Principal -->
        <div class="hero-section text-center">
            <div class="container">
                <h1 class="display-4">Nuestros Productos</h1>
                <p class="lead">Explora nuestra colección de productos textiles y promocionales</p>
            </div>
        </div>

        <!-- Sección de Productos -->
        <div class="container my-5">
            <div class="row g-4">
                <?php foreach ($productosEnPagina as $producto): ?>
                    <!-- Tarjeta de Producto -->
                    <div class="col-md-4">
                        <div class="card h-100 product-card">
                            <div class="card-image-wrapper">
                                <img src="../<?php echo $producto->imagen; ?>"
                                    class="card-img-top product-image"
                                    alt="<?php echo htmlspecialchars($producto->nombre); ?>">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($producto->nombre); ?></h5>
                                <p class="card-text text-muted"><?php echo htmlspecialchars($producto->descripcion); ?></p>
                                <div class="product-meta">
                                    <span class="category-badge badge bg-primary">
                                        <?php echo htmlspecialchars($producto->categoria); ?>
                                    </span>
                                    <span class="price-tag">$<?php echo number_format((float)$producto->precio, 2); ?></span>
                                </div>
                                <div class="product-availability mt-2">
                                    <span class="badge bg-<?php echo ((int)$producto->existencias > 0) ? 'success' : 'danger'; ?>">
                                        <?php echo ((int)$producto->existencias > 0) ? 'Disponible' : 'Agotado'; ?>
                                    </span>
                                </div>
                                <button class="btn btn-outline-primary mt-3 w-100"
                                    data-bs-toggle="modal"
                                    data-bs-target="#productoModal<?php echo str_replace('PROD', '', $producto['codigo']); ?>">
                                    Ver Detalles
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Navegación de Paginación -->
            <nav aria-label="Navegación de productos" class="mt-4">
                <ul class="pagination justify-content-center">
                    <!-- Botón Anterior -->
                    <li class="page-item <?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $paginaActual - 1; ?>" <?php echo ($paginaActual <= 1) ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>Anterior</a>
                    </li>

                    <!-- Números de Página -->
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
        </div>

        <!-- Ventanas Modales de Productos -->
        <?php foreach ($productosEnPagina as $producto): ?>
            <div class="modal fade product-modal"
                id="productoModal<?php echo str_replace('PROD', '', $producto['codigo']); ?>"
                tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?php echo htmlspecialchars($producto->nombre); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="product-image-container">
                                        <img src="../<?php echo $producto->imagen; ?>"
                                            class="img-fluid rounded"
                                            alt="<?php echo htmlspecialchars($producto->nombre); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="product-details">
                                        <h4>Detalles del Producto</h4>
                                        <div class="detail-item">
                                            <strong>Código:</strong>
                                            <span><?php echo htmlspecialchars($producto['codigo']); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <strong>Categoría:</strong>
                                            <span><?php echo htmlspecialchars($producto->categoria); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <strong>Precio:</strong>
                                            <span>$<?php echo number_format((float)$producto->precio, 2); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <strong>Existencias:</strong>
                                            <span><?php echo $producto->existencias; ?> unidades</span>
                                        </div>
                                        <div class="alert alert-<?php echo ((int)$producto->existencias > 0) ? 'success' : 'danger'; ?> mt-3">
                                            <?php echo ((int)$producto->existencias > 0) ? 'Producto Disponible' : 'Producto Agotado'; ?>
                                        </div>
                                        <div class="product-description mt-4">
                                            <h5>Descripción:</h5>
                                            <p><?php echo htmlspecialchars($producto->descripcion); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </main>

    <!-- Pie de Página -->
    <footer class="py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 TextilExport. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Scripts de JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>

</html>