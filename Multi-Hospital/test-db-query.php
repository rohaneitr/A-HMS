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
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute(['fast@hospital.com']);
    $userRow = $stmt->fetch();
    if (!$userRow) {
        echo "User fast@hospital.com not found!\n";
        exit;
    }
    echo "User Details:\n";
    echo "ID: " . $userRow['id'] . "\n";
    echo "Hospital ID: " . $userRow['hospital_id'] . "\n\n";
    $hospital_id = $userRow['hospital_id'];

    // 2. Query count of medicines for this hospital
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM medicine WHERE hospital_id = ?");
    $stmt->execute([$hospital_id]);
    echo "Medicine count for hospital $hospital_id: " . $stmt->fetch()['count'] . "\n\n";

    // 3. Select first 5 medicines
    $stmt = $pdo->prepare("SELECT * FROM medicine WHERE hospital_id = ? LIMIT 5");
    $stmt->execute([$hospital_id]);
    echo "First 5 medicines:\n";
    print_r($stmt->fetchAll());
    echo "\n";

    // 4. Test searching query
    $search = 'Napa';
    echo "Testing search query with '$search':\n";
    try {
        $q = "SELECT * FROM medicine WHERE hospital_id = ? AND (id LIKE ? OR category LIKE ? OR name LIKE ? OR e_date LIKE ? OR generic LIKE ? OR company LIKE ? OR effects LIKE ?)";
        $stmt = $pdo->prepare($q);
        $likeSearch = "%$search%";
        $stmt->execute([$hospital_id, $likeSearch, $likeSearch, $likeSearch, $likeSearch, $likeSearch, $likeSearch, $likeSearch]);
        echo "Search query succeeded. Match count: " . count($stmt->fetchAll()) . "\n";
    } catch (Exception $e) {
        echo "Search query failed: " . $e->getMessage() . "\n";
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
