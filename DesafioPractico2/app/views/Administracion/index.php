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
    <title>Administración de Productos</title>
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
                    <a href="/Venta/" class="btn btn-outline-light">Ventas</a>
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
        <h1 class="mb-4">Gestión de Productos</h1>

        <?php if (!empty($_SESSION['mensaje_exito'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['mensaje_exito']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
            <?php unset($_SESSION['mensaje_exito']); ?>
        <?php endif; ?>

        <!-- Botón agregar producto -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregar">Agregar Producto</button>
        <?php if ($tipoUsuario == 1): ?>
            <button class="btn btn-secondary mb-3 ms-2" data-bs-toggle="modal" data-bs-target="#modalCategorias">Administrar Categorías</button>
        <?php endif; ?>

        <!-- Tabla de productos -->
        <div class="table-responsive">
            <table class="table table-bordered">
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
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?= htmlspecialchars($producto['IdProducto']) ?></td>
                            <td>
                                <?php if (!empty($producto['Ruta'])): ?>
                                    <img src="<?= htmlspecialchars($producto['Ruta']) ?>" style="max-width:50px;">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($producto['Nombre']) ?></td>
                            <td><?= htmlspecialchars($producto['Descripcion']) ?></td>
                            <td>$<?= number_format($producto['Precio'], 2) ?></td>
                            <td><?= $producto['Cantidad'] ?></td>
                            <td>
                                <!-- Editar -->
                                <button class="btn btn-warning btn-sm me-1" title="Editar" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $producto['IdProducto'] ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <!-- Eliminar -->
                                <form action="/Administracion/eliminar" method="post" style="display:inline;">
                                    <input type="hidden" name="IdProducto" value="<?= $producto['IdProducto'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Eliminar producto?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Mueve todos los modales de editar aquí, fuera de la tabla -->
        <?php foreach ($productos as $producto): ?>
            <div class="modal fade" id="modalEditar<?= $producto['IdProducto'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form class="modal-content" action="/Administracion/editar" method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Producto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <?php if ($modalActivo === 'editar_' . $producto['IdProducto'] && !empty($errores)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach ($errores as $error): ?>
                                            <li><?= htmlspecialchars($error) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <input type="hidden" name="IdProducto" value="<?= $producto['IdProducto'] ?>">
                            <input type="hidden" name="IdImagen" value="<?= $producto['IdImagen'] ?>">
                            <div class="mb-3">
                                <label>Nombre</label>
                                <input type="text" name="Nombre" class="form-control" value="<?= htmlspecialchars($producto['Nombre']) ?>">
                            </div>
                            <div class="mb-3">
                                <label>Descripción</label>
                                <textarea name="Descripcion" class="form-control"><?= htmlspecialchars($producto['Descripcion']) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Imagen actual</label><br>
                                <?php if (!empty($producto['Ruta'])): ?>
                                    <img src="<?= htmlspecialchars($producto['Ruta']) ?>" style="max-width:100px;">
                                <?php endif; ?>
                                <input type="file" name="Imagen" class="form-control mt-2" accept=".jpg,.png">
                            </div>
                            <div class="mb-3">
                                <label>Categoría</label>
                                <select name="IdCategoria" class="form-select">
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= $cat['IdCategoria'] ?>" <?= $producto['IdCategoria'] == $cat['IdCategoria'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['Descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Precio</label>
                                <input type="text" name="Precio" class="form-control" value="<?= $producto['Precio'] ?>">
                            </div>
                            <div class="mb-3">
                                <label>Existencias</label>
                                <input type="text" name="Cantidad" class="form-control" value="<?= $producto['Cantidad'] ?>">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Modal agregar producto -->
        <div class="modal fade" id="modalAgregar" tabindex="-1">
            <div class="modal-dialog">
                <form class="modal-content" action="/Administracion/agregar" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <?php if ($modalActivo === 'agregar' && !empty($errores)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errores as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label>Nombre</label>
                            <input type="text" name="Nombre" class="form-control" value="<?= htmlspecialchars($datosForm['Nombre'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label>Descripción</label>
                            <textarea name="Descripcion" class="form-control"><?= htmlspecialchars($datosForm['Descripcion'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Imagen</label>
                            <input type="file" name="Imagen" class="form-control" accept=".jpg,.png">
                        </div>
                        <div class="mb-3">
                            <label>Categoría</label>
                            <select name="IdCategoria" class="form-select">
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['IdCategoria'] ?>" <?= (isset($datosForm['IdCategoria']) && $datosForm['IdCategoria'] == $cat['IdCategoria']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['Descripcion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Precio</label>
                            <input type="text" name="Precio" class="form-control" value="<?= htmlspecialchars($datosForm['Precio'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label>Existencias</label>
                            <input type="text" name="Cantidad" class="form-control" value="<?= htmlspecialchars($datosForm['Cantidad'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Producto</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Administrar Categorías -->
        <?php if ($tipoUsuario == 1): ?>
            <div class="modal fade" id="modalCategorias" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Administrar Categorías</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label for="nuevaCategoria" class="form-label">Nueva Categoría</label>
                            <!-- Formulario SOLO para agregar -->
                            <form action="/Administracion/agregarCategoria" method="post" class="input-group mb-3">
                                <input type="text" name="Descripcion" id="nuevaCategoria" class="form-control" placeholder="Nombre de la categoría" required>
                                <button type="submit" class="btn btn-success">Agregar</button>
                            </form>
                            <hr>
                            <h6>Categorías existentes</h6>
                            <ul class="list-group">
                                <?php foreach ($categorias as $cat): ?>
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <!-- Formulario para editar -->
                                            <form action="/Administracion/editarCategoria" method="post" class="d-flex align-items-center" style="gap: 0.5rem; flex:1;">
                                                <input type="hidden" name="IdCategoria" value="<?= $cat['IdCategoria'] ?>">
                                                <input type="text" name="Descripcion" value="<?= htmlspecialchars($cat['Descripcion']) ?>" class="form-control form-control-sm" required style="max-width: 200px;">
                                                <button type="submit" class="btn btn-sm btn-primary ms-2">Guardar</button>
                                            </form>
                                            <!-- Formulario para eliminar -->
                                            <form action="/Administracion/eliminarCategoria" method="post" style="margin-left: 0.5rem;">
                                                <input type="hidden" name="IdCategoria" value="<?= $cat['IdCategoria'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta categoría?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>
    <?php if ($modalActivo): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = new bootstrap.Modal(document.getElementById('<?= $modalActivo === "agregar" ? "modalAgregar" : "modalEditar" . $datosForm['IdProducto'] ?>'));
                modal.show();
            });
        </script>
    <?php endif; ?>

    <!-- Pie de Página -->
    <?php include __DIR__ . "/../footer.php" ?>
</body>

</html>