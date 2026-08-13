<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid user id"]);
    exit;
}

$stmt = $conn->prepare(
    "SELECT id, name, email, phone, blood_group, division, district, photo, cover_image, show_email, show_phone, show_location
     FROM users WHERE id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo json_encode(["success" => false, "message" => "User not found"]);
    exit;
}

$location = trim(
    ($user['division'] ?? '') .
    (($user['division'] && $user['district']) ? ', ' : '') .
    ($user['district'] ?? '')
);

echo json_encode([
    "success" => true,
    "user" => [
        "id"         => $user['id'],
        "name"       => $user['name'],
        "email"      => (!isset($user['show_email']) || $user['show_email']) ? $user['email'] : null,
        "phone"      => (!isset($user['show_phone']) || $user['show_phone']) ? $user['phone'] : null,
        "bloodGroup" => $user['blood_group'],
        "location"   => (!isset($user['show_location']) || $user['show_location']) ? $location : null,
        "photo"      => $user['photo'] ?: 'images/user.png'
    ]
]);