<?php
session_start();
$tipoUsuario = $_SESSION['tipo_usuario'] ?? null;
if ($tipoUsuario !== 1) {
    header('Location: /');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administración de Usuarios</title>
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
                    <a href="/Administracion/index" class="btn btn-outline-light">Productos</a>
                    <a href="/Venta/" class="btn btn-outline-light">Ventas</a>
                    <?php if ($tipoUsuario == 1): ?>
                        <a href="/Clientes/" class="btn btn-outline-light">Clientes</a>
                    <?php endif; ?>
                    <a href="/Usuario/logout" class="btn btn-outline-light">Cerrar Sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main class="container my-5">
        <h1 class="mb-4">Administración de Usuarios</h1>

        <?php if (!empty($_SESSION['mensaje_error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['mensaje_error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
            <?php unset($_SESSION['mensaje_error']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['mensaje_exito'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['mensaje_exito']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
            <?php unset($_SESSION['mensaje_exito']); ?>
        <?php endif; ?>

        <!-- Botón agregar usuario -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarUsuario">Agregar Usuario</button>

        <!-- Tabla de usuarios -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <?php if (in_array($usuario['TipoUsuario'], [1, 2])): // Solo Administrador y Empleado 
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($usuario['Username']) ?></td>
                                <td><?= htmlspecialchars($usuario['Nombre']) ?></td>
                                <td><?= htmlspecialchars($usuario['Apellido']) ?></td>
                                <td>
                                    <?= $usuario['TipoUsuario'] == 1 ? 'Administrador' : 'Empleado' ?>
                                </td>
                                <td class="text-center">
                                    <!-- Editar -->
                                    <button class="btn btn-warning btn-sm me-1" title="Editar" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario<?= $usuario['IdUsuario'] ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <!-- Eliminar -->
                                    <form action="/Usuario/eliminar" method="post" style="display:inline;">
                                        <input type="hidden" name="IdUsuario" value="<?= $usuario['IdUsuario'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Eliminar usuario?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Modales editar usuario -->
        <?php foreach ($usuarios as $usuario): ?>
            <?php if (in_array($usuario['TipoUsuario'], [1, 2])): ?>
                <div class="modal fade" id="modalEditarUsuario<?= $usuario['IdUsuario'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <form class="modal-content" action="/Usuario/editar" method="post">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Usuario</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="IdUsuario" value="<?= $usuario['IdUsuario'] ?>">
                                <div class="mb-3">
                                    <label>Usuario</label>
                                    <input type="text" name="Username" class="form-control" value="<?= htmlspecialchars($usuario['Username']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label>Nombre</label>
                                    <input type="text" name="Nombre" class="form-control" value="<?= htmlspecialchars($usuario['Nombre']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label>Apellido</label>
                                    <input type="text" name="Apellido" class="form-control" value="<?= htmlspecialchars($usuario['Apellido']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label>Tipo de Usuario</label>
                                    <select name="TipoUsuario" class="form-select" required>
                                        <option value="1" <?= $usuario['TipoUsuario'] == 1 ? 'selected' : '' ?>>Administrador</option>
                                        <option value="2" <?= $usuario['TipoUsuario'] == 2 ? 'selected' : '' ?>>Empleado</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Nueva Contraseña (opcional)</label>
                                    <input type="password" name="Password" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- Modal agregar usuario -->
        <div class="modal fade" id="modalAgregarUsuario" tabindex="-1">
            <div class="modal-dialog">
                <form class="modal-content" action="/Usuario/agregar" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Usuario</label>
                            <input type="text" name="Username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Nombre</label>
                            <input type="text" name="Nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Apellido</label>
                            <input type="text" name="Apellido" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Contraseña</label>
                            <input type="password" name="Password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Tipo de Usuario</label>
                            <select name="TipoUsuario" class="form-select" required>
                                <option value="1">Administrador</option>
                                <option value="2">Empleado</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <?php include __DIR__ . "/../footer.php" ?>
</body>

</html>