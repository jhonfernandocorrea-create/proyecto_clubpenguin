<?php
require_once 'conexion.php';

// Fetch products from database
$stmt = $pdo->query("SELECT p.*, c.nombre as categoria_nombre 
                    FROM productos p 
                    LEFT JOIN categorias c ON p.categoria_id = c.id");
$productos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Penguin - Tienda de Mascotas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>🐧 Club Penguin Pet Shop 🐾</h1>
        <p>¡Todo para tus perros y gatos!</p>
    </header>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="inventario.php">Inventario</a>
    </nav>

    <div class="container">
        <div class="dashboard-grid">
            <!-- Product List -->
            <section>
                <h2>Nuestros Productos</h2>
                <div class="products-container" id="products-list">
                    <?php foreach ($productos as $p): ?>
                        <div class="product-card" data-id="<?= $p['id'] ?>" data-nombre="<?= htmlspecialchars($p['nombre']) ?>" data-precio="<?= $p['precio'] ?>" data-stock="<?= $p['stock'] ?>">
                            <h3><?= htmlspecialchars($p['nombre']) ?></h3>
                            <p><?= htmlspecialchars($p['descripcion']) ?></p>
                            <span class="badge badge-category"><?= htmlspecialchars($p['categoria_nombre']) ?></span>
                            <div class="price">$<?= number_format($p['precio'], 2) ?></div>
                            <div class="stock-info">Stock: <?= $p['stock'] ?> uds.</div>
                            <br>
                            <?php if ($p['stock'] > 0): ?>
                                <button class="btn btn-primary add-to-cart">Agregar al Carrito</button>
                            <?php else: ?>
                                <button class="btn btn-danger" disabled>Sin Stock</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Shopping Cart -->
            <aside class="cart-container">
                <h2>🛒 Tu Carrito</h2>
                <div id="cart-items">
                    <!-- Cart items will appear here -->
                    <p>El carrito está vacío.</p>
                </div>
                <div class="cart-total">
                    Total: $<span id="cart-total-value">0.00</span>
                </div>
                <button id="checkout-btn" class="btn btn-success" style="width:100%;" disabled>Finalizar Compra</button>
                <button id="clear-cart-btn" class="btn btn-danger" style="width:100%; margin-top: 10px;">Vaciar Carrito</button>
            </aside>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
