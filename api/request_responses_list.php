<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$requestId = (int)($_GET['requestId'] ?? 0);
$myId = $_SESSION['user_id'];

$req = $conn->prepare("SELECT requester_id FROM blood_requests WHERE id = ?");
$req->bind_param("i", $requestId);
$req->execute();
$row = $req->get_result()->fetch_assoc();

if (!$row || $row['requester_id'] != $myId) {
    http_response_code(403);
    exit(json_encode(["success" => false, "message" => "Not your request."]));
}

$stmt = $conn->prepare("SELECT rr.id, rr.donor_id, rr.status, rr.created_at, u.name, u.phone, u.blood_group
                         FROM request_responses rr JOIN users u ON u.id = rr.donor_id
                         WHERE rr.request_id = ? AND rr.status != 'Declined'
                         ORDER BY rr.created_at ASC");
$stmt->bind_param("i", $requestId);
$stmt->execute();
$responses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode(["success" => true, "responses" => $responses]);
