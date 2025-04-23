<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
</head>

<body>
    <h1>Carrito de Compras</h1>
    <?php if (empty($carrito)): ?>
        <p>No hay productos en el carrito.</p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
            <?php $total = 0; ?>
            <?php foreach ($carrito as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                    <td><?= $item['cantidad'] ?></td>
                    <td>$<?= number_format($item['precio'], 2) ?></td>
                    <td>$<?= number_format($item['precio'] * $item['cantidad'], 2) ?></td>
                </tr>
                <?php $total += $item['precio'] * $item['cantidad']; ?>
            <?php endforeach; ?>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong>$<?= number_format($total, 2) ?></strong></td>
            </tr>
        </table>
    <?php endif; ?>
    <a href="/Productos/index">Seguir comprando</a>
</body>

</html>