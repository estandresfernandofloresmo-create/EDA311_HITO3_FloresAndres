<?php
require_once '../config/conexion.php';

if (isset($_POST['accion']) && $_POST['accion'] === 'crear') {
    $stmt = $pdo->prepare("INSERT INTO pedidos (id_cliente, fecha, total) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['id_cliente'], $_POST['fecha'], $_POST['total']]);
    header("Location: pedidos.php");
    exit;
}

if (isset($_POST['accion']) && $_POST['accion'] === 'editar') { // para editar pedido
    $stmt = $pdo->prepare("UPDATE pedidos SET id_cliente = ?, fecha = ?, total = ? WHERE id_pedido = ?");
    $stmt->execute([$_POST['id_cliente'], $_POST['fecha'], $_POST['total'], $_POST['id_pedido']]);
    header("Location: pedidos.php");
    exit;
}

if (isset($_GET['eliminar'])) {   //eliminacion
    $stmt = $pdo->prepare("DELETE FROM pedidos WHERE id_pedido = ?");
    $stmt->execute([$_GET['eliminar']]);
    header("Location: pedidos.php");
    exit;
}


$pedidos = $pdo->query("SELECT p.*, c.nombre AS cliente_nombre 
                        FROM pedidos p 
                        JOIN clientes c ON p.id_cliente = c.id_cliente")->fetchAll();

$clientes = $pdo->query("SELECT id_cliente, nombre FROM clientes")->fetchAll();

$pedidoEditar = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id_pedido = ?");
    $stmt->execute([$_GET['editar']]);
    $pedidoEditar = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body bgcolor="blue" font="black">
    
    <a href="../index.php">inicio</a>
    <h2>pedidos</h2>

    <form method="POST">
        <input type="hidden" name="accion" value="<?= $pedidoEditar ? 'editar' : 'crear' ?>">
        <?php if ($pedidoEditar): ?>
            <input type="hidden" name="id_pedido" value="<?= $pedidoEditar['id_pedido'] ?>">
        <?php endif; ?>

        <label color="blue">Cliente:</label>
        <select name="id_cliente" required>
            <option value="">clientes</option>
            
            <?php foreach ($clientes as $c): ?>
                <option value="<?= $c['id_cliente'] ?>" <?= ($pedidoEditar && $pedidoEditar['id_cliente'] == $c['id_cliente']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <label>Fecha</label>
        <input type="date" name="fecha" value="<?= $pedidoEditar['fecha'] ?? date('Y-m-d') ?>" required>
        
        <label>Total:</label>
        <input type="number" step="0.01" name="total" value="<?= $pedidoEditar['total'] ?? '' ?>" required>

        <button type="submit"><?= $pedidoEditar ? 'Actualizar' : 'Guardar' ?></button>
        <?php if ($pedidoEditar): ?><a href="pedidos.php">Cancelar</a><?php endif; ?>
    </form>

    <hr>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $p): ?>
            <tr>
                <td><?= $p['id_pedido'] ?></td>
                <td><?= htmlspecialchars($p['cliente_nombre']) ?></td>
                <td><?= $p['fecha'] ?></td>
                <td>$<?= number_format($p['total'], 2) ?></td>
                <td>
                    <a href="pedidos.php?editar=<?= $p['id_pedido'] ?>">Editar</a> | 
                    <a href="pedidos.php?eliminar=<?= $p['id_pedido'] ?>" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>