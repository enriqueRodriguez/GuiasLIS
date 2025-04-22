<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Categorías</title>
</head>

<body>
    <h1>Listado de Categorías</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?= htmlspecialchars($categoria['IdCategoria']) ?></td>
                        <td><?= htmlspecialchars($categoria['Descripcion']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">No hay categorías disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>