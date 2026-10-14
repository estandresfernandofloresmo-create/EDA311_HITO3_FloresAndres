<?php
require_once '../config/conexion.php';
if (isset($_POST['accion']) && $_POST['accion'] === 'crear') {
    $stmt = $pdo->prepare("INSERT INTO clientes (nombre, email, edad) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['nombre'], $_POST['email'], $_POST['edad']]);
    header("Location: clientes.php");
    exit;
}


if (isset($_POST['accion']) && $_POST['accion'] === 'editar') {           //editar
    $stmt = $pdo->prepare("UPDATE clientes SET nombre = ?, email = ?, edad = ? WHERE id_cliente = ?");
    $stmt->execute([$_POST['nombre'], $_POST['email'], $_POST['edad'], $_POST['id_cliente']]);
    header("Location: clientes.php");
    exit;
}


if (isset($_GET['eliminar'])) {               //eliminar 
    $stmt = $pdo->prepare("DELETE FROM clientes WHERE id_cliente = ?");
    $stmt->execute([$_GET['eliminar']]);
    header("Location: clientes.php");
    exit;
}


$clientes = $pdo->query("SELECT * FROM clientes")->fetchAll();
$clienteEditar = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
    $stmt->execute([$_GET['editar']]);
    $clienteEditar = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    <a href="../index.php">regresar</a>
    <h2>gestion de clientes</h2>

  
    <form method="POST">
        <input type="hidden" name="accion" value="<?= $clienteEditar ? 'editar' : 'crear' ?>">
        <?php if ($clienteEditar): ?>

            <input type="hidden" name="id_cliente" value="<?= $clienteEditar['id_cliente'] ?>">

        <?php endif; ?>

        <label>nombre</label>
        <input type="text" name="nombre" value="<?= $clienteEditar['nombre'] ?? '' ?>" required>
        
        <label>Email</label>
        <input type="email" name="email" value="<?= $clienteEditar['email'] ?? '' ?>" required>
        
        <label>Edad</label>
        <input type="number" name="edad" value="<?= $clienteEditar['edad'] ?? '' ?>" required>

        <button type="submit"><?= $clienteEditar ? 'Actualizar' : 'Guardar' ?></button>
        <?php if ($clienteEditar): ?><a href="clientes.php">Cancelar</a><?php endif; ?>
    </form>

    <hr>

   
    <table border="1" cellpadding="8" color="red">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Edad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $c): ?>
            <tr>
                <td><?= $c['id_cliente'] ?></td>
                <td><?= htmlspecialchars($c['nombre']) ?></td>
                <td><?= htmlspecialchars($c['email']) ?></td>
                <td><?= $c['edad'] ?></td>
                <td>
                    <a href="clientes.php?editar=<?= $c['id_cliente'] ?>">editr</a> | 
                    <a href="clientes.php?eliminar=<?= $c['id_cliente'] ?>" onclick="return confirm('elimiar')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>