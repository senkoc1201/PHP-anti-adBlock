<?php
include_once '../config.php';

// session_start();
// if (!isset($_SESSION['username'])) {
//     header("Location: login.php");
//     exit();
// }

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$apSettingsTable = $tablePrefix . "settings";
$adBlocksTable = $tablePrefix . "blocks";
$adclicktable = $tablePrefix . "clicks";

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
        $query = "SELECT * FROM $adBlocksTable WHERE id =" . $id;
        $result = $conn->query($query);
        $settings = $result->fetch(PDO::FETCH_ASSOC);
        $ip_address = $settings['ip_address'];
        $deleteQuery = "DELETE FROM $adBlocksTable WHERE ip_address = :ip_address";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->execute(['ip_address' => $ip_address]);
        $deleteQuery = "DELETE FROM $adclicktable WHERE ip_address = :ip_address";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->execute([
            'ip_address' => $ip_address
        ]);
        header("Location: index.php");
        exit();
    }
}

// Whitelist with IP
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM $adBlocksTable WHERE id =" . $id;
    $result = $conn->query($query);
    $settings = $result->fetch(PDO::FETCH_ASSOC);
    // print_r( $settings);die();
    $ip_address = $settings['ip_address'];
    $status = $settings['onWhitelist'];
    if ($status == 0) {
        $updateQuery = "UPDATE $adBlocksTable SET onWhitelist = 1 WHERE ip_address = :ip_address";
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute([
            'ip_address' => $ip_address
        ]);
        $deleteQuery = "DELETE FROM $adclicktable WHERE ip_address = :ip_address";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->execute([
            'ip_address' => $ip_address
        ]);
        header("Location: index.php");
        exit();
    } else {
        $updateQuery = "UPDATE $adBlocksTable SET onWhitelist = 0 WHERE ip_address = :ip_address";
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute([
            'ip_address' => $ip_address
        ]);
        $deleteQuery = "DELETE FROM $adclicktable WHERE ip_address = :ip_address";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->execute([
            'ip_address' => $ip_address
        ]);
        header("Location: index.php");
        exit();
    }
}

// Search
$query = "SELECT COUNT(*) as total FROM $adBlocksTable";
$selectquery = "SELECT * FROM $adBlocksTable";
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$filterStatus = isset($_GET['filter_status']) ? $_GET['filter_status'] : 'id';
$statusOptions = [
    'id' => 'By Date',
    'onWhitelist' => 'By Whitelisted IP'
];
if (!empty($searchTerm) && !empty($filterStatus)) {
    $query .= " WHERE ip_address LIKE '%$searchTerm%' OR fingerprint LIKE '%$searchTerm%' ORDER BY $filterStatus DESC";
    $selectquery .= " WHERE ip_address LIKE '%$searchTerm%' OR fingerprint LIKE '%$searchTerm%' ORDER BY $filterStatus DESC";
} elseif (!empty($searchTerm) && empty($filterStatus)) {
    $query .= " WHERE ip_address LIKE '%$searchTerm%' OR fingerprint LIKE '%$searchTerm%' ORDER BY id DESC";
    $selectquery .=  " WHERE ip_address LIKE '%$searchTerm%' OR fingerprint LIKE '%$searchTerm%' ORDER BY id DESC";
} elseif (empty($searchTerm) && !empty($filterStatus)) {
    $query .= " ORDER BY $filterStatus DESC";
    $selectquery .= " ORDER BY $filterStatus DESC";
} else {
}

$totalResult = $conn->query($query);
$totalRecords = $totalResult->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalRecords / $perPage);
$offset = ($page - 1) * $perPage;
$selectquery .= " LIMIT $perPage OFFSET $offset";
$result = $conn->query($selectquery);
$userData = $result->fetchAll(PDO::FETCH_ASSOC);

