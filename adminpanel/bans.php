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
$apBansTable = $tablePrefix . "bans";

// Dynamic pagination
$query = "SELECT pagination FROM $apSettingsTable";
$result = $conn->query($query);
$settings = $result->fetch(PDO::FETCH_ASSOC);
$perPage = $settings;

// Pagination
if ($settings && isset($settings['pagination'])) {
    $perPage = intval($settings['pagination']);
}
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Delete data
if (isset($_POST['deleteselected']) && isset($_POST['checkbox']) && is_array($_POST['checkbox']) && !isset($_POST['deleteall'])) {
    foreach ($_POST['checkbox'] as $id) {
        $deleteQuery = "DELETE FROM $apBansTable WHERE id = :id";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->execute(['id' => $id]);
    }
    // Redirect after all deletions are complete
    header("Location: bans.php");
    exit();
}

// Whitelist with IP
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $get_data = "SELECT * FROM $apBansTable WHERE id = :id";
    $stmt = $conn->prepare($get_data);
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    //print_r($user);die();
    $status = $user['onWhitelist'];
    if ($status == 0) {
        $updateQuery = "UPDATE $apBansTable SET onWhitelist = 1 WHERE id = :id";
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute(['id' => $id]);
        header("Location: bans.php");
        exit();
    } else {
        $updateQuery = "UPDATE $apBansTable SET onWhitelist = 0 WHERE id = :id";
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute(['id' => $id]);
        header("Location: bans.php");
        exit();
    }
}

// Search
$query = "SELECT COUNT(*) as total FROM $apBansTable";
$selectquery = "SELECT * FROM $apBansTable";
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$filterStatus = isset($_GET['filter_status']) ? $_GET['filter_status'] : 'id';
$statusOptions = [
    'id' => 'By Date',
    'onWhitelist' => 'By Whitelisted IP'
];
if (!empty($searchTerm) && !empty($filterStatus)) {
    $query .= " WHERE ip_from LIKE '%$searchTerm%' OR ip_to LIKE '%$searchTerm%' ORDER BY $filterStatus DESC";
    $selectquery .= " WHERE ip_from LIKE '%$searchTerm%' OR ip_to LIKE '%$searchTerm%' ORDER BY $filterStatus DESC";
} elseif (!empty($searchTerm) && empty($filterStatus)) {
    $query .= " WHERE ip_from LIKE '%$searchTerm%' OR ip_to LIKE '%$searchTerm%' ORDER BY id DESC";
    $selectquery .= " WHERE ip_from LIKE '%$searchTerm%' OR ip_to LIKE '%$searchTerm%' ORDER BY id DESC";
} elseif (empty($searchTerm) && !empty($filterStatus)) {
    $query .= " ORDER BY $filterStatus DESC";
    $selectquery .= " ORDER BY $filterStatus DESC";
} else {
}

$totalResult = $conn->query($query);
$totalRecords = $totalResult->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalRecords / $perPage);
$offset = ($page - 1) * $perPage;

$query = "SELECT * FROM $apBansTable";
if (!empty($searchTerm)) {
    $query .= "WHERE ip_from LIKE '%$searchTerm%' OR ip_to LIKE '%$searchTerm%'";
}

$selectquery .= " LIMIT $perPage OFFSET $offset";
$result = $conn->query($selectquery);
$userData = $result->fetchAll(PDO::FETCH_ASSOC);

// Insert ip_from and ip_to query
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['ip_from']) && isset($_POST['ip_to'])) {
        $ip_from = $_POST['ip_from'];
        $ip_to = $_POST['ip_to'];
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        );
        try {
            $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbpassword, $options);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            die();
        }
        $sql = "INSERT INTO $apBansTable (ip_from, ip_to) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $ip_from);
        $stmt->bindParam(2, $ip_to);
        try {
            $stmt->execute();
            header("Location: bans.php");
            echo "Record inserted successfully";
            exit(); // It's good practice to exit after a header redirect
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $pdo = null;
    } else {
        echo "";
    }
}

// Delete all
if (isset($_POST['deleteall'])) {
    $deleteAllQuery = "TRUNCATE TABLE $apBansTable";
    $stmt = $conn->prepare($deleteAllQuery);
    $stmt->execute();
    header("Location: bans.php");
    exit();
}

$conn = null;
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permanent Blocks</title>
    <link rel="stylesheet" type="text/css" href="../css/admin.css" />
    <link rel="stylesheet" type="text/css" href="../css/bans.css" />
</head>

