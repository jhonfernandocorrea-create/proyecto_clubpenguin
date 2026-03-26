<?php
header('Content-Type: application/json');
require_once 'conexion.php';

// Get the raw POST data
$jsonData = file_get_contents('php://input');
$cart = json_decode($jsonData, true);

if (empty($cart)) {
    echo json_encode(['success' => false, 'message' => 'El carrito está vacío.']);
    exit;
}

try {
    // Start transaction
    $pdo->beginTransaction();

    // Calculate total
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }

    // Insert into 'ventas' table
    $stmt = $pdo->prepare("INSERT INTO ventas (total) VALUES (?)");
    $stmt->execute([$total]);
    $venta_id = $pdo->lastInsertId();

    // Prepare statements for details and stock update
    $stmtDetail = $pdo->prepare("INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
    $stmtStock = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");

    foreach ($cart as $item) {
        // Verify stock before processing
        $stmtCheck = $pdo->prepare("SELECT stock FROM productos WHERE id = ?");
        $stmtCheck->execute([$item['id']]);
        $currentStock = $stmtCheck->fetchColumn();

        if ($currentStock < $item['cantidad']) {
            throw new Exception("Stock insuficiente para el producto: " . $item['nombre']);
        }

        // Insert sale detail
        $stmtDetail->execute([$venta_id, $item['id'], $item['cantidad'], $item['precio']]);

        // Update stock
        $stmtStock->execute([$item['cantidad'], $item['id']]);
    }

    // Commit transaction
    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Venta procesada con éxito.', 'id' => $venta_id]);

} catch (Exception $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
