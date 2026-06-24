<?php
// Secure temporary database upgrade script
// To run: HTTP GET /rebrand_db.php?key=fctbd123

if (!isset($_GET['key']) || $_GET['key'] !== 'fctbd123') {
    header('HTTP/1.0 403 Forbidden');
    die('Forbidden: Invalid key.');
}

$db_host = getenv('DB_HOST') ?: 'db';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: 'rootpassword';
$db_name = getenv('DB_NAME') ?: 'hmssaas';

$conn = @new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}

echo "<h3>Database connection successful! Running upgrades...</h3><pre>";

// 1. Update superadmin global settings
$sql1 = "UPDATE settings SET 
            system_vendor='Fast Technologies', 
            title='A+HMS', 
            email='fctbd1@gmail.com', 
            phone='+8801759190782', 
            footer_message='Fast Technologies', 
            logo='uploads/logo.png', 
            logo_title='uploads/favicon.png' 
         WHERE hospital_id='superadmin'";
if ($conn->query($sql1)) {
    echo "1. Superadmin settings updated successfully. (Affected rows: " . $conn->affected_rows . ")\n";
} else {
    echo "1. Error updating superadmin settings: " . $conn->error . "\n";
}

// 2. Update settings of all other registered hospitals to remove developer attribution in footer
$sql2 = "UPDATE settings SET 
            system_vendor='Fast Technologies',
            footer_message='Fast Technologies' 
         WHERE footer_message LIKE '%Code Aristos%' OR footer_message LIKE '%Tanvir%' OR footer_message IS NULL";
if ($conn->query($sql2)) {
    echo "2. Secondary settings updated successfully. (Affected rows: " . $conn->affected_rows . ")\n";
} else {
    echo "2. Error updating secondary settings: " . $conn->error . "\n";
}

// 3. Ensure Bengali language exists in the language table
$check_lang = $conn->query("SELECT id FROM language WHERE language = 'bangla'");
if ($check_lang && $check_lang->num_rows > 0) {
    echo "3. Bengali language already registered in the language table.\n";
} else {
    $sql3 = "INSERT INTO language (language, folder_name, flag_icon, description, status) 
             VALUES ('bangla', 'bangla', 'bd', 'বাংলা (Bangla)', '1')";
    if ($conn->query($sql3)) {
        echo "3. Bengali language inserted successfully! (ID: " . $conn->insert_id . ")\n";
    } else {
        echo "3. Error inserting Bengali language: " . $conn->error . "\n";
    }
}

echo "\nAll database migrations complete!</pre>";
$conn->close();
?>
