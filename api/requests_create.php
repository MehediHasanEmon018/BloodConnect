<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false, "message" => "Please log in."])); }

$patientName = trim($_POST['patientName'] ?? '');
$bloodGroup  = trim($_POST['bloodGroup'] ?? '');
$units       = (int)($_POST['units'] ?? 0);
$hospitalId  = (int)($_POST['hospitalId'] ?? 0);
$hospitalOther = trim($_POST['hospitalOther'] ?? '');
$location    = trim($_POST['location'] ?? '');
$phone       = trim($_POST['phone'] ?? '');
$urgency     = trim($_POST['urgency'] ?? '');
$date        = trim($_POST['date'] ?? '');
$notes       = trim($_POST['notes'] ?? '');

$hospitalName = '';
$hospitalIdVal = null;

if ($hospitalId > 0) {
    $h = $conn->prepare("SELECT id, name FROM hospitals WHERE id = ?");
    $h->bind_param("i", $hospitalId);
    $h->execute();
    $row = $h->get_result()->fetch_assoc();
    if ($row) {
        $hospitalName = $row['name'];
        $hospitalIdVal = $row['id'];
    }
}

if (!$hospitalName) {
    // "Other" hospital, or an invalid id was sent - fall back to free text.
    $hospitalName = $hospitalOther;
}

if (!$patientName || !$bloodGroup || !$units || !$hospitalName || !$location || !$phone || !$date) {
    exit(json_encode(["success" => false, "message" => "Please fill in all required fields, including a hospital."]));
}

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("INSERT INTO blood_requests (requester_id, patient_name, blood_group, units, hospital, hospital_id, location, phone, urgency, needed_date, notes, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,'Pending')");
$stmt->bind_param("issisisssss", $userId, $patientName, $bloodGroup, $units, $hospitalName, $hospitalIdVal, $location, $phone, $urgency, $date, $notes);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "id" => $stmt->insert_id]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not submit the request."]);
}
