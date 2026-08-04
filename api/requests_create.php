<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false, "message" => "Please log in."])); }

$patientName = trim($_POST['patientName'] ?? '');
$bloodGroup  = trim($_POST['bloodGroup'] ?? '');
$units       = (int)($_POST['units'] ?? 0);
$hospital    = trim($_POST['hospital'] ?? '');
$location    = trim($_POST['location'] ?? '');
$phone       = trim($_POST['phone'] ?? '');
$urgency     = trim($_POST['urgency'] ?? '');
$date        = trim($_POST['date'] ?? '');
$notes       = trim($_POST['notes'] ?? '');

if (!$patientName || !$bloodGroup || !$units || !$hospital || !$location || !$phone || !$date) {
    exit(json_encode(["success" => false, "message" => "Please fill in all required fields."]));
}

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("INSERT INTO blood_requests (requester_id, patient_name, blood_group, units, hospital, location, phone, urgency, needed_date, notes, status) VALUES (?,?,?,?,?,?,?,?,?,?,'Pending')");
$stmt->bind_param("ississsss", $userId, $patientName, $bloodGroup, $units, $hospital, $location, $phone, $urgency, $date, $notes);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "id" => $stmt->insert_id]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not submit the request."]);
}
