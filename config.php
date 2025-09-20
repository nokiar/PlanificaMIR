<?php
// config.php - Configuración de la base de datos

// Obtener información del host
$httpHost = $_SERVER['HTTP_HOST'] ?? 'No definido';
$serverName = $_SERVER['SERVER_NAME'] ?? 'No definido';

// Detectar si estamos en producción (InfinityFree) o desarrollo local
$isProduction = (
    // Detectar dominios de InfinityFree
    isset($_SERVER['HTTP_HOST']) && (
        strpos($_SERVER['HTTP_HOST'], '.page.gd') !== false ||
        strpos($_SERVER['HTTP_HOST'], '.infinityfreeapp.com') !== false ||
        strpos($_SERVER['HTTP_HOST'], '.epizy.com') !== false ||
        strpos($_SERVER['HTTP_HOST'], '.rf.gd') !== false ||
        strpos($_SERVER['HTTP_HOST'], '.atwebpages.com') !== false ||
        // Cualquier dominio que no sea localhost
        ($_SERVER['HTTP_HOST'] !== 'localhost' && !preg_match('/^127\.0\.0\.1/', $_SERVER['HTTP_HOST']))
    )
);

// Debug: SIEMPRE mostrar información del entorno
echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px; border: 1px solid #ccc; font-family: monospace;'>";
echo "<strong>DEBUG CONFIG.PHP:</strong><br>";
echo "HTTP_HOST: " . $httpHost . "<br>";
echo "SERVER_NAME: " . $serverName . "<br>";
echo "Es producción: " . ($isProduction ? 'SÍ' : 'NO') . "<br>";
echo "Detectando '.page.gd': " . (strpos($httpHost, '.page.gd') !== false ? 'SÍ' : 'NO') . "<br>";
echo "</div>";

// También log a la consola del navegador
echo "<script>console.log('CONFIG DEBUG:', {";
echo "httpHost: '" . $httpHost . "',";
echo "serverName: '" . $serverName . "',";
echo "isProduction: " . ($isProduction ? 'true' : 'false') . ",";
echo "pageGdDetected: " . (strpos($httpHost, '.page.gd') !== false ? 'true' : 'false');
echo "});</script>";

if ($isProduction) {
    // Configuración para InfinityFree
    $host = 'sql104.infinityfree.com';
    $dbname = 'if0_39980782_planificamir';
    $username = 'if0_39980782';
    $password = 'DWccYKXLKSdQpPo';
    $port = 3306;
    
    echo "<div style='background: #d4edda; padding: 10px; margin: 10px; border: 1px solid #c3e6cb; color: #155724;'>";
    echo "<strong>🌐 CONECTANDO A BASE DE DATOS ONLINE (InfinityFree)</strong><br>";
    echo "Host: " . $host . "<br>";
    echo "Database: " . $dbname . "<br>";
    echo "Usuario: " . $username . "<br>";
    echo "</div>";
} else {
    // Configuración local XAMPP
    $host = 'localhost';
    $dbname = 'planificamir';
    $username = 'root'; // Usuario por defecto en XAMPP
    $password = ''; // Contraseña vacía por defecto en XAMPP
    $port = 3306;
    
    echo "<div style='background: #fff3cd; padding: 10px; margin: 10px; border: 1px solid #ffeaa7; color: #856404;'>";
    echo "<strong>🏠 CONECTANDO A BASE DE DATOS LOCAL (XAMPP)</strong><br>";
    echo "Host: " . $host . "<br>";
    echo "Database: " . $dbname . "<br>";
    echo "Usuario: " . $username . "<br>";
    echo "</div>";
}

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div style='background: #d1ecf1; padding: 10px; margin: 10px; border: 1px solid #bee5eb; color: #0c5460;'>";
    echo "<strong>✅ CONEXIÓN EXITOSA A LA BASE DE DATOS</strong><br>";
    echo "Conexión establecida correctamente<br>";
    echo "</div>";
    
    echo "<script>console.log('✅ Conexión a BD exitosa:', {host: '$host', database: '$dbname'});</script>";
    
} catch (PDOException $e) {
    echo "<div style='background: #f8d7da; padding: 10px; margin: 10px; border: 1px solid #f5c6cb; color: #721c24;'>";
    echo "<strong>❌ ERROR DE CONEXIÓN</strong><br>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "</div>";
    
    echo "<script>console.error('❌ Error de conexión BD:', '" . $e->getMessage() . "');</script>";
    
    die("Error de conexión: " . $e->getMessage());
}
// No closing PHP tag to avoid accidental output
