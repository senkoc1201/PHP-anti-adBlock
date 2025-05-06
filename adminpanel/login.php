<?php
include_once '../config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $apUsersTable = $tablePrefix . "users";
    $sql = "SELECT * FROM $apUsersTable WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        if (md5($password) === $result['password']) {
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}

$conn = null;
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeInsect | Admin System Log In</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/login.css" />
    

</head>

<body>
    <div class="container">
        <div class="login-box">
            <div class="login-title">ADMIN PANEL</div>
            <form action="login.php" method="POST">
                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <label class="form-control-label">USERNAME</label>
                    <input type="text" class="form-control" placeholder="Username" name="username" required>
                </div>
                <div class="form-group">
                    <label class="form-control-label">PASSWORD</label>
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
    </div>
</body>

</html>