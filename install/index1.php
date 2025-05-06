<?php
include_once '../../config.php';

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
    <title>Welcome to Page</title>  
    <link rel="stylesheet" type="text/css" href="../css/style_two.css"/>
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 90vh;">
    <div class="card">
        <div class="card-header text-center" style="background: #a0979726; width: 700px;">
            <h1 class="card-title" style="font-family: sans-serif;">
            <h1 class="card-title">
                <i class="fa fa-user-circle"></i> Welcome to Ads-Script
            </h1>
        </div>
        <div class="card-body" style=" text-align: center;">
            <h3 class="card-text" style="font-family: sans-serif;">
                <i class="fa fa-user-circle"></i> To install the script, please click the install button.
            </h3>            
        </div>
        <?php
    if ($data['initialization'] == 1) {
        echo '<div class="card-footer text-center" style="background: #a0979726;height: 20px;">
                  <!-- Add an onclick event to open the install-page in a popup -->
                  <a href="welcome.php" class="btn btn-primary" style="background-color: #0d6efd;color: white; border: none; border-radius: 9px; padding: 5px 15px 5px 15px;font-family: sans-serif;">Install</a>
              </div>';
    } else {
        echo '<div class="card-footer text-center" style="background: #a0979726;">
                  <!-- Add an onclick event to open the install-page in a popup -->
                  <a href="finish_initialization.php" class="btn btn-primary" style="background-color: #0d6efd;color: white; border: none; border-radius: 9px;padding: 5px 15px 5px 15px;font-family: sans-serif;">Install</a>
              </div>';
    }
?>
</div>
</body>
</html>
