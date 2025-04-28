<?php
session_start();
$tipoUsuario = $_SESSION['tipo_usuario'] ?? null;
if ($tipoUsuario !== 1 && $tipoUsuario !== 2) {
    header('Location: /');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ventas Realizadas</title>
    <?php include __DIR__ . "/../header.php" ?>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">TextilExport</a>
            <div class="ms-auto d-flex gap-2 align-items-center">
                <?php if ($tipoUsuario): ?>
                    <span class="text-white fw-bold">
                        <?= htmlspecialchars(($_SESSION['nombre'] ?? '') . ' ' . ($_SESSION['apellido'] ?? '')) ?>
                    </span>
                    <a href="/Administracion/" class="btn btn-outline-light">Productos</a>
                    <?php if ($tipoUsuario == 1): ?>
                        <a href="/Usuario/" class="btn btn-outline-light">Usuarios</a>
                        <a href="/Clientes/" class="btn btn-outline-light">Clientes</a>
                    <?php endif; ?>
                    <a href="/Usuario/logout" class="btn btn-outline-light">Cerrar Sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main class="container my-5">
        <h1 class="mb-4">Ventas Realizadas</h1>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Venta</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Comprobante</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventas as $venta): ?>
                        <tr>
                            <td><?= htmlspecialchars($venta['IdVenta']) ?></td>
                            <td>
                                <?= htmlspecialchars(($venta['Nombre'] ?? '') . ' ' . ($venta['Apellido'] ?? '')) ?>
                            </td>
                            <td>$<?= number_format($venta['Total'], 2) ?></td>
                            <td>
                                <?php if (!empty($venta['RutaComprobante'])): ?>
                                    <a href="<?= htmlspecialchars($venta['RutaComprobante']) ?>" class="btn btn-success btn-sm" download>
                                        Descargar Comprobante
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">No disponible</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <?php include __DIR__ . "/../footer.php" ?>
</body>

</html>