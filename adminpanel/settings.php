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

$apSettingsTable = $tablePrefix . "settings";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gather form data
    $limit_number = $_POST['limit_number'];
    $time_frame_days = $_POST['time_frame_days'];
    $time_frame_hours = $_POST['time_frame_hours'];
    $time_frame_minutes = $_POST['time_frame_minutes'];
    $time_frame_seconds = $_POST['time_frame_seconds'];
    $blocked_days = $_POST['blocked_days'];
    $blocked_hours = $_POST['blocked_hours'];
    $blocked_minutes = $_POST['blocked_minutes'];
    $blocked_seconds = $_POST['blocked_seconds'];
    $initialization = $_POST['initialization'];
    $pagination = $_POST['pagination'];
    $ip_fingerprint = $_POST['ip_fingerprint'];
    $blocked_type = $_POST['blocked_type'];

    // Update query
    $sql = "UPDATE $apSettingsTable SET
                limit_number = :limit_number,
                time_frame_days = :time_frame_days,
                time_frame_hours = :time_frame_hours,
                time_frame_minutes = :time_frame_minutes,
                time_frame_seconds = :time_frame_seconds,
                blocked_days = :blocked_days,
                blocked_hours = :blocked_hours,
                blocked_minutes = :blocked_minutes,
                blocked_seconds = :blocked_seconds,
                initialization = :initialization,
                pagination = :pagination,
                ip_fingerprint = :ip_fingerprint,
                blocked_type = :blocked_type
            WHERE id = 1";
    $stmt = $conn->prepare($sql);
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
    $stmt->bindParam(':pagination', $pagination);
    $stmt->bindParam(':ip_fingerprint', $ip_fingerprint);
    $stmt->bindParam(':blocked_type', $blocked_type);
    if ($stmt->execute()) {
        echo '<div class="alert" style="background-color: green; color: white; padding: 5px; text-align: center;">Settings updated successfully</div>';
    } else {
        echo '<div class="alert" style="background-color: skyblue; color: white; padding: 5px; text-align: center;">Error updating Settings: ' . $stmt->errorInfo()[2] . '</div>';
    }
}

