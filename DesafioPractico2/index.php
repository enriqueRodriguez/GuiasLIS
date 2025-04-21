<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TextilExport - Productos Textiles y Promocionales</title>
    <!-- CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS Personalizado -->
    <link href="assets/css/styles.css" rel="stylesheet">
</head>

<body class="home-page">
    <!-- Barra de Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">TextilExport</a>
            <div class="ms-auto">
                <a href="admin/login.php" class="btn btn-outline-light">Administrador</a>
            </div>
        </div>
    </nav>

    <main>
        <!-- Sección Principal -->
        <div class="hero-section text-center">
            <div class="container">
                <h1 class="display-4">Bienvenido a TextilExport</h1>
                <p class="lead">Tu mejor opción en productos textiles y artículos promocionales</p>
            </div>
        </div>

        <!-- Opciones de Acceso -->
        <div class="container my-5 position-relative">
            <!-- Fondo con Carrusel -->
            <div class="carousel-background">
                <div id="backgroundCarousel" class="carousel slide" data-bs-ride="carousel">
                    <!-- Indicadores -->
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#backgroundCarousel" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#backgroundCarousel" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#backgroundCarousel" data-bs-slide-to="2"></button>
                        <button type="button" data-bs-target="#backgroundCarousel" data-bs-slide-to="3"></button>
                    </div>

                    <!-- Diapositivas -->
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="uploads/carrousel/textile1.jpg" class="d-block w-100" alt="Textiles">
                        </div>
                        <div class="carousel-item">
                            <img src="uploads/carrousel/promo1.jpg" class="d-block w-100" alt="Promocionales">
                        </div>
                        <div class="carousel-item">
                            <img src="uploads/carrousel/textile2.jpg" class="d-block w-100" alt="Productos">
                        </div>
                        <div class="carousel-item">
                            <img src="uploads/carrousel/textile3.jpg" class="d-block w-100" alt="Productos">
                        </div>
                    </div>

                    <!-- Controles -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#backgroundCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#backgroundCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </div>

            <!-- Tarjeta Superpuesta -->
            <div class="row justify-content-center position-relative">
                <div class="col-12">
                    <div class="card text-center access-card bg-white bg-opacity-95">
                        <div class="card-body">
                            <h3 class="card-title">Catálogo de Productos</h3>
                            <p class="card-text">Explora nuestra amplia gama de productos.</p>
                            <a href="public/" class="btn btn-success btn-lg">Ver Productos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Pie de Página -->
    <footer class="py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 TextilExport. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- JavaScript de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript Personalizado -->
    <script src="assets/js/main.js"></script>
</body>

</html>