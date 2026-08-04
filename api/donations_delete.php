<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$id = (int)($_POST['id'] ?? 0);
$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM donations WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $userId);
$stmt->execute();

echo json_encode(["success" => $stmt->affected_rows > 0]);
