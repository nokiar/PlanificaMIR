<?php
echo "<h1>TEST DE CONEXIÓN</h1>";
echo "<p>HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'No definido') . "</p>";
echo "<p>SERVER_NAME: " . ($_SERVER['SERVER_NAME'] ?? 'No definido') . "</p>";

// Detectar dominio
$httpHost = $_SERVER['HTTP_HOST'] ?? '';
echo "<p>Detectando .page.gd: " . (strpos($httpHost, '.page.gd') !== false ? 'SÍ' : 'NO') . "</p>";

// Test de conexión
try {
    // Credenciales InfinityFree
    $host = 'sql104.infinityfree.com';
    $dbname = 'if0_39980782_planificamir';
    $username = 'if0_39980782';
    $password = 'DWccYKXLKSdQpPo';
    $port = 3306;
    
    echo "<h2>Intentando conectar a InfinityFree...</h2>";
    echo "<p>Host: $host</p>";
    echo "<p>Database: $dbname</p>";
    echo "<p>Usuario: $username</p>";
    
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div style='color: green; font-weight: bold;'>✅ CONEXIÓN EXITOSA A INFINITYFREE</div>";
    
    // Test simple
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM subjects");
    $result = $stmt->fetch();
    echo "<p>Asignaturas en BD: " . $result['count'] . "</p>";
    
} catch (Exception $e) {
    echo "<div style='color: red; font-weight: bold;'>❌ ERROR: " . $e->getMessage() . "</div>";
}
?>
