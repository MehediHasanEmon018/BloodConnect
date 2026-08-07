<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$requestId = (int)($_POST['requestId'] ?? 0);
$donorId = $_SESSION['user_id'];

if (!$requestId) exit(json_encode(["success" => false, "message" => "Missing requestId"]));

$req = $conn->prepare("SELECT requester_id, status FROM blood_requests WHERE id = ?");
$req->bind_param("i", $requestId);
$req->execute();
$row = $req->get_result()->fetch_assoc();

if (!$row) exit(json_encode(["success" => false, "message" => "Request not found."]));
if ($row['requester_id'] == $donorId) exit(json_encode(["success" => false, "message" => "You cannot offer on your own request."]));
if ($row['status'] !== 'Pending') exit(json_encode(["success" => false, "message" => "This request is no longer open."]));

$stmt = $conn->prepare("INSERT INTO request_responses (request_id, donor_id, status) VALUES (?,?,'Pending')
                         ON DUPLICATE KEY UPDATE status='Pending'");
$stmt->bind_param("ii", $requestId, $donorId);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not submit your offer."]);
}
