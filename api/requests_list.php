<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$result = $conn->query("SELECT br.*, u.name AS requesterName, u.email AS requesterEmail
                         FROM blood_requests br JOIN users u ON u.id = br.requester_id
                         ORDER BY br.created_at ASC");
$requests = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode(["success" => true, "requests" => $requests]);
