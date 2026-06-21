<?php
$passwords = ['Admin@1234', 'Hms@12345', 'SuperAdmin@1', 'admin@123', 'Admin123!',
              'Superadmin@1', 'hms@12345', 'Admin@12345', 'rootpassword', 'superadmin',
              '12345', '123456', 'admin', 'admin123', 'Admin@123', 'Hms@1234',
              'superadmin123', 'SuperAdmin123', 'Admin1234!', 'Hms123456'];

$conn = new mysqli('db', 'root', 'rootpassword', 'hmssaas');
$res  = $conn->query("SELECT password FROM users WHERE email='superadmin@hms.com'");
$row  = $res->fetch_assoc();
$hash = $row['password'];

echo 'Superadmin hash prefix: ' . substr($hash, 0, 7) . PHP_EOL;
$found = false;
foreach ($passwords as $pw) {
    if (password_verify($pw, $hash)) {
        echo 'PASSWORD FOUND: ' . $pw . PHP_EOL;
        $found = true;
        break;
    }
}
if (!$found) {
    echo 'Password not in candidate list — was set to a custom value during PASS-001 remediation.' . PHP_EOL;
}
$conn->close();
