<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$postId = (int)($_POST['postId'] ?? 0);
$userId = $_SESSION['user_id'];
if (!$postId) exit(json_encode(["success" => false, "message" => "Missing postId"]));

$stmt = $conn->prepare("INSERT INTO emergency_responses (post_id, responder_id) VALUES (?,?)");
$stmt->bind_param("ii", $postId, $userId);
$stmt->execute();

// Find the original poster so the frontend can jump straight into a chat with them
$owner = $conn->query("SELECT u.id, u.name FROM posts p JOIN users u ON u.id = p.user_id WHERE p.id = " . $postId)->fetch_assoc();

echo json_encode(["success" => true, "posterId" => $owner['id'] ?? null, "posterName" => $owner['name'] ?? null]);
