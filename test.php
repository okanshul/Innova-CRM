<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>InnovaCRM InfinityFree Server Diagnostic</h2>";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
echo "<p><strong>Loaded Extensions:</strong> " . implode(', ', get_loaded_extensions()) . "</p>";

try {
    $pdo = new PDO("mysql:host=sql202.infinityfree.com;port=3306;dbname=if0_42738344_Innova_crm", "if0_42738344", "8VKuL5EZdYIGh", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "<p style='color:green; font-weight:bold;'>✓ Database Connection Successful!</p>";
} catch (Exception $e) {
    echo "<p style='color:red; font-weight:bold;'>✗ Database Connection Failed: " . $e->getMessage() . "</p>";
}
