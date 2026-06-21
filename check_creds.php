<?php
$passwords_to_test = ['12345', '123456', 'admin123', 'password', '1234', 'hms123', 'demo123', 'admin', 'Admin@123', 'Hms@1234'];

$conn = new mysqli('db', 'root', 'rootpassword', 'hmssaas');
if ($conn->connect_error) {
    die('DB connection failed: ' . $conn->connect_error . PHP_EOL);
}

$emails = [
    'superadmin@hms.com',
    'accountant@hms.com',
    'doctor1@hms.com',
    'doctor@hms.com',
    'nurse@hms.com',
    'patient@hms.com',
    'pharmacist@hms.com',
    'laboratorist@hms.com',
    'receptionist@hms.com',
    'uhm@gmail.com',
];

$in = implode("','", $emails);
$res = $conn->query("SELECT email, password FROM users WHERE email IN ('$in')");

echo str_pad('EMAIL', 35) . str_pad('PASSWORD', 16) . 'HASH PREFIX' . PHP_EOL;
echo str_repeat('-', 65) . PHP_EOL;

$found = [];
while ($row = $res->fetch_assoc()) {
    $cracked = false;
    foreach ($passwords_to_test as $pw) {
        if (password_verify($pw, $row['password'])) {
            echo str_pad($row['email'], 35) . str_pad($pw, 16) . substr($row['password'], 0, 7) . PHP_EOL;
            $cracked = true;
            break;
        }
    }
    if (!$cracked) {
        echo str_pad($row['email'], 35) . str_pad('[not in test list]', 16) . substr($row['password'], 0, 7) . PHP_EOL;
    }
}
$conn->close();
