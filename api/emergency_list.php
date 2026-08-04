<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$result = $conn->query("SELECT er.id, er.post_id AS postId, er.created_at AS time,
                                u.id AS responderId, u.name AS responderName, u.photo AS responderPhoto
                         FROM emergency_responses er JOIN users u ON u.id = er.responder_id
                         ORDER BY er.created_at DESC LIMIT 50");
$responses = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode(["success" => true, "responses" => $responses]);
