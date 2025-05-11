<?php
include_once '../config.php';

$tableExists = false;
try {
    $apSettingsTable = $tablePrefix . "settings";
    $sql_check_table = "SHOW TABLES LIKE '$apSettingsTable'";
    $result_check_table = $conn->query($sql_check_table);
    $tableExists = $result_check_table->rowCount() > 0;
} catch (PDOException $e) {
    
    echo "Error: " . $e->getMessage();
}

if (!$tableExists) {
    header("Location: welcome.php");
    exit(); 
}

$apSettingsTable = $tablePrefix . "settings";
$sql = "SELECT initialization FROM $apSettingsTable";
$result = $conn->query($sql);
$data = $result->fetch(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Complete | Ads-Script</title>
    <link rel="stylesheet" type="text/css" href="../css/style_two.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <style>
        body { background: #f6f8fa; }
        .install-card {
            max-width: 420px;
            margin: 40px auto;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            background: #fff;
            overflow: hidden;
        }
        .install-header {
            background: #0d6efd;
            color: #fff;
            padding: 32px 24px 16px 24px;
            text-align: center;
        }
        .install-header .fa-check-circle {
            font-size: 3rem;
            color: #28a745;
            margin-bottom: 10px;
        }
        .install-title {
            font-family: 'Segoe UI', sans-serif;
            font-size: 2rem;
            margin-bottom: 0;
        }
        .install-body {
            padding: 32px 24px 24px 24px;
            text-align: center;
        }
        .install-body h3 {
            font-family: 'Segoe UI', sans-serif;
            font-size: 1.1rem;
            margin: 18px 0 10px 0;
            color: #333;
        }
        .install-footer {
            background: #f6f8fa;
            padding: 18px 24px;
            text-align: center;
        }
        .btn-primary {
            background-color: #0d6efd;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 28px;
            font-size: 1rem;
            font-family: 'Segoe UI', sans-serif;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
        }
        @media (max-width: 600px) {
            .install-card { max-width: 98vw; }
            .install-header, .install-body, .install-footer { padding-left: 8px; padding-right: 8px; }
        }
    </style>
</head>
<body>
    <div class="install-card">
        <div class="install-header">
            <i class="fa fa-check-circle"></i>
            <div class="install-title">Installation Complete!</div>
        </div>
        <div class="install-body">
            <h3><i class="fa fa-user-circle"></i> Username: <b>admin</b></h3>
            <h3><i class="fa fa-user-circle"></i> Password: <b>admin</b></h3>
            <p style="margin-top:18px;">Please login using these credentials. You can change the settings after logging in.</p>
        </div>
        <div class="install-footer">
            <a href="../adminpanel/login.php" class="btn-primary">Login</a>
        </div>
    </div>
</body>
</html>
