<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TextilExport - Productos Textiles y Promocionales</title>
    <?php include __DIR__ . "/../header.php" ?>
</head>

<body class="home-page">
    <?php
    session_start();
    $tipoUsuario = $_SESSION['tipo_usuario'] ?? null;
    ?>
    <!-- Barra de Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">TextilExport</a>
            <div class="ms-auto d-flex gap-2 align-items-center">
                <?php if ($tipoUsuario): ?>
                    <span class="text-white fw-bold">
                        <?php echo htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']); ?>
                    </span>
                    <?php if ($tipoUsuario === 1 || $tipoUsuario === 2): ?>
                        <a href="admin/login.php" class="btn btn-outline-light">Administrador</a>
                    <?php endif; ?>
                    <form action="/Usuario/logout" method="post" class="d-inline">
                        <button type="submit" class="btn btn-outline-light">Cerrar sesión</button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#loginModal">Iniciar Sesión</button>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Modal de Login -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/Usuario/login" method="post" autocomplete="off">
                    <div class="modal-header">
                        <h5 class="modal-title">Iniciar Sesión</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="username" class="form-label">Usuario</label>
                            <input type="text" class="form-control" name="username" id="username" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                        <?php if (!empty($imagenesCarrusel)): ?>
                            <?php foreach ($imagenesCarrusel as $index => $imagen): ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <img src="<?= htmlspecialchars($imagen['Ruta']) ?>" class="d-block w-100" alt="Imagen del carrusel">
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="carousel-item active">
                                <img src="images/default.png" class="d-block w-100" alt="Imagen por defecto">
                            </div>
                        <?php endif; ?>
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
                            <a href="Productos" class="btn btn-success btn-lg">Ver Productos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Pie de Página -->
    <?php include __DIR__ . "/../footer.php" ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(loginForm);
                    fetch('/Usuario/login', {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                document.getElementById('loginError').textContent = data.error || 'Error de autenticación';
                                document.getElementById('loginError').classList.remove('d-none');
                            }
                        });
                });
            }
        });
    </script>
</body>

</html>