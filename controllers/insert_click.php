<?php
include_once '../config.php';

$apSettingsTable = $tablePrefix . "settings";
$apBansTable = $tablePrefix . "bans";
$adBlocksTable = $tablePrefix . "blocks";
$adClicksTable = $tablePrefix . "clicks";

$settingsQuery = "SELECT * FROM $apSettingsTable";
$settingsResult = $conn->query($settingsQuery);
$settingsRow = $settingsResult->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    if (isset($data->ip_address) && isset($data->fingerprint) && isset($data->ad_unit_id)) {
        $ip_address = $data->ip_address;
        $fingerprint = $data->fingerprint;
        $ad_unit_id = $data->ad_unit_id;

        // Delete old Ad clicks
        $currentTime = time();
        $deleteTime = $currentTime - ($settingsRow['time_frame_days'] * 24 * 3600 + $settingsRow['time_frame_hours'] * 3600 + $settingsRow['time_frame_minutes'] * 60 + $settingsRow['time_frame_seconds']);
        $deleteTimeFormatted = date('Y-m-d H:i:s', $deleteTime);
        $deleteQuery = "DELETE FROM $adClicksTable WHERE click_time <= :delete_time";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bindParam(':delete_time',  $deleteTimeFormatted);
        $deleteStmt->execute();

        // Insert new Ad click
        $insertQuery = "INSERT INTO $adClicksTable (ip_address, fingerprint, ad_unit_id, click_time) VALUES (:ip_address, :fingerprint, :ad_unit_id, NOW())";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bindParam(':ip_address', $ip_address);
        $insertStmt->bindParam(':fingerprint', $fingerprint);
        $insertStmt->bindParam(':ad_unit_id', $ad_unit_id);
        // Execute the insert query
        try {
            $insertStmt->execute();
            //echo json_encode(array("status" => "success", "message" => "Ad click was successful"));
        } catch(PDOException $e) {
            //echo json_encode(array("status" => "error", "message" => "Ad click failed: " . $e->getMessage()));
        }

        // Check if the user is banned
        $ipCheckQuery = "SELECT * FROM $apBansTable WHERE :ip_address BETWEEN ip_from AND ip_to AND onWhitelist = 0";
        $ipCheckStmt = $conn->prepare($ipCheckQuery);
        $ipCheckStmt->bindParam(':ip_address', $ip_address);
        $ipCheckStmt->execute();
        $ipCheckResult = $ipCheckStmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($ipCheckResult)) {
            if ($settingsRow['blocked_type'] == 1) {
                echo json_encode(['status' => 'blocked', 'id' => $ad_unit_id]);
            } else {
                echo json_encode(['status' => 'blockedall', 'id' => $ad_unit_id]);
            }
            die();
        }

        // Check if the user has exceeded click limit
        $clickCountQuery = "SELECT COUNT(*) as click_count FROM $adClicksTable WHERE ip_address = :ip_address AND fingerprint = :fingerprint AND ad_unit_id = :ad_unit_id AND click_time >= :delete_time";
        $clickCountStmt = $conn->prepare($clickCountQuery);
        $clickCountStmt->bindParam(':ip_address', $ip_address);
        $clickCountStmt->bindParam(':fingerprint', $fingerprint);
        $clickCountStmt->bindParam(':ad_unit_id', $ad_unit_id);
        $clickCountStmt->bindParam(':delete_time',  $deleteTimeFormatted);
        $clickCountStmt->execute();
        $clickCountResult = $clickCountStmt->fetch(PDO::FETCH_ASSOC);
        if ($clickCountResult['click_count'] >= $settingsRow['limit_number']) {
            $blockDuration = $settingsRow['blocked_days'] * 24 * 3600 + $settingsRow['blocked_hours'] * 3600 + $settingsRow['blocked_minutes'] * 60 + $settingsRow['blocked_seconds'];
            $blockUntil = time() + $blockDuration;
            $insertBlockQuery = "INSERT INTO $adBlocksTable (ip_address, fingerprint, ad_unit_id, block_untill) VALUES (:ip_address, :fingerprint, :ad_unit_id, :block_untill)";
            $insertBlockStmt = $conn->prepare($insertBlockQuery);
            $insertBlockStmt->bindParam(':ip_address', $ip_address);
            $insertBlockStmt->bindParam(':fingerprint', $fingerprint);
            $insertBlockStmt->bindParam(':ad_unit_id', $ad_unit_id);
            $blockUntilFormatted = date('Y-m-d H:i:s', $blockUntil);
            $insertBlockStmt->bindParam(':block_untill', $blockUntilFormatted);
            $insertBlockStmt->execute();
            if ($settingsRow['blocked_type'] == 1) {
                echo json_encode(['status' => 'blocked', 'id' => $ad_unit_id]);
            } else {
                echo json_encode(['status' => 'blockedall', 'id' => $ad_unit_id]);
            }
            die();
        } else {
            echo $clickCountResult['click_count'];
        }
    } else {
        echo json_encode(array("status" => "error", "message" => "Missing required fields"));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Invalid request method"));
}
?>
