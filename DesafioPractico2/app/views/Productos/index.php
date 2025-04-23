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
            <a class="navbar-brand" href="/">TextilExport</a>
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

        <?php
        session_start();
        if (!empty($_SESSION['mensaje_error'])): ?>
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                    <?php
                    echo $_SESSION['mensaje_error'];
                    unset($_SESSION['mensaje_error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Formulario de Búsqueda -->
        <div class="container my-4">
            <form method="get" action="/Productos/index/1" class="mb-4" id="form-filtros">
                <div class="input-group">
                    <input type="text" name="buscar" id="filtro-productos" class="form-control" placeholder="Buscar producto..." value="<?php echo htmlspecialchars($buscar ?? ''); ?>">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <button type="button" class="btn btn-outline-secondary" id="btn-limpiar-busqueda">Limpiar</button>
                </div>
                <select name="categoria" id="filtro-categoria" class="form-select mt-2">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat['IdCategoria']); ?>"
                            <?php if (($categoriaSeleccionada ?? '') == $cat['IdCategoria']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($cat['Descripcion']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <!-- Sección de Productos -->
        <div class="container my-5">
            <?php if (empty($productos)): ?>
                <div class="alert alert-warning text-center">No hay productos para mostrar.</div>
            <?php endif; ?>
            <div class="row g-4">
                <?php foreach ($productos as $producto): ?>
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
                                <!-- Botón Agregar al Carrito -->
                                <form method="post" action="/Productos/addToCart" class="d-flex align-items-center gap-2 mt-2">
                                    <input type="number"
                                        name="cantidad"
                                        min="1"
                                        max="<?php echo (int)($producto['Cantidad'] ?? 0); ?>"
                                        value="1"
                                        class="form-control"
                                        style="width: 80px;"
                                        <?php echo ((int)($producto['Cantidad'] ?? 0) <= 0) ? 'disabled' : ''; ?>>
                                    <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($producto['IdProducto'] ?? ''); ?>">
                                    <button type="submit"
                                        class="btn btn-success w-100"
                                        <?php echo ((int)($producto['Cantidad'] ?? 0) <= 0) ? 'disabled' : ''; ?>>
                                        Agregar al carrito
                                    </button>
                                </form>
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
            <?php
            // Construir query string para mantener filtros
            $query = [];
            if (!empty($buscar)) $query['buscar'] = $buscar;
            if (!empty($categoriaSeleccionada)) $query['categoria'] = $categoriaSeleccionada;
            $queryString = http_build_query($query);
            $queryString = $queryString ? '&' . $queryString : '';
            ?>
            <nav aria-label="Navegación de productos" class="mt-4">
                <ul class="pagination justify-content-center">
                    <!-- Botón Anterior -->
                    <li class="page-item <?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>">
                        <?php if ($paginaActual <= 1): ?>
                            <span class="page-link">Anterior</span>
                        <?php else: ?>
                            <a class="page-link" href="/Productos/index/<?php echo $paginaActual - 1; ?>?<?php echo ltrim($queryString, '&'); ?>">Anterior</a>
                        <?php endif; ?>
                    </li>
                    <!-- Números de Página -->
                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <li class="page-item <?php echo ($paginaActual == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="/Productos/index/<?php echo $i; ?>?<?php echo ltrim($queryString, '&'); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <!-- Botón Siguiente -->
                    <li class="page-item <?php echo ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>">
                        <?php if ($paginaActual >= $totalPaginas): ?>
                            <span class="page-link">Siguiente</span>
                        <?php else: ?>
                            <a class="page-link" href="/Productos/index/<?php echo $paginaActual + 1; ?>?<?php echo ltrim($queryString, '&'); ?>">Siguiente</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Ventanas Modales de Productos -->
        <?php foreach ($productos as $producto): ?>
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
                                        <img src="<?php echo htmlspecialchars($producto['Ruta'] ?? ''); ?>"
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
                                        <!-- Botón Agregar al Carrito en el Modal -->
                                        <form method="post" action="/Productos/addToCart" class="d-flex align-items-center gap-2 mt-2">
                                            <input type="number"
                                                name="cantidad"
                                                min="1"
                                                max="<?php echo (int)($producto['Cantidad'] ?? 0); ?>"
                                                value="1"
                                                class="form-control"
                                                style="width: 80px;"
                                                <?php echo ((int)($producto['Cantidad'] ?? 0) <= 0) ? 'disabled' : ''; ?>>
                                            <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($producto['IdProducto'] ?? ''); ?>">
                                            <button type="submit"
                                                class="btn btn-success w-100"
                                                <?php echo ((int)($producto['Cantidad'] ?? 0) <= 0) ? 'disabled' : ''; ?>>
                                                Agregar al carrito
                                            </button>
                                        </form>
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
    <script src="/js/searchProducts.js"></script>
</body>

</html>