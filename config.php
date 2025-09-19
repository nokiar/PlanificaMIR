<?php
// config.php - Configuración de la base de datos

$host = 'localhost';
$dbname = 'planificamir';
$username = 'root'; // Usuario por defecto en XAMPP
$password = ''; // Contraseña vacía por defecto en XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
// No closing PHP tag to avoid accidental output
