<?php
// Reset superadmin password to a known value for local pentesting
$new_password = 'Admin@HMS2024!';
$hash = password_hash($new_password, PASSWORD_BCRYPT, ['cost' => 14]);

$conn = new mysqli('db', 'root', 'rootpassword', 'hmssaas');
$stmt = $conn->prepare("UPDATE users SET password=? WHERE email='superadmin@hms.com'");
$stmt->bind_param('s', $hash);
$stmt->execute();

echo 'Rows updated: ' . $stmt->affected_rows . PHP_EOL;
echo 'New hash prefix: ' . substr($hash, 0, 7) . PHP_EOL;

// Verify it works
$res  = $conn->query("SELECT password FROM users WHERE email='superadmin@hms.com'");
$row  = $res->fetch_assoc();
$ok   = password_verify($new_password, $row['password']);
echo 'Verification: ' . ($ok ? 'PASS' : 'FAIL') . PHP_EOL;
echo 'Superadmin password set to: ' . $new_password . PHP_EOL;
$conn->close();
