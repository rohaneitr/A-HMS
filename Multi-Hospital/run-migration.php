<?php
// ==========================================================================
// run-migration.php — Runs the update_branding_2026.sql migration
// SECURITY: Delete this file after use!
// ==========================================================================

$host = getenv('DB_HOST') ?: 'db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'rootpassword';
$name = getenv('DB_NAME') ?: 'hmssaas';

header('Content-Type: text/plain; charset=utf-8');
echo "=== A+HMS Migration Runner ===\n";
echo "PHP " . PHP_VERSION . "\n";
echo "DB Host: $host\n";
echo "DB Name: $name\n\n";

try {
    $conn = new mysqli($host, $user, $pass, $name);
    if ($conn->connect_error) {
        die("DB CONNECTION ERROR: " . $conn->connect_error . "\n");
    }
    echo "DB: Connected OK\n\n";
} catch (Exception $e) {
    die("DB Exception: " . $e->getMessage() . "\n");
}

// --- Run each statement individually for better error reporting ---

$statements = [];

// 1. Update superadmin settings
$statements['superadmin_title'] = "UPDATE `settings`
SET `title` = 'A+HMS',
    `system_vendor` = 'Fast Technologies',
    `footer_message` = 'By Fast Technologies'
WHERE `hospital_id` = 'superadmin'";

// 2. Update existing hospital vendors
$statements['vendor_update_1'] = "UPDATE `settings`
SET `system_vendor` = 'Fast Technologies - Hospital management System'
WHERE `system_vendor` = 'Code Aristos - Hospital management System'";

$statements['vendor_update_2'] = "UPDATE `settings`
SET `system_vendor` = 'Fast Technologies | Hospital management System'
WHERE `system_vendor` = 'Code Aristos | Hospital management System'";

$statements['footer_update_1'] = "UPDATE `settings`
SET `footer_message` = 'By Fast Technologies'
WHERE `footer_message` = 'By Code Aristos'";

$statements['footer_update_2'] = "UPDATE `settings`
SET `footer_message` = 'By Fast Technologies'
WHERE `footer_message` = 'BycaSoft'";

// 3. Create SSLCOMMERZ state table
$statements['create_ssl_state'] = "CREATE TABLE IF NOT EXISTS `sslcommerz_payments_state` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `tran_id` VARCHAR(100) NOT NULL UNIQUE,
  `patient_id` INT DEFAULT NULL,
  `payment_id` INT DEFAULT NULL,
  `amount` DECIMAL(10,2) DEFAULT NULL,
  `user_id` INT DEFAULT NULL,
  `hospital_id` VARCHAR(100) DEFAULT NULL,
  `redirect_link` VARCHAR(100) DEFAULT NULL,
  `status` VARCHAR(20) DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// 4. Insert SSLCOMMERZ for each hospital (using users table as hospital reference)
$statements['ssl_all_hospitals'] = "INSERT INTO `paymentGateway` (`name`, `status`, `APIUsername`, `APIPassword`, `hospital_id`)
SELECT 'SSLCOMMERZ', 'test', 'Your Store ID', 'Your Store Password', u.id
FROM `users` u
INNER JOIN `groups_users` gu ON gu.user_id = u.id
INNER JOIN `groups` g ON g.id = gu.group_id AND g.name = 'admin'
WHERE NOT EXISTS (
    SELECT 1 FROM `paymentGateway` pg
    WHERE pg.`name` = 'SSLCOMMERZ' AND pg.`hospital_id` = u.id
)";

// 5. Insert for superadmin
$statements['ssl_superadmin'] = "INSERT INTO `paymentGateway` (`name`, `status`, `APIUsername`, `APIPassword`, `hospital_id`)
SELECT 'SSLCOMMERZ', 'test', 'Your Store ID', 'Your Store Password', 'superadmin'
WHERE NOT EXISTS (
    SELECT 1 FROM `paymentGateway` pg
    WHERE pg.`name` = 'SSLCOMMERZ' AND pg.`hospital_id` = 'superadmin'
)";

// --- Execute each statement ---
foreach ($statements as $label => $sql) {
    $result = $conn->query($sql);
    if ($result === false) {
        echo "[FAIL] $label: " . $conn->error . "\n";
    } else {
        $affected = $conn->affected_rows;
        echo "[OK]   $label (affected rows: $affected)\n";
    }
}

// --- Verify SSLCOMMERZ was inserted ---
echo "\n--- Verification ---\n";
$check = $conn->query("SELECT hospital_id, status FROM `paymentGateway` WHERE name = 'SSLCOMMERZ'");
if ($check && $check->num_rows > 0) {
    echo "SSLCOMMERZ rows found:\n";
    while ($row = $check->fetch_assoc()) {
        echo "  hospital_id=" . $row['hospital_id'] . " | status=" . $row['status'] . "\n";
    }
} else {
    echo "WARNING: No SSLCOMMERZ rows found in paymentGateway table!\n";
    // Fallback: list all hospitals
    echo "\nHospitals in users table (group=admin):\n";
    $hospitals = $conn->query("SELECT u.id, u.username FROM users u
        INNER JOIN groups_users gu ON gu.user_id = u.id
        INNER JOIN groups g ON g.id = gu.group_id AND g.name = 'admin'");
    if ($hospitals) {
        while ($h = $hospitals->fetch_assoc()) {
            echo "  id=" . $h['id'] . " username=" . $h['username'] . "\n";
        }
    }
}

$conn->close();
echo "\n=== Migration Done! ===\n";
echo "SECURITY: Delete run-migration.php after use!\n";
