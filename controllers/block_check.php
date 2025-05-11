<?php
include_once '../config.php';

$apSettingsTable = $tablePrefix . "settings";
$apBansTable = $tablePrefix . "bans";
$adBlocksTable = $tablePrefix . "blocks";
$adclicksTable = $tablePrefix . "clicks";

$settingsQuery = "SELECT * FROM $apSettingsTable";
$settingsResult = $conn->query($settingsQuery);
$settingsRow = $settingsResult->fetch(PDO::FETCH_ASSOC);

$json = file_get_contents('php://input');
$data = (array)json_decode($json);

// Remove time-blocked Ad
$blockDuration = $settingsRow['blocked_days'] * 24 * 60 * 60 + $settingsRow['blocked_hours'] * 60 * 60 + $settingsRow['blocked_minutes'] * 60 + $settingsRow['blocked_seconds'];
$blockUntil = time() - $blockDuration;
$deleteQuery = "DELETE FROM $adBlocksTable WHERE block_untill <= :blockUntil";
$deleteStmt = $conn->prepare($deleteQuery);
$formattedDate = date('Y-m-d H:i:s', $blockUntil);
$deleteStmt->bindParam(':blockUntil', $formattedDate);
$deleteStmt->execute();

// Ad bans check (IP Range)
$iprBansCheckQuery = "SELECT * FROM $apBansTable WHERE (INET_ATON('" . $data['ip_address'] . "') BETWEEN INET_ATON(ip_from) AND INET_ATON(ip_to))";
$result = $conn->query($iprBansCheckQuery);
$getdata = $result->fetchAll(PDO::FETCH_ASSOC);
if (!empty($getdata[0]) && $getdata[0]['onWhitelist'] == 0) {
    if ($settingsRow['blocked_type'] == 1) {
                echo json_encode(['status' => 'blocked', 'id' => $data['ad_unit_id']]);
           } else {
               echo json_encode(['status' => 'blockedall', 'id' => $data['ad_unit_id']]);
           }
            die();
}
// Ad bans check (Single IP)
$sipBansCheckQuery = "SELECT COUNT(*) as block_count FROM $apBansTable WHERE ip_from = '{$data['ip_address']}' AND onWhitelist = 0";
$result = $conn->query($sipBansCheckQuery);
$getdata = $result->fetchAll(PDO::FETCH_ASSOC);
if (!empty($getdata[0]['block_count']) &&  $getdata[0]['block_count'] > 0) {
    if ($settingsRow['blocked_type'] == 1) {
        echo json_encode(['status' => 'blocked', 'id' => $data['ad_unit_id']]);
    } else {
        echo json_encode(['status' => 'blockedall', 'id' => $data['ad_unit_id']]);
    }
    die();
}

// Ad blocks check
if ($settingsRow['ip_fingerprint'] == 1) {
    $adBlocksCheckQuery = "SELECT COUNT(*) as block_count FROM $adBlocksTable WHERE ip_address = '{$data['ip_address']}' AND ad_unit_id = '{$data['ad_unit_id']}' AND onWhitelist = 0 AND block_untill > '" . date('Y-m-d') . "'";
} elseif ($settingsRow['ip_fingerprint'] == 2) {
    $adBlocksCheckQuery = "SELECT COUNT(*) as block_count FROM $adBlocksTable WHERE fingerprint = '{$data['fingerprint']}' AND ad_unit_id = '{$data['ad_unit_id']}' AND onWhitelist = 0 AND block_untill > '" . date('Y-m-d') . "'";
} else {
    $adBlocksCheckQuery = "SELECT COUNT(*) as block_count FROM $adBlocksTable WHERE ip_address = '{$data['ip_address']}' AND fingerprint = '{$data['fingerprint']}' AND ad_unit_id = '{$data['ad_unit_id']}' AND onWhitelist = 0 AND block_untill > '" . date('Y-m-d') . "'";
}
$result = $conn->query($adBlocksCheckQuery);
$getdata = $result->fetchAll(PDO::FETCH_ASSOC);
if (!empty($getdata[0]) && $getdata[0]['block_count'] > 0) {
    if ($settingsRow['blocked_type'] == 1) {
        echo json_encode(['status' => 'blocked', 'id' => $data['ad_unit_id']]);
    } else {
        echo json_encode(['status' => 'blockedall', 'id' => $data['ad_unit_id']]);
    }
    die();
}

// Time frame calculation
$currentTime = date('Y-m-d H:i:s');
$meetingTime = date('Y-m-d H:i:s', time() - ($settingsRow['time_frame_days'] * 24 * 3600 + $settingsRow['time_frame_hours'] * 3600 + $settingsRow['time_frame_minutes'] * 60 + $settingsRow['time_frame_seconds']));
// Ad clicks count query
$adClicksCountQuery = "SELECT COUNT(*) as click_count FROM $adclicksTable WHERE ip_address = '{$data['ip_address']}' AND fingerprint = '{$data['fingerprint']}' AND ad_unit_id = '{$data['ad_unit_id']}' AND click_time >= '{$meetingTime}' AND click_time <= '{$currentTime}'";
$result = $conn->query($adClicksCountQuery);
$adClicksCountRow = $result->fetchAll(PDO::FETCH_ASSOC);
if ($adClicksCountRow[0]['click_count'] >= $settingsRow['limit_number']) {
    $blockDuration = $settingsRow['blocked_days'] * 24 * 60 * 60 + $settingsRow['blocked_hours'] * 60 * 60 + $settingsRow['blocked_minutes'] * 60 + $settingsRow['blocked_seconds'];
    $blockUntil = time() + $blockDuration;
    $insertData = [
        'ip_address'   => $data['ip_address'],
        'fingerprint'  => $data['fingerprint'],
        'ad_unit_id'   => $data['ad_unit_id'],
        'block_untill' => date('Y-m-d H:i:s', $blockUntil)
    ];
    // Insert into blocks table
    $insertQuery = "INSERT INTO $adBlocksTable (ip_address, fingerprint, ad_unit_id, block_untill) VALUES ('{$insertData['ip_address']}', '{$insertData['fingerprint']}', '{$insertData['ad_unit_id']}', '{$insertData['block_untill']}')";
    $stmt = $conn->prepare($insertQuery);
    $stmt->execute();
    if ($settingsRow['blocked_type'] == 1) {
        echo json_encode(['status' => 'blocked', 'id' => $data['ad_unit_id']]);
    } else {
        echo json_encode(['status' => 'blockedall', 'id' => $data['ad_unit_id']]);
    }
} else {
    echo $adClicksCountRow[0]['click_count'];
}
?>
