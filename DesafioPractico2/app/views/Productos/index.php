<?php
// $productos es recibido desde el controller

// Configuración de paginación
$productosPorPagina = 6;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

$totalProductos = count($productos);
$totalPaginas = max(1, ceil($totalProductos / $productosPorPagina));

// Asegurar que la página actual esté dentro de los límites
$paginaActual = max(1, min($paginaActual, $totalPaginas));

// Calcular el índice de inicio para la página actual
$inicio = ($paginaActual - 1) * $productosPorPagina;

// Obtener los productos para la página actual
$productosEnPagina = array_slice($productos, $inicio, $productosPorPagina);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TextilExport - Catálogo de Productos</title>
    <?php include __DIR__ . "/../header.php" ?>
</head>

<body class="catalog-page">
    <!-- Barra de Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">TextilExport</a>
            <div class="ms-auto">
                <a href="/" class="btn btn-outline-light">Regresar</a>
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
                    <div class="col-md-4">
                        <div class="card h-100 product-card">
                            <div class="card-image-wrapper">
                                <img src="<?php echo htmlspecialchars($producto['Ruta'] ?? ''); ?>"
                                    class="card-img-top product-image"
                                    alt="<?php echo htmlspecialchars($producto['Nombre'] ?? ''); ?>">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($producto['Nombre'] ?? ''); ?></h5>
                                <div class="product-meta">
                                    <span class="category-badge badge bg-primary">
                                        <?php echo htmlspecialchars($producto['Descripcion'] ?? ''); ?>
                                    </span>
                                    <span class="price-tag">$<?php echo number_format((float)($producto['Precio'] ?? 0), 2); ?></span>
                                </div>
                                <div class="product-availability mt-2">
                                    <span class="badge bg-<?php echo ((int)($producto['Cantidad'] ?? 0) > 0) ? 'success' : 'danger'; ?>">
                                        <?php echo ((int)($producto['Cantidad'] ?? 0) > 0) ? 'Disponible' : 'Agotado'; ?>
                                    </span>
                                </div>
                                <!-- Botón para abrir el modal -->
                                <button class="btn btn-outline-primary mt-3 w-100"
                                    data-bs-toggle="modal"
                                    data-bs-target="#productoModal<?php echo htmlspecialchars($producto['IdProducto'] ?? ''); ?>">
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
                    <li class="page-item <?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $paginaActual - 1; ?>" <?php echo ($paginaActual <= 1) ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>Anterior</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <li class="page-item <?php echo ($paginaActual == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $paginaActual + 1; ?>" <?php echo ($paginaActual >= $totalPaginas) ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>Siguiente</a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Ventanas Modales de Productos -->
        <?php foreach ($productosEnPagina as $producto): ?>
            <div class="modal fade product-modal"
                id="productoModal<?php echo htmlspecialchars($producto['IdProducto'] ?? ''); ?>"
                tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?php echo htmlspecialchars($producto['Nombre'] ?? ''); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="product-image-container">
                                        <img src="../<?php echo htmlspecialchars($producto['Ruta'] ?? ''); ?>"
                                            class="img-fluid rounded"
                                            alt="<?php echo htmlspecialchars($producto['Nombre'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="product-details">
                                        <h4>Detalles del Producto</h4>
                                        <div class="detail-item">
                                            <strong>Código:</strong>
                                            <span><?php echo htmlspecialchars($producto['IdProducto'] ?? ''); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <strong>Categoría:</strong>
                                            <span><?php echo htmlspecialchars($producto['Descripcion'] ?? ''); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <strong>Precio:</strong>
                                            <span>$<?php echo number_format((float)($producto['Precio'] ?? 0), 2); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <strong>Existencias:</strong>
                                            <span><?php echo $producto['Cantidad'] ?? 0; ?> unidades</span>
                                        </div>
                                        <div class="alert alert-<?php echo ((int)($producto['Cantidad'] ?? 0) > 0) ? 'success' : 'danger'; ?> mt-3">
                                            <?php echo ((int)($producto['Cantidad'] ?? 0) > 0) ? 'Producto Disponible' : 'Producto Agotado'; ?>
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
    <?php include __DIR__ . "/../footer.php" ?>
</body>

</html>