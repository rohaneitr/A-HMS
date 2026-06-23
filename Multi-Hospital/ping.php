<?php
// Quick diagnostic - bypasses CodeIgniter entirely
echo "PHP OK\n";
echo "CI_ENV: " . getenv('CI_ENV') . "\n";
echo "DB_HOST: " . getenv('DB_HOST') . "\n";
echo "DB_NAME: " . getenv('DB_NAME') . "\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";

// Test DB connection
$host = getenv('DB_HOST') ?: 'db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'rootpassword';
$name = getenv('DB_NAME') ?: 'hmssaas';

try {
    $conn = new mysqli($host, $user, $pass, $name);
    if ($conn->connect_error) {
        echo "DB ERROR: " . $conn->connect_error . "\n";
    } else {
        echo "DB OK: Connected to $name\n";
        // Check ci_sessions table
        $res = $conn->query("SHOW TABLES LIKE 'ci_sessions'");
        echo "ci_sessions table: " . ($res->num_rows > 0 ? "EXISTS" : "MISSING") . "\n";
        $conn->close();
    }
} catch (Exception $e) {
    echo "DB Exception: " . $e->getMessage() . "\n";
}
