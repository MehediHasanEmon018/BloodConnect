<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$myId = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $targetId = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT id, name, photo, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $targetId);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    exit(json_encode(["success" => (bool)$user, "users" => $user ? [$user] : []]));
}

$query = trim($_GET['q'] ?? '');
if ($query === '') exit(json_encode(["success" => false, "message" => "Type a name or email."]));

$stmt = $conn->prepare("SELECT id, name, photo, email FROM users WHERE (email = ? OR name LIKE ?) AND id != ? LIMIT 5");
$like = "%$query%";
$stmt->bind_param("ssi", $query, $like, $myId);
$stmt->execute();
$users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode(["success" => true, "users" => $users]);
