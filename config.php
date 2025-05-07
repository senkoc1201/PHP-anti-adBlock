<?php
// Enter the configuration settings below and then include
// the header.php file into the <head>*</head> of your website.

// Database configuration
$dbhost      = "localhost";
$dbusername  = "root";     // Changed to root user
$dbpassword  = "";         // Empty password for root
$dbname      = "c1testsite";
$tablePrefix = "adshield_";
$site_path   = "/scripts/adshield2";

try {
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbpassword);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
