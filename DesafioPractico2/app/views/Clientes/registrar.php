<?php
session_start();
if (!empty($_SESSION['tipo_usuario'])) {
    header('Location: /');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro de Cliente</title>
    <?php include __DIR__ . "/../header.php" ?>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">TextilExport</a>
            <a href="/" class="btn btn-outline-light">Regresar</a>
        </div>
    </nav>
    <main class="container my-5" style="max-width: 500px;">
        <h1 class="mb-4 text-center">Registro de Cliente</h1>

        <?php if (!empty($_SESSION['mensaje_error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['mensaje_error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
            <?php unset($_SESSION['mensaje_error']); ?>
        <?php endif; ?>

        <form action="/Clientes/agregarCliente" method="post" autocomplete="off">
            <div class="mb-3">
                <label for="username" class="form-label">Usuario</label>
                <input type="text" name="Username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="Nombre" id="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" name="Apellido" id="apellido" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="Password" id="password" class="form-control" required>
            </div>
            <!-- TipoUsuario oculto para Cliente -->
            <input type="hidden" name="TipoUsuario" value="3">
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Registrarse</button>
            </div>
        </form>
        <div class="mt-3 text-center">
            <a href="/" class="btn btn-link">Volver al inicio</a>
        </div>
    </main>
    <?php include __DIR__ . "/../footer.php" ?>
</body>

</html>