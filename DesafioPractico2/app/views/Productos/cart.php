<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 3) {
    header('Location: /Productos');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <?php include __DIR__ . '/../header.php' ?>
    <style>
    </style>
</head>

<body>
    <!-- Barra de Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">TextilExport</a>
            <div class="ms-auto">
                <a href="/Productos/index" class="btn btn-outline-light">Seguir comprando</a>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <h1>Carrito de Compras</h1>
        <?php if (empty($carrito)): ?>
            <p>No hay productos en el carrito.</p>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
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
                </tbody>
            </table>

            <!-- Formulario de pago -->
            <div class="payment-form">
                <h2>Pago</h2>
                <form action="/Productos/checkout" method="POST">
                    <label for="card-number">Número de Tarjeta</label>
                    <input type="text" id="card-number" name="card_number" placeholder="1234 5678 9012 3456" required>

                    <label for="card-name">Nombre en la Tarjeta</label>
                    <input type="text" id="card-name" name="card_name" placeholder="Nombre" required>

                    <label for="expiry-date">Fecha de Expiración</label>
                    <input type="text" id="expiry-date" name="expiry_date" placeholder="MM/AA" required>

                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" placeholder="123" required>

                    <button type="submit">Pagar $<?= number_format($total, 2) ?></button>
                </form>
            </div>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/../footer.php' ?>
</body>

</html>