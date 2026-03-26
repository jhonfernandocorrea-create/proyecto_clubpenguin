<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $categoria_id = $_POST['categoria_id'];

    try {
        if ($id) {
            // Update existing product
            $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, categoria_id = ? WHERE id = ?");
            $stmt->execute([$nombre, $descripcion, $precio, $stock, $categoria_id, $id]);
            $msg = "updated";
        } else {
            // Create new product
            $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $descripcion, $precio, $stock, $categoria_id]);
            $msg = "created";
        }
        
        header("Location: inventario.php?msg=$msg");
        exit;
    } catch (Exception $e) {
        die("Error al procesar el inventario: " . $e->getMessage());
    }
} else {
    header("Location: inventario.php");
    exit;
}
?>
