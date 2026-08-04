<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$receiverId = (int)($_POST['receiverId'] ?? 0);
$message    = trim($_POST['message'] ?? '');
$image      = $_POST['image'] ?? null;
$senderId   = $_SESSION['user_id'];

if (!$receiverId || ($message === '' && !$image)) {
    exit(json_encode(["success" => false, "message" => "Message cannot be empty."]));
}

$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, image) VALUES (?,?,?,?)");
$stmt->bind_param("iiss", $senderId, $receiverId, $message, $image);
$stmt->execute();

echo json_encode(["success" => true, "id" => $stmt->insert_id]);
