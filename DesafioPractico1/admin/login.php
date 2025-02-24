<?php
session_start();

// Redirige al panel de administración si ya hay una sesión activa
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: productos.php');
    exit;
}

$error = '';

// Maneja el inicio de sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $xml = simplexml_load_file('../data/usuarios.xml');
    $usuario = $xml->usuario;

    if (
        $_POST['username'] === (string)$usuario->username &&
        $_POST['password'] === (string)$usuario->password
    ) {
        $_SESSION['loggedin'] = true;
        header('Location: productos.php');
        exit;
    }

    $error = 'Usuario o contraseña incorrectos';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TextilExport - Inicio de Sesión</title>
    <!-- CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS Personalizado -->
    <link href="../assets/css/styles.css" rel="stylesheet">
</head>

<body class="bg-login">
    <!-- Barra de Navegación -->
    <nav class="navbar navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">TextilExport</a>
        </div>
    </nav>

    <!-- Contenedor Principal -->
    <div class="container mt-5 login-container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <!-- Tarjeta de Inicio de Sesión -->
                <div class="card login-card bg-white bg-opacity-95">
                    <div class="card-body p-4">
                        <h3 class="card-title text-center mb-4">Iniciar Sesión</h3>

                        <!-- Mensaje de Error -->
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Formulario de Inicio de Sesión -->
                        <form method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    required autocomplete="username">
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    required autocomplete="current-password">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                        </form>

                        <!-- Enlace de Regreso -->
                        <div class="text-center mt-4">
                            <a href="../index.php" class="text-decoration-none link-dark">Volver al inicio</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts de JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>