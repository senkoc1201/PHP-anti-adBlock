<?php
include_once '../config.php';

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$profile_settings = $tablePrefix . "users";

if (isset($_POST['update_profile'])) {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $query = "UPDATE $profile_settings SET username = :username, email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
}

if (isset($_POST['update_password'])) {
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    if ($password !== $confirm_password) {
        echo '<div class="alert" style="background-color: red; color: white; padding: 5px; text-align: center;">Passwords do not match!</div>';
    } else {
        $hashed_password = md5($password);
        $query = "UPDATE $profile_settings SET password = :password";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
        echo '<div class="alert" style="background-color: green; color: white; padding: 5px; text-align: center;">Password updated successfully</div>';
    }
}

$query = "SELECT * FROM $profile_settings";
$result = $conn->query($query);
$data = $result->fetch(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Details</title>
    <link rel="stylesheet" type="text/css" href="../css/profile.css" />
</head>

<body>
    <?php include "navbar.php"; ?>
    <div class="content-wrapper">
        <div class="container">
            <h2 class="card-title">Login Details</h2>
            <br>
            <div class="card-body">
                <form action="" method="POST">
                    <div style="padding: 10px; display: flex; gap: 50px;">
                        <label style="font-size: 18px;" for="username">Username: </label>
                        <input style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; background-color: #2c3e50; color: white; font-size: 14px; transition: border-color 0.3s ease;" type="text" min="3" max="99" autocomplete="off" id="username" name="username" value="<?php echo isset($data['username']) ? $data['username'] : ''; ?>">
                    </div>
                    <div style="padding: 10px; display: flex; gap: 50px">
                        <label style="font-size: 18px;" for="email">Email: </label>
                        <input style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; background-color: #2c3e50; color: white; font-size: 14px; transition: border-color 0.3s ease;" type="text" min="3" max="99" autocomplete="off" id="email" name="email" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>">
                    </div>
                    <button type="submit" name="update_profile" value="submit" class="button">Submit</button>
                </form>
            </div>
            <div class="card-body" style="border-top: 1px solid black;">
                <form action="" method="POST">
                    <div style="padding: 10px; display: flex;gap: 50px;">
                        <label style="font-size: 18px;" for="password">Password: </label>
                        <input style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; background-color: #2c3e50; color: white; font-size: 14px; transition: border-color 0.3s ease;" type="password" min="3" max="99" id="password" name="password" value="">
                    </div>
                    <div style="padding: 10px; display: flex; gap: 21px">
                        <label style="font-size: 18px;" for="confirm_password">Confirm Password: </label>
                        <input style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; background-color: #2c3e50; color: white; font-size: 14px; transition: border-color 0.3s ease;" type="password" id="confirm_password" name="confirm_password" value="">
                    </div>
                    <button type="submit" name="update_password" value="submit" class="button">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Hide success and error messages after 5 seconds
        setTimeout(function() {
            var alertElement = document.querySelector('.alert');
            if (alertElement) {
                alertElement.style.transition = 'opacity 1s';
                alertElement.style.opacity = 0;
                setTimeout(function() {
                    alertElement.style.display = 'none';
                }, 1000);
            }
        }, 5000);
    </script>
</body>

</html>