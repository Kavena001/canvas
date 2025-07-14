<?php
// Bypass all security for emergency reset
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials - enter yours manually here
$db_host = 'localhost';
$db_name = 'u189409396_dbcanvas';
$db_user = 'u189409396_canvas2025';
$db_pass = 'Adminphoenix25';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    
    $new_password = 'admin123'; // Change to your desired password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
    $stmt->execute([$hashed_password]);
    
    echo "<h1>Password Reset Successful</h1>";
    echo "<p>New password: <strong>$new_password</strong></p>";
    echo "<p>This file will self-destruct in 5 seconds...</p>";
    
    // Self-destruct
    echo "<script>
            setTimeout(function() {
                window.location.href = 'login.php';
            }, 5000);
          </script>";
    
    // Delete this file
    unlink(__FILE__);
    
} catch (PDOException $e) {
    die("<h1>Error:</h1><p>" . $e->getMessage() . "</p>");
}
?>