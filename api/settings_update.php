<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$userId = $_SESSION['user_id'];

$emailNotif = isset($_POST['emailNotification']) ? 1 : 0;
$smsNotif   = isset($_POST['smsNotification']) ? 1 : 0;
$emergNotif = isset($_POST['emergencyAlert']) ? 1 : 0;
$showEmail  = isset($_POST['showEmail']) ? 1 : 0;
$showPhone  = isset($_POST['showPhone']) ? 1 : 0;
$showLoc    = isset($_POST['showLocation']) ? 1 : 0;

$stmt = $conn->prepare("UPDATE users SET email_notification=?, sms_notification=?, emergency_notification=?, show_email=?, show_phone=?, show_location=? WHERE id=?");
$stmt->bind_param("iiiiiii", $emailNotif, $smsNotif, $emergNotif, $showEmail, $showPhone, $showLoc, $userId);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not save settings."]);
}
