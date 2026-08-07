<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$userId = $_SESSION['user_id'];
$available = isset($_POST['available']) && $_POST['available'] === '1' ? 1 : 0;

$stmt = $conn->prepare("UPDATE users SET available_to_donate = ? WHERE id = ?");
$stmt->bind_param("ii", $available, $userId);
$stmt->execute();

$me = getCurrentUser($conn);
$bloodGroup = $me['blood_group'];
$location = trim(($me['division'] ?? '') . (($me['division'] && $me['district']) ? ', ' : '') . ($me['district'] ?? ''));
$phone = $me['phone'];

$syncMarker = "Available to donate blood. (auto-listed from profile)";

$existing = $conn->prepare("SELECT id FROM posts WHERE user_id = ? AND description = ? LIMIT 1");
$existing->bind_param("is", $userId, $syncMarker);
$existing->execute();
$existingRow = $existing->get_result()->fetch_assoc();

if ($available) {

    if ($existingRow) {
        $upd = $conn->prepare("UPDATE posts SET blood_group=?, location=?, contact=? WHERE id=?");
        $upd->bind_param("sssi", $bloodGroup, $location, $phone, $existingRow['id']);
        $upd->execute();
    } else {
        $postType = "Blood Available";
        $hospitalVal = "";
        $urgencyVal = "Normal";
        $ins = $conn->prepare("INSERT INTO posts (user_id, post_type, blood_group, hospital, location, contact, urgency, description, emergency) VALUES (?,?,?,?,?,?,?,?,0)");
        $ins->bind_param("isssssss", $userId, $postType, $bloodGroup, $hospitalVal, $location, $phone, $urgencyVal, $syncMarker);
        $ins->execute();
    }

} else if ($existingRow) {
    $del = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $del->bind_param("i", $existingRow['id']);
    $del->execute();
}

echo json_encode(["success" => true, "available" => (bool)$available]);