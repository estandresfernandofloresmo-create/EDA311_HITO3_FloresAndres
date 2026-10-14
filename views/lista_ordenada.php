<?php
require_once '../config/conexion.php';
require_once '../classes/ListaDoble.php';

$tabla = $_GET['tabla'] ?? 'clientes';
$campo = $_GET['campo'] ?? 'id_cliente';

$sql = "SELECT * FROM " . preg_replace('/[^a-zA-Z0-9_]/', '', $tabla);
$stmt = $pdo->query($sql);
$registros = $stmt->fetchAll();

$lista = new ListaDoble();
foreach ($registros as $row) {
    $lista->insertar($row);
}

if ($campo) {
    $lista->ordenarBubbleSort($campo);
}

$datosOrdenados = $lista->obtenerElementos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    <a href="../index.php">inicio de la pagina</a>
    <h2>lista doble + burbuja </h2>

    <form method="GET">
        <label>tabla</label>
        <select name="tabla" onchange="this.form.submit()">
            <option value="clientes" <?= $tabla === 'clientes' ? 'selected' : '' ?>>Clientes</option>
            <option value="productos" <?= $tabla === 'productos' ? 'selected' : '' ?>>Productos</option>
            <option value="pedidos" <?= $tabla === 'pedidos' ? 'selected' : '' ?>>Pedidos</option>
        </select>
    </form>

    <hr>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <?php if (!empty($datosOrdenados)): ?>
                    <?php foreach (array_keys($datosOrdenados[0]) as $col): ?>
                        <th><?= htmlspecialchars($col) ?></th>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datosOrdenados as $fila): ?>
                <tr>
                    <?php foreach ($fila as $val): ?>
                        <td><?= htmlspecialchars($val) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>