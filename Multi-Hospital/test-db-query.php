<?php
header('Content-Type: text/plain; charset=utf-8');

$host = getenv('DB_HOST') ?: 'db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'rootpassword';
$dbname = getenv('DB_NAME') ?: 'hmssaas';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    echo "Connected successfully to database: $dbname\n\n";

    // 1. Get hospital_id for fast@hospital.com
    $stmt = $pdo->prepare("SELECT hospital_id FROM users WHERE email = ?");
    $stmt->execute(['fast@hospital.com']);
    $userRow = $stmt->fetch();
    if (!$userRow) {
        echo "User fast@hospital.com not found!\n";
        exit;
    }
    $hospital_id = $userRow['hospital_id'];
    echo "Hospital ID: $hospital_id\n\n";

    // Get currency settings
    $stmt = $pdo->prepare("SELECT currency FROM settings WHERE hospital_id = ?");
    $stmt->execute([$hospital_id]);
    $settingsRow = $stmt->fetch();
    $currency = $settingsRow ? $settingsRow['currency'] : '$';

    // 2. Fetch ALL medicines for this hospital
    $stmt = $pdo->prepare("SELECT * FROM medicine WHERE hospital_id = ?");
    $stmt->execute([$hospital_id]);
    $medicines = $stmt->fetchAll();

    echo "Total medicines found: " . count($medicines) . "\n\n";

    $info = [];
    $i = 0;
    foreach ($medicines as $medicine) {
        $i++;
        
        $row_data = [
            $i,
            $medicine['name'],
            $medicine['category'],
            $medicine['box'],
            $currency . $medicine['price'],
            $currency . $medicine['s_price'],
            $medicine['quantity'],
            $medicine['generic'],
            $medicine['company'],
            $medicine['effects'],
            $medicine['e_date'],
            'options_placeholder'
        ];

        // Test json_encode on this single row
        $encoded = json_encode($row_data);
        if ($encoded === false) {
            echo "ERROR: Row $i (Medicine ID: {$medicine['id']}) failed json_encode!\n";
            echo "Details:\n";
            print_r($medicine);
            echo "JSON Error: " . json_last_error_msg() . "\n\n";
        }
        
        $info[] = $row_data;
    }

    // Test json_encode on entire dataset
    echo "Testing json_encode on the full output array...\n";
    $final_json = json_encode($info);
    if ($final_json === false) {
        echo "FAIL: Full output failed json_encode! Error: " . json_last_error_msg() . "\n";
    } else {
        echo "SUCCESS: Full output successfully json_encoded! Size: " . strlen($final_json) . " bytes\n";
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
