<?php
require_once '../config/db.php';

if (isset($_POST['accion']) && $_POST['accion'] === 'crear') {

    $stmt = $pdo->prepare("INSERT INTO productos (nombre, categoria, precio, stock) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['nombre'], $_POST['categoria'], $_POST['precio'], $_POST['stock']]);
    header("Location: productos.php");
    exit;
}

if (isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, categoria = ?, precio = ?, stock = ? WHERE id_producto = ?");
    $stmt->execute([$_POST['nombre'], $_POST['categoria'], $_POST['precio'], $_POST['stock'], $_POST['id_producto']]);
    header("Location: productos.php");
    exit;
}

if (isset($_GET['eliminar'])) {
    $stmt = $pdo->prepare("DELETE FROM productos WHERE id_producto = ?");
    $stmt->execute([$_GET['eliminar']]);
    header("Location: productos.php");
    exit;
}

$productos = $pdo->query("SELECT * FROM productos")->fetchAll();
$prodEditar = null;

if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id_producto = ?");
    $stmt->execute([$_GET['editar']]);
    $prodEditar = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
</head>
<body>
    <a href="../index.php">inicio</a>
    <h2>productos</h2>

    <form method="POST">
        <input type="hidden" name="accion" value="<?= $prodEditar ? 'editar' : 'crear' ?>">
        <?php if ($prodEditar): ?>
            <input type="hidden" name="id_producto" value="<?= $prodEditar['id_producto'] ?>">
        <?php endif; ?>

        <label>Nombre</label>
        <input type="text" name="nombre" value="<?= $prodEditar['nombre'] ?? '' ?>" required>
        
        <label>Categoría</label>
        <input type="text" name="categoria" value="<?= $prodEditar['categoria'] ?? '' ?>" required>
        
        <label>Precio</label>
        <input type="number" step="0.01" name="precio" value="<?= $prodEditar['precio'] ?? '' ?>" required>

        <label>Stock</label>
        <input type="number" name="stock" value="<?= $prodEditar['stock'] ?? '' ?>" required>

        <button type="submit"><?= $prodEditar ? 'Actualizar' : 'Guardar' ?></button>
        <?php if ($prodEditar): ?><a href="productos.php">cancela</a><?php endif; ?>
    </form>

    <hr>

    <table border="1" cellpadding="8">
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
                <td><?= $p['id_producto'] ?></td>
                <td><?= htmlspecialchars($p['nombre']) ?></td>
                <td><?= htmlspecialchars($p['categoria']) ?></td>
                <td>$<?= number_format($p['precio'], 2) ?></td>
                <td><?= $p['stock'] ?></td>
                <td>
                    <a href="productos.php?editar=<?= $p['id_producto'] ?>">editar</a> | 
                    <a href="productos.php?eliminar=<?= $p['id_producto'] ?>" onclick="return confirm('eliminas')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>