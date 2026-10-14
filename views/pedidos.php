<?php
require_once '../config/conexion.php';


$mensaje = '';
$error = '';


if (isset($_POST['accion']) && $_POST['accion'] === 'crear') {
    try {
        $stmt = $pdo->prepare("INSERT INTO pedidos (id_cliente, fecha, total) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['id_cliente'], $_POST['fecha'], $_POST['total']]);
        header("Location: pedidos.php?msg=creado");
        exit;
    } catch (PDOException $e) {
        $error = "Error al registrar el pedido: " . $e->getMessage();
    }
}

if (isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    try {
        $stmt = $pdo->prepare("UPDATE pedidos SET id_cliente = ?, fecha = ?, total = ? WHERE id_pedido = ?");
        $stmt->execute([$_POST['id_cliente'], $_POST['fecha'], $_POST['total'], $_POST['id_pedido']]);
        header("Location: pedidos.php?msg=editado");
        exit;
    } catch (PDOException $e) {
        $error = "Error al actualizar el pedido: " . $e->getMessage();
    }
}


if (isset($_GET['eliminar'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM pedidos WHERE id_pedido = ?");
        $stmt->execute([$_GET['eliminar']]);
        header("Location: pedidos.php?msg=eliminado");
        exit;
    } catch (PDOException $e) {
        $error = "Error al eliminar el pedido: " . $e->getMessage();
    }
}


$sqlPedidos = "SELECT p.id_pedido, p.id_cliente, p.fecha, p.total, c.nombre AS cliente_nombre 
               FROM pedidos p 
               JOIN clientes c ON p.id_cliente = c.id_cliente";
$pedidos = $pdo->query($sqlPedidos)->fetchAll();


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
    <title>Gestión de Pedidos</title>
</head>
<body>
    <p><a href="../index.php">← Volver al Menú Principal</a></p>

    <h2>Gestión de Pedidos</h2>

    <?php if (isset($_GET['msg'])): ?>
        <p style="color: green; font-weight: bold;">
            <?php 
                if ($_GET['msg'] === 'creado') echo "¡Pedido guardado exitosamente!";
                if ($_GET['msg'] === 'editado') echo "¡Pedido actualizado exitosamente!";
                if ($_GET['msg'] === 'eliminado') echo "¡Pedido eliminado exitosamente!";
            ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p style="color: red; font-weight: bold;"><?= $error ?></p>
    <?php endif; ?>

    <h3><?= $pedidoEditar ? 'Modificar Pedido' : 'Registrar Nuevo Pedido' ?></h3>
    <form method="POST">
        <input type="hidden" name="accion" value="<?= $pedidoEditar ? 'editar' : 'crear' ?>">
        
        <?php if ($pedidoEditar): ?>
            <input type="hidden" name="id_pedido" value="<?= $pedidoEditar['id_pedido'] ?>">
        <?php endif; ?>

        <label>Cliente:</label>
        <select name="id_cliente" required>
            <option value="">-- Seleccione un Cliente --</option>
            <?php foreach ($clientes as $c): ?>
                <option value="<?= $c['id_cliente'] ?>" <?= ($pedidoEditar && $pedidoEditar['id_cliente'] == $c['id_cliente']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nombre']) ?> (ID: <?= $c['id_cliente'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Fecha del Pedido:</label>
        <input type="date" name="fecha" value="<?= $pedidoEditar['fecha'] ?? date('Y-m-d') ?>" required>
        <br><br>

        <label>Total ($):</label>
        <input type="number" step="0.01" min="0.01" name="total" value="<?= $pedidoEditar['total'] ?? '' ?>" required>
        <br><br>

        <button type="submit"><?= $pedidoEditar ? 'Actualizar Pedido' : 'Guardar Pedido' ?></button>
        <?php if ($pedidoEditar): ?>
            <a href="pedidos.php">Cancelar</a>
        <?php endif; ?>
    </form>

    <hr>


    <h3>Lista de Pedidos Registrados</h3>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>ID Pedido</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pedidos)): ?>
                <tr>
                    <td colspan="5" style="text-align: center;">No hay pedidos registrados.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($pedidos as $p): ?>
                <tr>
                    <td><?= $p['id_pedido'] ?></td>
                    <td><?= htmlspecialchars($p['cliente_nombre']) ?></td>
                    <td><?= $p['fecha'] ?></td>
                    <td>$<?= number_format($p['total'], 2) ?></td>
                    <td>
                        <a href="pedidos.php?editar=<?= $p['id_pedido'] ?>">Editar</a> | 
                        <a href="pedidos.php?eliminar=<?= $p['id_pedido'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este pedido?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>