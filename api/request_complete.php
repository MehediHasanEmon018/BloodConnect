<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$requestId = (int)($_POST['requestId'] ?? 0);
$donorId   = (int)($_POST['donorId'] ?? 0);
$myId      = $_SESSION['user_id'];

if (!$requestId || !$donorId) exit(json_encode(["success" => false, "message" => "Missing requestId or donorId."]));

$req = $conn->prepare("SELECT requester_id, hospital, blood_group, status FROM blood_requests WHERE id = ?");
$req->bind_param("i", $requestId);
$req->execute();
$request = $req->get_result()->fetch_assoc();

if (!$request) exit(json_encode(["success" => false, "message" => "Request not found."]));
if ($request['requester_id'] != $myId) { http_response_code(403); exit(json_encode(["success" => false, "message" => "Not your request."])); }
if ($request['status'] === 'Completed') exit(json_encode(["success" => false, "message" => "This request is already completed."]));

// Confirm the chosen donor's response, decline the rest.
$confirm = $conn->prepare("UPDATE request_responses SET status='Confirmed' WHERE request_id=? AND donor_id=?");
$confirm->bind_param("ii", $requestId, $donorId);
if (!$confirm->execute() || $confirm->affected_rows === 0) {
    exit(json_encode(["success" => false, "message" => "That donor has no active offer on this request."]));
}

$decline = $conn->prepare("UPDATE request_responses SET status='Declined' WHERE request_id=? AND donor_id!=?");
$decline->bind_param("ii", $requestId, $donorId);
$decline->execute();

// Mark the request completed.
$updateReq = $conn->prepare("UPDATE blood_requests SET status='Completed' WHERE id=?");
$updateReq->bind_param("i", $requestId);
$updateReq->execute();

// This is the single source of truth for "Successful Donations".
$ins = $conn->prepare("INSERT INTO donations (user_id, request_id, hospital, blood_group, donation_date, status) VALUES (?,?,?,?,CURDATE(),'Completed')");
$ins->bind_param("iiss", $donorId, $requestId, $request['hospital'], $request['blood_group']);
$ins->execute();

echo json_encode(["success" => true]);
