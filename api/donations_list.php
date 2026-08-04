<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, hospital, blood_group, donation_date, status FROM donations WHERE user_id = ? ORDER BY donation_date ASC, id ASC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$donations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode(["success" => true, "donations" => $donations]);
