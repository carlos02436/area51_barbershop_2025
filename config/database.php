<?php
// config/database.php


$host = "localhost";   
$dbname = "area51_barbershop_2025"; // tu base de datos
$username = "root";    // tu usuario de MySQL
$password = "";        // tu contraseña (si tienes)

try {
    // Conexión con PDO
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Opciones para manejo de errores
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}