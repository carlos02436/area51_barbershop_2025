<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/PQRSController.php';

$controller = new PQRSController($db);
$pqrs = $controller->ver($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->editar($_GET['id'], $_POST['nombre'], $_POST['apellidos'], $_POST['email'], $_POST['tipo'], $_POST['mensaje'], $_POST['estado']);
    header("Location: pqrs.php");
    exit;
}
?>
<body>
    <h2>Editar PQRS</h2>
    <form method="post">
        <input type="text" name="nombre" value="<?= $pqrs['nombre'] ?>" required><br>
        <input type="text" name="apellidos" value="<?= $pqrs['apellidos'] ?>" required><br>
        <input type="email" name="email" value="<?= $pqrs['email'] ?>" required><br>
        <select name="tipo">
            <?php foreach (['Petición','Queja','Reclamo','Sugerencia'] as $tipo): ?>
                <option value="<?= $tipo ?>" <?= $pqrs['tipo'] === $tipo ? 'selected' : '' ?>><?= $tipo ?></option>
            <?php endforeach; ?>
        </select><br>
        <textarea name="mensaje"><?= $pqrs['mensaje'] ?></textarea><br>
        <select name="estado">
            <?php foreach (['Pendiente','En Proceso','Resuelto'] as $estado): ?>
                <option value="<?= $estado ?>" <?= $pqrs['estado'] === $estado ? 'selected' : '' ?>><?= $estado ?></option>
            <?php endforeach; ?>
        </select><br>
        <button type="submit">Actualizar</button>
    </form>
<main>