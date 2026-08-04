<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(["success" => false, "message" => "Method not allowed"]));
}

$email    = trim(strtolower($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    exit(json_encode(["success" => false, "message" => "Email and password are required."]));
}

$stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($password, $user['password_hash'])) {
    exit(json_encode(["success" => false, "message" => "Incorrect email or password."]));
}

session_regenerate_id(true);
$_SESSION['user_id'] = $user['id'];
echo json_encode(["success" => true, "message" => "Login successful."]);
