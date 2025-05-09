<?php
include_once '../config.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$formSubmitted = false;
$apSettingsTable = $tablePrefix . "settings";
$checkTableQuery = "SHOW TABLES LIKE '$apSettingsTable'";
$tableExists = $conn->query($checkTableQuery)->rowCount() > 0;
$initialization = 1; // default to enabled
if ($tableExists) {
    $settingsQuery = "SELECT * FROM $apSettingsTable";
    $settingsResult = $conn->query($settingsQuery);
    $settingsRow = $settingsResult->fetch(PDO::FETCH_ASSOC);
    if (isset($settingsRow['initialization'])) {
        $initialization = $settingsRow['initialization'];
    }
}

$step = 'start';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Always re-check initialization value before installing
    $settingsQuery = "SELECT * FROM $apSettingsTable";
    $settingsResult = $conn->query($settingsQuery);
    $settingsRow = $settingsResult->fetch(PDO::FETCH_ASSOC);
    $currentInitialization = isset($settingsRow['initialization']) ? $settingsRow['initialization'] : 1;

    if ($tableExists && $currentInitialization == 2) {
        $errorMsg = "Installation is disabled by the administrator. Please contact the administrator if you need to reinstall.";
    } else {
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

            $step = 'completed';
        } catch(PDOException $e) {
            $errorMsg = "Connection failed: " . $e->getMessage();
        }
    }
}

// UI logic
$showInstaller = false;
if (!$tableExists) {
    $showInstaller = true;
} else if ($initialization == 1) {
    $showInstaller = true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Install | Ads-Script</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <style>
        body {
            min-height: 100vh;
            background: #232a36;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .install-card {
            background: #232a36;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.18);
            max-width: 420px;
            width: 100%;
            margin: 40px auto;
            padding: 36px 32px 32px 32px;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .install-title {
            font-family: 'Segoe UI', sans-serif;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 28px;
            letter-spacing: 1px;
            text-align: center;
        }
        .install-body {
            width: 100%;
            text-align: center;
        }
        .install-body h3 {
            font-size: 1.1rem;
            margin: 18px 0 10px 0;
            color: #e0e6ed;
            font-family: 'Segoe UI', sans-serif;
        }
        .install-footer {
            width: 100%;
            margin-top: 24px;
            text-align: center;
        }
        .btn-primary {
            background: linear-gradient(90deg, #2196f3 0%, #0d6efd 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 28px;
            font-size: 1rem;
            font-family: 'Segoe UI', sans-serif;
            cursor: pointer;
            transition: background 0.2s;
            font-weight: 500;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #0d6efd 0%, #2196f3 100%);
        }
        .checkbox {
            margin-top: 18px;
            margin-bottom: 18px;
            transform: scale(1.2);
        }
        .alert {
            margin: 18px 0 0 0;
            padding: 14px 18px;
            border-radius: 8px;
            font-size: 1rem;
            background: #ea7171e3;
            color: #fff;
        }
        .fa-check-circle {
            color: #28a745;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .success-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #fff;
        }
        .success-body {
            color: #e0e6ed;
            margin-bottom: 18px;
        }
        @media (max-width: 600px) {
            .install-card { max-width: 98vw; padding: 18px 4vw; }
        }
    </style>
</head>
<body>
    <div class="install-card">
        <?php if ($step === 'completed'): ?>
            <div class="fa fa-check-circle"></div>
            <div class="success-title">Installation Complete!</div>
            <div class="success-body">
                <div><i class="fa fa-user-circle"></i> Username: <b>admin</b></div>
                <div><i class="fa fa-user-circle"></i> Password: <b>admin</b></div>
                <p style="margin-top:18px;">Please login using these credentials. You can change the settings after logging in.</p>
            </div>
            <div class="install-footer">
                <a href="../adminpanel/login.php" class="btn-primary">Login</a>
            </div>
        <?php elseif ($showInstaller): ?>
            <div class="install-title">Welcome to Ads-Script Installer</div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" autocomplete="off" style="width:100%;">
                <div class="install-body">
                    <?php
                    if ($tableExists && $initialization == 1) {
                        if ($settingsResult || $settingsResult->rowCount() == 0) { ?>
                            <h3>
                                <i class="fa fa-user-circle"></i> The script is already installed.<br>
                                Please check the box and click Install to override.
                            </h3>
                            <input type="checkbox" id="checkbox" class="checkbox"> <label for="checkbox">Confirm Override</label>
                        <?php } else { ?>
                            <h3>
                                <i class="fa fa-user-circle"></i> Please click the install button to Install.
                            </h3>
                        <?php }
                    } else { ?>
                        <h3>
                            <i class="fa fa-user-circle"></i> Please click the install button to Install.
                        </h3>
                    <?php } ?>
                </div>
                <div class="install-footer">
                    <button type="submit" id="installButton" name="submit" class="btn-primary">Install</button>
                </div>
            </form>
        <?php else: ?>
            <div class="install-title">Installer Disabled</div>
            <div class="alert">
                <i class="fa fa-exclamation-triangle"></i> The installer is disabled by the administrator.<br>
                Please contact the administrator if you need to reinstall.<br>
            </div>
        <?php endif; ?>
        <?php if (isset($errorMsg)): ?>
            <div class="alert" style="margin-top:20px;">Error: <?php echo htmlspecialchars($errorMsg); ?></div>
        <?php endif; ?>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var checkbox = document.getElementById("checkbox");
        var installButton = document.getElementById("installButton");
        if (checkbox && installButton) {
            installButton.disabled = true;
            checkbox.addEventListener('change', function() {
                installButton.disabled = !this.checked;
            });
        }
    });
    </script>
</body>
</html> 