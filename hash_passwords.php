<?php
require_once 'config/database.php';

$stmt = $db->query("SELECT id_admin, password FROM administradores");
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($admins as $admin) {
    $hash = password_hash($admin['password'], PASSWORD_DEFAULT);
    $update = $db->prepare("UPDATE administradores SET password = ? WHERE id_admin = ?");
    $update->execute([$hash, $admin['id_admin']]);
    echo "Contraseña de admin ID {$admin['id_admin']} actualizada.<br>";
}
echo "Proceso completado.";