// Fetch settings data
$query = "SELECT * FROM $apSettingsTable";
$result = $conn->query($query);
$userData = $result->fetch(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" type="text/css" href="../css/settings.css" />
</head>

<body>
    <?php include "navbar.php"; ?>
    <div class="content-setting">
        <div class="container">
            <div class="card-body">
                <h2 class="card-title">Settings</h2>
                <hr style="border-bottom: thin;">
                <br>
                <form action="" method="POST">
                    <div class="rows">
                        <div>
                            <h4>Ads Unit Click Limit:</h4>
                            <input type="number" min="1" class="form-control" id="limit_number" name="limit_number" value="<?= $userData['limit_number'] ?>">
                        </div>
                    </div>
                    <div class="rows" style="padding-top: 20px">
                        <h4>Add Unit Time Frame</h4><br>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="time_frame_days">Days : </label>
                            <div class="col-sm-10">
                                <select name="time_frame_days" id="time_frame_days" value="<?= $userData['time_frame_days'] ?>">
                                    <?php
                                    for ($i = 0; $i <= 31; $i++) {
                                        if ($i == $userData['time_frame_days']) {
                                            echo '<option selected value="' . $i . '">' . $i . '</option>';
                                        } else {
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="time_frame_hours">Hours : </label>
                            <div class="col-sm-10">
                                <select name="time_frame_hours" id="time_frame_hours" value="<?= $userData['time_frame_hours'] ?>">
                                    <?php
                                    for ($i = 0; $i <= 24; $i++) {
                                        if ($i == $userData['time_frame_hours']) {
                                            echo '<option selected value="' . $i . '">' . $i . '</option>';
                                        } else {
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="time_frame_minutes">Minutes : </label>
                            <div class="col-sm-10">
                                <select name="time_frame_minutes" id="time_frame_minutes" value="<?= $userData['time_frame_minutes'] ?>">
                                    <?php
                                    for ($i = 0; $i <= 60; $i++) {
                                        if ($i == $userData['time_frame_minutes']) {
                                            echo '<option selected value="' . $i . '">' . $i . '</option>';
                                        } else {
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="time_frame_seconds">Seconds : </label>
                            <div class="col-sm-10">
                                <select name="time_frame_seconds" id="time_frame_seconds" value="<?= $userData['time_frame_seconds'] ?>">
                                    <?php
                                    for ($i = 0; $i <= 60; $i++) {
                                        if ($i == $userData['time_frame_seconds']) {
                                            echo '<option selected value="' . $i . '">' . $i . '</option>';
                                        } else {
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="rows">
                            <h4>Block Duration</h4><br>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="blocked_days">Days : </label>
                                <div class="col-sm-10">
                                    <select name="blocked_days" id="blocked_days" value="<?= $userData['blocked_days'] ?>">
                                        <?php
                                        for ($i = 0; $i <= 31; $i++) {
                                            if ($i == $userData['blocked_days']) {
                                                echo '<option selected value="' . $i . '">' . $i . '</option>';
                                            } else {
                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="blocked_hours">Hours : </label>
                                <div class="col-sm-10">
                                    <select name="blocked_hours" id="blocked_hours" value="<?= $userData['blocked_hours'] ?>">
                                        <?php
                                        for ($i = 0; $i <= 24; $i++) {
                                            if ($i == $userData['blocked_hours']) {
                                                echo '<option selected value="' . $i . '">' . $i . '</option>';
                                            } else {
                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="blocked_minutes">Minutes : </label>
                                <div class="col-sm-10">
                                    <select name="blocked_minutes" id="blocked_minutes" value="<?= $userData['blocked_minutes'] ?>">
                                        <?php
                                        for ($i = 0; $i <= 60; $i++) {
                                            if ($i == $userData['blocked_minutes']) {
                                                echo '<option selected value="' . $i . '">' . $i . '</option>';
                                            } else {
                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="blocked_seconds">Seconds : </label>
                                <div class="col-sm-10">
                                    <select name="blocked_seconds" id="blocked_seconds" value="<?= $userData['blocked_seconds'] ?>">
                                        <?php
                                        for ($i = 0; $i <= 60; $i++) {
                                            if ($i == $userData['blocked_seconds']) {
                                                echo '<option selected value="' . $i . '">' . $i . '</option>';
                                            } else {
                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="form-group row" style="gap: 147px; padding-left: 15px;">
                                <h4>Initialize</h4>
                                <div class="col-sm-10">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label" for="on">On</label>
                                        <input class="form-check-input" type="radio" id="on" name="initialization" value="1" <?php if ($userData['initialization'] == 1) echo "checked"; ?>>
                                    </div>
                                    <div class="form-check form-switch">
                                        <label class="form-check-label" for="off">Off</label>
                                        <input class="form-check-input" type="radio" id="off" name="initialization" value="2" <?php if ($userData['initialization'] == 2) echo "checked"; ?>>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="rows" style="display: flex; gap: 70px; padding-bottom: 20px;padding-left: 18px;">
                                <h4>Pagination</h4><br>
                                <select name="pagination" id="pagination">
                                    <option value="10" <?php if ($userData['pagination'] == 10) echo 'selected'; ?>>10</option>
                                    <option value="25" <?php if ($userData['pagination'] == 25) echo 'selected'; ?>>25</option>
                                    <option value="50" <?php if ($userData['pagination'] == 50) echo 'selected'; ?>>50</option>
                                    <option value="100" <?php if ($userData['pagination'] == 100) echo 'selected'; ?>>100</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="form-group row" style="gap: 125px; padding-left: 18px;">
                            <h4>IP or fingerprint</h4>
                            <div class="col-sm-10">
                                <div class="form-check form-switch">
                                    <label class="form-check-label" for="ip">IP Addreess</label>
                                    <input class="form-check-input" type="radio" id="ip" name="ip_fingerprint" value="1" <?php if ($userData['ip_fingerprint'] == 1) echo "checked"; ?>>
                                </div>
                                <div class="form-check form-switch">
                                    <label class="form-check-label" for="fingerprint">Fingerprint</label>
                                    <input class="form-check-input" type="radio" id="fingerprint" name="ip_fingerprint" value="2" <?php if ($userData['ip_fingerprint'] == 2) echo "checked"; ?>>
                                </div>
                                <div class="form-check form-switch">
                                    <label class="form-check-label" for="both">Are Both</label>
                                    <input class="form-check-input" type="radio" id="both" name="ip_fingerprint" value="3" <?php if ($userData['ip_fingerprint'] == 3) echo "checked"; ?>>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group row" style="gap: 140px; padding-left: 20px;">
                            <h4>Blocked Type</h4>
                            <div class="col-sm-10">
                                <div class="form-check form-switch">
                                    <label class="form-check-label" for="single">Single Ads Unit</label>
                                    <input class="form-check-input" type="radio" id="single" name="blocked_type" value="1" <?php if ($userData['blocked_type'] == 1) echo "checked"; ?>>
                                </div>
                                <div class="form-check form-switch">
                                    <label class="form-check-label" for="all">All Ads unit</label>
                                    <input class="form-check-input" type="radio" id="all" name="blocked_type" value="2" <?php if ($userData['blocked_type'] == 2) echo "checked"; ?>>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="box-footer" style="padding: 0px 0px 20px 17px">
                            <button type="submit" value="Submit" name="submit" class="btn btn-primary" style="background: #0C86F4; color: white; border: none; border-radius: 5px;">Submit</button>
                        </div>
                </form>
            </div>
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