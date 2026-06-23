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
        // Check and create ci_sessions table if missing
        $res = $conn->query("SHOW TABLES LIKE 'ci_sessions'");
        if ($res->num_rows > 0) {
            echo "ci_sessions table: EXISTS\n";
        } else {
            echo "ci_sessions table: MISSING. Creating...\n";
            $createTableQuery = "
                CREATE TABLE IF NOT EXISTS `ci_sessions` (
                    `id`         varchar(128)     NOT NULL,
                    `ip_address` varchar(45)      NOT NULL,
                    `timestamp`  int(10) unsigned DEFAULT 0 NOT NULL,
                    `data`       blob             NOT NULL,
                    PRIMARY KEY (`id`),
                    KEY `ci_sessions_timestamp` (`timestamp`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ";
            if ($conn->query($createTableQuery)) {
                echo "ci_sessions table: CREATED SUCCESSFULLY\n";
            } else {
                echo "ci_sessions table: CREATION FAILED: " . $conn->error . "\n";
            }
        }
        $conn->close();
    }
} catch (Exception $e) {
    echo "DB Exception: " . $e->getMessage() . "\n";
}
