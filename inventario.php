<?php
require_once 'conexion.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: inventario.php?msg=deleted");
    exit;
}

// Fetch categories for the form
$stmtCat = $pdo->query("SELECT * FROM categorias");
$categorias = $stmtCat->fetchAll();

// Fetch products for the table
$stmtProd = $pdo->query("SELECT p.*, c.nombre as categoria_nombre 
                        FROM productos p 
                        LEFT JOIN categorias c ON p.categoria_id = c.id
                        ORDER BY p.id DESC");
$productos = $stmtProd->fetchAll();

// Handle Edit (fetch data if id is present)
$editProduct = null;
if (isset($_GET['edit'])) {
    $stmtEdit = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $stmtEdit->execute([$_GET['edit']]);
    $editProduct = $stmtEdit->fetch();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - Club Penguin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>📋 Gestión de Inventario</h1>
    </header>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="inventario.php">Inventario</a>
    </nav>

    <div class="container">
        <h2><?= $editProduct ? 'Editar Producto' : 'Agregar Nuevo Producto' ?></h2>
        <form action="procesar_inventario.php" method="POST" class="form-inventory">
            <?php if ($editProduct): ?>
                <input type="hidden" name="id" value="<?= $editProduct['id'] ?>">
            <?php endif; ?>
            
            <div class="dashboard-grid">
                <div>
                    <div class="form-group">
                        <label>Nombre del Producto:</label>
                        <input type="text" name="nombre" value="<?= $editProduct ? htmlspecialchars($editProduct['nombre']) : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción:</label>
                        <textarea name="descripcion" rows="3"><?= $editProduct ? htmlspecialchars($editProduct['descripcion']) : '' ?></textarea>
                    </div>
                </div>
                <div>
                    <div class="form-group">
                        <label>Precio ($):</label>
                        <input type="number" name="precio" step="0.01" value="<?= $editProduct ? $editProduct['precio'] : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Stock Inicial:</label>
                        <input type="number" name="stock" value="<?= $editProduct ? $editProduct['stock'] : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Categoría:</label>
                        <select name="categoria_id" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($categorias as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= ($editProduct && $editProduct['categoria_id'] == $c['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success"><?= $editProduct ? 'Actualizar Producto' : 'Guardar Producto' ?></button>
            <?php if ($editProduct): ?>
                <a href="inventario.php" class="btn btn-danger">Cancelar</a>
            <?php endif; ?>
        </form>

        <hr style="margin: 30px 0;">

        <h2>Productos en Existencia</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                    <td><span class="badge badge-category"><?= htmlspecialchars($p['categoria_nombre']) ?></span></td>
                    <td>$<?= number_format($p['precio'], 2) ?></td>
                    <td><span class="badge badge-stock"><?= $p['stock'] ?></span></td>
                    <td>
                        <a href="inventario.php?edit=<?= $p['id'] ?>" class="btn btn-primary" style="font-size: 0.8rem; text-decoration: none;">Editar</a>
                        <a href="inventario.php?delete=<?= $p['id'] ?>" class="btn btn-danger" style="font-size: 0.8rem; text-decoration: none;" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