// Delete all
if (isset($_POST['deleteall'])) {
    $deleteAllQuery = "TRUNCATE TABLE $adBlocksTable";
    $stmt = $conn->prepare($deleteAllQuery);
    $stmt->execute();
    $deleteAllQuery = "TRUNCATE TABLE $adclicktable";
    $stmt = $conn->prepare($deleteAllQuery);
    $stmt->execute();
    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temporary Blocks</title>
    <link rel="stylesheet" type="text/css" href="../css/index.css" />
    <link rel="stylesheet" type="text/css" href="../css/admin.css" />
</head>

<body>
<?php include 'navbar.php'; ?>
    <div class="content-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:50%; border: 0; padding: 0; text-align: center; border-radius: 3rem;">
                        <h3>Search By IP or Finger print</h3>
                        <form method='get' action="index.php" id="searchForm">
                            <input type='text' placeholder="Search" name='search' value='<?php echo $searchTerm; ?>' style="padding: 12px; border: 1px solid #ccc; border-radius: 6px; background-color: #2c3e50; color: white; font-size: 14px; transition: border-color 0.3s ease;">
                            <input type='submit' value='Submit'>
                            <input type="hidden" name="filter_status" id="hidden_search_term" value="<?php echo $filterStatus; ?>">
                        </form>
                    </th>
                    <th style="width:50%; border: 0; padding: 0; text-align: center; border-radius: 3rem;">
                        <h3>Sort by</h3>
                        <form method="get" action="index.php">
                            <select id="filter_status" name="filter_status" style="padding: 12px; border: 1px solid #ccc; border-radius: 6px; background-color: #2c3e50; color: white; font-size: 14px;">
                                <?php foreach ($statusOptions as $key => $label): ?>
                                    <option value="<?php echo $key; ?>" <?php echo ($filterStatus === $key) ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="search" id="hidden_filter_status" value="<?php echo $searchTerm; ?>">
                            <button type="submit">Apply</button>
                        </form>
                    </th>
                </tr>
            </thead>
        </table>
        <form method="post">
            <div style="display: flex;justify-content: center; gap: 10px; float: right;">
                <div style="float: right; padding-bottom: 20px;">
                    <button type="submit" name="deleteselected" id="deleteselected" style="background: #0C86F4; color: white ; border: none;border-radius: 5px;padding: 8px;">Delete Selected</button>
                    <button type="submit" name="deleteall" id="deleteall" style="background: #0C86F4; color: white; border: none; border-radius: 5px; padding: 8px;">Delete All</button>
                </div>
            </div>
            <table class="table table-bordered" id="users-list">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="checkbox" name="deleteselected" id="check-all" value=""></th>
                        <th>IP Address</th>
                        <th>Finger print</th>
                        <th>Ad Unit ID</th>
                        <th>Block Until</th>
                        <th>Whitelist</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userData as $row) { ?>
                        <tr>
                            <td><input type="checkbox" name="checkbox[]" value="<?php echo $row['id']; ?>"></td>
                            <td><?php echo $row['ip_address']; ?></td>
                            <td><?php echo $row['fingerprint']; ?></td>
                            <td><?php echo $row['ad_unit_id']; ?></td>
                            <td><?php echo $row['block_untill']; ?></td>
                            <td>
                                <?php
                                if ($row['onWhitelist'] == 1) {
                                    $checked = "checked";
                                } else {
                                    $checked = "";
                                }
                                ?>
                                <label class="switch">
                                    <input class="switch-input" type="checkbox" value="<?php echo $row['id']; ?>" <?php echo $checked; ?> />
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </form>
        <div class="pagination">
            <?php
            // Display pagination links
            for ($i = 1; $i <= $totalPages; $i++) {
                if ($page == $i) {
                    echo "<a class='active' href='index.php?page=$i'>$i</a>";
                } else {
                    echo "<a href='index.php?page=$i'>$i</a>";
                }
            }
            ?>
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
                xhr.open('POST', 'index.php?id=' + id, true); // Send the ban_id as a query parameter
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