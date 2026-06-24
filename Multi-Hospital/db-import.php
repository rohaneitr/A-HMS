<?php
// ==========================================================================
// db-import.php - One-time full database importer (bypasses CodeIgniter)
// SECURITY: Delete this file after successful import!
// ==========================================================================

$host = getenv('DB_HOST') ?: 'db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'rootpassword';
$name = getenv('DB_NAME') ?: 'hmssaas';
$sqlFile = __DIR__ . '/../../../docker-entrypoint-initdb.d/database_tables.sql';

header('Content-Type: text/plain; charset=utf-8');
echo "=== HMS Database Importer ===\n";
echo "PHP " . PHP_VERSION . "\n";
echo "DB Host: $host\n";
echo "DB Name: $name\n";
echo "SQL File: $sqlFile\n\n";

// Connect
try {
    $conn = new mysqli($host, $user, $pass, $name);
    if ($conn->connect_error) {
        die("DB CONNECTION ERROR: " . $conn->connect_error . "\n");
    }
    echo "DB: Connected OK\n";
} catch (Exception $e) {
    die("DB Exception: " . $e->getMessage() . "\n");
}

// Count existing tables
$res = $conn->query("SELECT COUNT(*) as cnt FROM information_schema.tables WHERE table_schema='$name'");
$row = $res->fetch_assoc();
$tableCount = (int)$row['cnt'];
echo "Existing tables: $tableCount\n\n";

// Check if users table exists (core app table)
$usersCheck = $conn->query("SELECT COUNT(*) as cnt FROM information_schema.tables WHERE table_schema='$name' AND table_name='users'");
$usersRow = $usersCheck->fetch_assoc();
$usersExists = (int)$usersRow['cnt'] > 0;

if ($usersExists) {
    echo "STATUS: Core tables already exist! No import needed.\n";
    echo "users table: EXISTS\n";
} else {
    echo "STATUS: Core tables MISSING. Starting import...\n\n";

    // Read SQL from the bundled file in image
    if (!file_exists($sqlFile)) {
        // Try alternate paths
        $altPaths = [
            '/docker-entrypoint-initdb.d/database_tables.sql',
            __DIR__ . '/Database/database_tables.sql',
        ];
        foreach ($altPaths as $path) {
            if (file_exists($path)) {
                $sqlFile = $path;
                break;
            }
        }
    }

    if (!file_exists($sqlFile)) {
        echo "ERROR: SQL file not found at: $sqlFile\n";
        echo "Tried paths:\n";
        echo "  /docker-entrypoint-initdb.d/database_tables.sql\n";
        die();
    }

    echo "SQL File found: $sqlFile (" . round(filesize($sqlFile)/1024) . " KB)\n";

    $sql = file_get_contents($sqlFile);
    if (!$sql) {
        die("ERROR: Could not read SQL file\n");
    }

    // Set connection options for large import
    $conn->query("SET FOREIGN_KEY_CHECKS=0");
    $conn->query("SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO'");
    $conn->query("SET time_zone='+00:00'");
    mysqli_multi_query($conn, $sql);

    $queries = 0;
    $errors = 0;
    do {
        $queries++;
        if ($conn->errno) {
            $errors++;
            // Skip non-fatal errors (duplicate table etc)
        }
    } while (mysqli_next_result($conn));

    $conn->query("SET FOREIGN_KEY_CHECKS=1");

    // Verify
    $verify = $conn->query("SELECT COUNT(*) as cnt FROM information_schema.tables WHERE table_schema='$name'");
    $vRow = $verify->fetch_assoc();
    $finalCount = (int)$vRow['cnt'];

    echo "\nImport complete!\n";
    echo "Queries processed: $queries\n";
    echo "Errors (non-fatal): $errors\n";
    echo "Tables now: $finalCount\n";
}

// Always ensure ci_sessions
$conn->query("CREATE TABLE IF NOT EXISTS ci_sessions (
    id varchar(128) NOT NULL,
    ip_address varchar(45) NOT NULL,
    timestamp int(10) unsigned DEFAULT 0 NOT NULL,
    data blob NOT NULL,
    PRIMARY KEY (id),
    KEY ci_sessions_timestamp (timestamp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
echo "\nci_sessions table: " . ($conn->errno ? "ERROR: ".$conn->error : "READY") . "\n";

$conn->close();
echo "\n=== Done! Visit the site now. ===\n";
echo "SECURITY: Delete db-import.php after successful deployment!\n";
