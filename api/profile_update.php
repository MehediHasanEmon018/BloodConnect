<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$userId   = $_SESSION['user_id'];
$name     = trim($_POST['name'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$bloodGroup = trim($_POST['bloodGroup'] ?? '');
$division = trim($_POST['division'] ?? '');
$district = trim($_POST['district'] ?? '');
$photo    = $_POST['photo'] ?? null; // base64 data URL, same compressImage() pipeline as posts

if (!$name || !$phone) {
    exit(json_encode(["success" => false, "message" => "Name and phone are required."]));
}

if ($photo) {
    $stmt = $conn->prepare("UPDATE users SET name=?, phone=?, blood_group=?, division=?, district=?, photo=? WHERE id=?");
    $stmt->bind_param("ssssssi", $name, $phone, $bloodGroup, $division, $district, $photo, $userId);
} else {
    $stmt = $conn->prepare("UPDATE users SET name=?, phone=?, blood_group=?, division=?, district=? WHERE id=?");
    $stmt->bind_param("sssssi", $name, $phone, $bloodGroup, $division, $district, $userId);
}

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Update failed."]);
}
