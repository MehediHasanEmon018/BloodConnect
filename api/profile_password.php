<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$userId  = $_SESSION['user_id'];
$current = $_POST['currentPassword'] ?? '';
$new     = $_POST['newPassword'] ?? '';
$confirm = $_POST['confirmPassword'] ?? '';

$stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row || !password_verify($current, $row['password_hash'])) {
    exit(json_encode(["success" => false, "message" => "Current password is incorrect."]));
}
if (strlen($new) < 6) {
    exit(json_encode(["success" => false, "message" => "New password must be at least 6 characters."]));
}
if ($new !== $confirm) {
    exit(json_encode(["success" => false, "message" => "New passwords do not match."]));
}

$hash = password_hash($new, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
$stmt->bind_param("si", $hash, $userId);
$stmt->execute();

echo json_encode(["success" => true]);
