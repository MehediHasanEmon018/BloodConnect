<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$hospital = trim($_POST['hospital'] ?? '');
if ($hospital === '') exit(json_encode(["success" => false, "message" => "Hospital name is required."]));

$me = getCurrentUser($conn);
$userId = $_SESSION['user_id'];
$bloodGroup = $me['blood_group'];

$stmt = $conn->prepare("INSERT INTO donations (user_id, hospital, blood_group, donation_date, status) VALUES (?,?,?,CURDATE(),'Completed')");
$stmt->bind_param("iss", $userId, $hospital, $bloodGroup);
$stmt->execute();

echo json_encode(["success" => true, "id" => $stmt->insert_id]);
