<?php
include_once '../config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$formSubmitted = false;
$apSettingsTable = $tablePrefix . "settings";
$checkTableQuery = "SHOW TABLES LIKE '$apSettingsTable'";
$tableExists = $conn->query($checkTableQuery)->rowCount() > 0;

if ($tableExists) {
    $settingsQuery = "SELECT * FROM $apSettingsTable";
    $settingsResult = $conn->query($settingsQuery);
    $settingsRow = $settingsResult->fetch(PDO::FETCH_ASSOC);
    if ($settingsRow['initialization'] == 2) {
        goto end;
    }
} else {

}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $formSubmitted = true;
    try {
        $adBlocksTable = $tablePrefix . "blocks";
        $adClicksTable = $tablePrefix . "clicks";
        $apBansTable = $tablePrefix . "bans";
        $apSettingsTable = $tablePrefix . "settings";
        $apUsersTable = $tablePrefix . "users";

        $sql1_drop = "DROP TABLE IF EXISTS $adBlocksTable";

        $sql1 = "CREATE TABLE IF NOT EXISTS $adBlocksTable (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ip_address VARCHAR(50) NOT NULL,
            fingerprint VARCHAR(50) NOT NULL,
            ad_unit_id VARCHAR(255) NOT NULL,
            onWhitelist VARCHAR(255) NOT NULL DEFAULT '0',
            block_untill DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        $conn->exec($sql1_drop);
        $conn->exec($sql1);


        $sql2_drop = "DROP TABLE IF EXISTS $adClicksTable";
        $sql2 = "CREATE TABLE IF NOT EXISTS $adClicksTable (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ip_address VARCHAR(50) NOT NULL,
            fingerprint VARCHAR(50) NOT NULL,
            ad_unit_id VARCHAR(50) NOT NULL,
            onWhitelist VARCHAR(255) NOT NULL DEFAULT '0',
            click_time DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        $conn->exec($sql2_drop);
        $conn->exec($sql2);

        $sql3_drop = "DROP TABLE IF EXISTS $apBansTable";
        $sql3 = "CREATE TABLE IF NOT EXISTS $apBansTable (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ip_from VARCHAR(50) NOT NULL,
            ip_to VARCHAR(50) NOT NULL,
            onWhitelist VARCHAR(50) NOT NULL DEFAULT '0',
            inserted_time DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        $conn->exec($sql3_drop);
        $conn->exec($sql3);


        $sql4_drop = "DROP TABLE IF EXISTS $apSettingsTable";
        $sql4 = "CREATE TABLE IF NOT EXISTS $apSettingsTable (
            id INT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            limit_number INT(20) NOT NULL,
            time_frame_days INT(20) NOT NULL,
            time_frame_hours INT(20) NOT NULL,
            time_frame_minutes INT(20) NOT NULL,
            time_frame_seconds INT(20) NOT NULL,
            blocked_days INT(20) NOT NULL,
            blocked_hours INT(20) NOT NULL,
            blocked_minutes INT(20) NOT NULL,
            blocked_seconds INT(20) NOT NULL,
            initialization INT(20) NOT NULL,
            blocked_type INT(20) NOT NULL,
            ip_fingerprint INT(20) NOT NULL,
            pagination INT(20) NOT NULL
        )";
        $conn->exec($sql4_drop);
        $conn->exec($sql4);

        $stmt = $conn->prepare("INSERT INTO $apSettingsTable (limit_number, time_frame_days, time_frame_hours, time_frame_minutes, time_frame_seconds, blocked_days, blocked_hours, blocked_minutes, blocked_seconds,  initialization, blocked_type, ip_fingerprint, pagination) VALUES (:limit_number, :time_frame_days, :time_frame_hours, :time_frame_minutes, :time_frame_seconds, :blocked_days, :blocked_hours, :blocked_minutes, :blocked_seconds, :initialization, :blocked_type, :ip_fingerprint, :pagination)");

        $stmt->bindParam(':limit_number', $limit_number);
        $stmt->bindParam(':time_frame_days', $time_frame_days);
        $stmt->bindParam(':time_frame_hours', $time_frame_hours);
        $stmt->bindParam(':time_frame_minutes', $time_frame_minutes);
        $stmt->bindParam(':time_frame_seconds', $time_frame_seconds);
        $stmt->bindParam(':blocked_days', $blocked_days);
        $stmt->bindParam(':blocked_hours', $blocked_hours);
        $stmt->bindParam(':blocked_minutes', $blocked_minutes);
        $stmt->bindParam(':blocked_seconds', $blocked_seconds);
        $stmt->bindParam(':initialization', $initialization);
        $stmt->bindParam(':blocked_type', $blocked_type);
        $stmt->bindParam(':ip_fingerprint', $ip_fingerprint);
        $stmt->bindParam(':pagination', $pagination);


        $limit_number = 2;
        $time_frame_days = 3;
        $time_frame_hours = 1;
        $time_frame_minutes = 3;
        $time_frame_seconds = 6;
        $blocked_days = 8;
        $blocked_hours = 3;
        $blocked_minutes = 6;
        $blocked_seconds = 9;
        $blocked_type = 1;
        $initialization = 1;
        $ip_fingerprint = 1;
        $pagination = 10;

        $stmt->execute();

        $sql5_drop = "DROP TABLE IF EXISTS $apUsersTable";
        $sql5 = "CREATE TABLE IF NOT EXISTS $apUsersTable (
            `id` int(5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `username` varchar(100) NOT NULL,
            `password` varchar(100) NOT NULL,
            `email` varchar(100) NOT NULL,
            `isAdmin` tinyint(5) NOT NULL,
            `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            `updated` datetime NOT NULL
            )";
        $conn->exec($sql5_drop);
        $conn->exec($sql5);

        $stmt = $conn->prepare("INSERT INTO $apUsersTable (username, password, email, isAdmin, created, updated) VALUES (:username, :password, :email, :isAdmin, :created, :updated)");

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':isAdmin', $isAdmin);
        $stmt->bindParam(':created', $created);
        $stmt->bindParam(':updated', $updated);


        $username = "admin";
        $password = md5("admin");
        $email = "info@domain.com";
        $isAdmin = 1;
        $created = date('Y-m-d H:i:s');
        $updated = date('Y-m-d H:i:s');

        $stmt->execute();

        header("Location: completed.php");
        exit();
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
end:
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to Page</title>
    <link rel="stylesheet" type="text/css" href="../css/style_two.css" />
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 90vh;">
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <div class="card">
        <div class="card-header text-center" style="background: #a0979726;width: 800px;">
            <h1 class="card-title" style="font-family: sans-serif;">
                <i class="fa fa-user-circle"></i> Welcome to Ads-Script
            </h1>
        </div>
        <?php
        $checkTableQuery = "SHOW TABLES LIKE '$apSettingsTable'";
        $tableExists = $conn->query($checkTableQuery)->rowCount() > 0;
        if ($tableExists) {
            if ($settingsRow['initialization'] == 1) {
                if ($settingsResult || $settingsResult->rowCount() == 0) { ?>
                    <div class="card-body" style="text-align: center;">
                        <h3 class="card-text" style="font-family: sans-serif;">
                            <i class="fa fa-user-circle"></i> The script already installed. Please click the install button to override.
                        </h3>
                        <input type="checkbox" id="checkbox" class="checkbox"> Confirm Override.
                    </div>
                    <div class="card-footer text-center" style="background: #a0979726;">
                        <button type="submit" id="installButton" name="submit" class="btn btn-primary" style="background-color: #0d6efd;color: white; border: none; border-radius: 9px;padding: 10px 15px 10px 15px;font-family: sans-serif;">Install</button>
                    </div>
          <?php } else { ?>
                    <div class="card-body" style="text-align: center;">
                    <h3 class="card-text" style="font-family: sans-serif;">
                        <i class="fa fa-user-circle"></i> Please click the install button to Install.
                    </h3>
                    </div>
                    <div class="card-footer text-center" style="background: #a0979726;">
                        <button type="submit" id="installButton" name="submit" class="btn btn-primary" style="background-color: #0d6efd;color: white; border: none; border-radius: 9px;padding: 10px 15px 10px 15px;font-family: sans-serif;">Install</button>
                    </div>
                <?php }
            } else {
                echo '<div class="card-body">
                     <div class="alert alert-danger" role="alert" style="text-align: center;background: #ea7171e3;color: white;border-radius: 20px;font-size: 20px;">
                         <h3 class="alert-heading">
                             <i class="fa fa-exclamation-triangle"></i> Initialization disabled on the admin panel.
                         </h3>
                         <p>Please contact the administrator.</p>
                     </div>
                </div>';
            }
        } else { ?>
            <div class="card-body" style="text-align: center;">
                <h3 class="card-text" style="font-family: sans-serif;">
                    <i class="fa fa-user-circle"></i> Please click the install button to Install
                </h3>
            </div>
            <div class="card-footer text-center" style="background: #a0979726;">
                <button type="submit" id="installButton" name="submit" class="btn btn-primary" style="background-color: #0d6efd;color: white; border: none; border-radius: 9px;padding: 10px 15px 10px 15px;font-family: sans-serif;">Install</button>
            </div>
  <?php } ?>
    </div>
</form>
<script>
document.querySelector("form").addEventListener("submit", function(event) {
    if (!document.getElementById("checkbox").checked) {
        alert("Please check the checkbox to continue.");
        event.preventDefault();
    }
});
</script>
</body>
</html>
