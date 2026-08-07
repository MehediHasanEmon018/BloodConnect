<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$myId = $_SESSION['user_id'];

$result = $conn->query("SELECT br.*, u.name AS requesterName, u.email AS requesterEmail
                         FROM blood_requests br JOIN users u ON u.id = br.requester_id
                         ORDER BY br.created_at ASC");
$requests = $result->fetch_all(MYSQLI_ASSOC);

// Attach: how many people have offered, and whether *this* user already offered.
$countStmt = $conn->prepare("SELECT COUNT(*) c FROM request_responses WHERE request_id = ? AND status != 'Declined'");
$mineStmt  = $conn->prepare("SELECT status FROM request_responses WHERE request_id = ? AND donor_id = ?");

foreach ($requests as &$r) {
    $countStmt->bind_param("i", $r['id']);
    $countStmt->execute();
    $r['responseCount'] = (int)$countStmt->get_result()->fetch_assoc()['c'];

    $mineStmt->bind_param("ii", $r['id'], $myId);
    $mineStmt->execute();
    $mine = $mineStmt->get_result()->fetch_assoc();
    $r['myResponseStatus'] = $mine ? $mine['status'] : null;
}

echo json_encode(["success" => true, "requests" => $requests, "myId" => $myId]);