<body>
    <?php include "navbar.php"; ?>
    <div class="content-wrapper">
        <div>
            <div class="content-card">
                <div class="card-body" style="max-width: 500px; background: #1a2a30; border-radius: 10px; padding: 20px; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);     margin-top: 5rem; overflow: hidden; transition: all 0.3s ease-in-out;">
                    <form action="bans.php" method="POST" style="display: flex; margin-top: 3rem; flex-direction: column; gap: 20px;">
                        <div class="rows" style="display:flex;">
                            <!-- <div> -->
                                <label for="ip_from" style="font-size: 16px; font-weight: bold; color: #ccc; margin-bottom: 6px;">IP from or Single IP</label>
                                <input type="text" inputmode="text" pattern="^[a-f0-9.:]+$" minlength="5" maxlength="45" class="form-control" id="ip_from" name="ip_from" value="" style="width: 60%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; background-color: #2c3e50; color: white; font-size: 14px; transition: border-color 0.3s ease;">
                            <!-- </div> -->
                        </div>
                        <div class="rows" style="display:flex;">
                            <!-- <div> -->
                                <label for="ip_to" style="font-size: 16px; font-weight: bold; color: #ccc; margin-bottom: 6px;">IP To</label>
                                <input type="text" inputmode="text" pattern="^[a-f0-9.:]+$" minlength="5" maxlength="45" class="form-control" id="ip_to" name="ip_to" value="" style="width: 80%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; background-color: #2c3e50; color: white; font-size: 14px; transition: border-color 0.3s ease;">
                            <!-- </div> -->
                        </div>
                        <div class="box-footer" style="margin-top: 20px;">
                            <input type="submit" class="btn btn-primary" value="Submit" name="submit" style="padding: 12px; background-color: #0C86F4; color: white; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; transition: background-color 0.3s ease;">
                        </div>
                    </form>
                </div>
                <div class="card-body" style="max-width: 500px; background: #1a2a30; border-radius: 10px; padding: 20px; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);     margin-top: 5rem; overflow: hidden; transition: all 0.3s ease-in-out;">
                    <div style="width:100%; border: 0; padding: 0; text-align: center;">
                        <h3>Search By IP or Finger print</h3>
                        <form method='get' action="bans.php" id="searchForm">
                            <input type='text' name='search' placeholder="Search" value='<?php echo $searchTerm; ?>' style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; background-color: #2c3e50; color: white; font-size: 14px; transition: border-color 0.3s ease;">
                            <button class="btn btn-primary" type='submit' value='Submit'>Submit</button>
                            <input type="hidden" name="filter_status" id="hidden_search_term" value="<?php echo $filterStatus; ?>">
                        </form>
                    </div>
                    <div style="width:100%; border: 0; padding: 0; text-align: center;">
                        <h3>Sort by</h3>
                        <form method="get" action="bans.php">
                            <select id="filter_status" name="filter_status" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; background-color: #2c3e50; color: white; font-size: 14px;">
                                <?php foreach ($statusOptions as $key => $label): ?>
                                    <option value="<?php echo $key; ?>" <?php echo ($filterStatus === $key) ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="search" id="hidden_filter_status" value="<?php echo $searchTerm; ?>">
                            <button type="submit">Apply</button>
                        </form>
                    </div>
                </div>
            </div>
            <form action="" method="POST" id="form" style="margin-top: 2rem;">
                <div class="content-delete" style="display: flex; justify-content: right; gap: 10px; float: right;">
                    <div style="float: right; padding-bottom: 20px;">
                        <button type="submit" name="deleteselected" id="deleteselected" style="background: #0C86F4; color: white ; border: none;border-radius: 5px;padding: 8px;">Delete Selected</button>
                        <button type="submit" name="deleteall" id="deleteall" style="background: #0C86F4; color: white ; border: none; border-radius: 5px; padding: 8px;">Delete All</button>
                    </div>
                </div>
                <table class="table table-bordered" id="users-list" >
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="checkbox" name="deleteselected" id="check-all" value=""></th>
                            <th>IP From</th>
                            <th>Ip To</th>
                            <th>Whitelist</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($userData): ?>
                            <?php foreach ($userData as $user): ?>
                                <tr>
                                    <td><input type="checkbox" name="checkbox[]" class="checkbox" value="<?php echo $user['id']; ?>"></td>
                                    <td><?php echo $user['ip_from']; ?></td>
                                    <td><?php echo $user['ip_to']; ?></td>
                                    <td>
                                        <?php
                                        if ($user['onWhitelist'] == 1) {
                                            $checked = "checked";
                                        } else {
                                            $checked = "";
                                        }
                                        ?>
                                        <label class="switch">
                                            <input class="switch-input" type="checkbox" value="<?php echo $user['id']; ?>" <?php echo $checked; ?> />
                                            <span class="switch-label"></span>
                                            <span class="switch-handle"></span>
                                        </label>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </form>
            <div class="pagination">
                <?php
                // Display pagination links
                for ($i = 1; $i <= $totalPages; $i++) {
                    if ($page == $i) {
                        echo '<a class="active" style="text-align: center;" href="bans.php?page=' . $i . '">' . $i . '</a>';
                    } else {
                        echo "<a href='bans.php?page=$i'>$i</a>";
                    }
                }
                ?>
            </div>
        </div>
        
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var checkAll = document.getElementById('check-all');
            checkAll.addEventListener('change', function() {
                var checkboxes = document.querySelectorAll('input[name="delete"]');
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = checkAll.checked;
                });
            });
        });
    </script>
    <script>
        document.getElementById("deleteselected").addEventListener("click", function() {
            var selected = document.querySelectorAll('input[type="checkbox"]:checked');
            if (selected.length === 0) {
                alert("Please select items to delete.");
            } else {
                if (confirm("Are you sure you want to delete the selected items?")) {
                    // Code to perform deletion
                } else {
                    // Cancelled
                    event.preventDefault();
                    alert("Deletion cancelled.");
                }
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkAllCheckbox = document.getElementById("check-all");
            const checkboxes = document.querySelectorAll('input[name="checkbox[]"]');
            checkAllCheckbox.addEventListener("change", function() {
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = checkAllCheckbox.checked;
                });
            });
        });
    </script>
    <script>
        document.getElementById("deleteall").addEventListener("click", function(event) {
            if (confirm("Are you sure you want to delete all data?")) {
                document.querySelector('form').submit();
            } else {
                // Cancelled
                event.preventDefault();
                alert("Deletion cancelled.");
            }
        });
    </script>
    <script>
        document.querySelectorAll('.switch-input').forEach(function(checkbox) {
            checkbox.addEventListener('click', function() {
                var id = this.value; // Extract the ID from the checkbox
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'bans.php?id=' + id, true); // Send the ban_id as a query parameter
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var result = JSON.parse(xhr.responseText);
                        console.log(result.abc);
                    }
                };
                xhr.send();
            });
        });
    </script>
</body>

</html>