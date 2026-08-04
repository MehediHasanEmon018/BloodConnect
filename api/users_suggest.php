<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$myId = $_SESSION['user_id'];
$me = getCurrentUser($conn);

$stmt = $conn->prepare("SELECT id, name, photo, blood_group FROM users WHERE id != ?
                         ORDER BY (blood_group = ?) DESC, created_at DESC LIMIT 4");
$stmt->bind_param("is", $myId, $me['blood_group']);
$stmt->execute();
$donors = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode(["success" => true, "donors" => $donors]);
