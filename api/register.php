<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(["success" => false, "message" => "Method not allowed"]));
}

$name        = trim($_POST['name'] ?? '');
$email       = trim(strtolower($_POST['email'] ?? ''));
$phone       = trim($_POST['phone'] ?? '');
$bloodGroup  = trim($_POST['bloodGroup'] ?? '');
$gender      = trim($_POST['gender'] ?? '');
$dob         = trim($_POST['dob'] ?? '');
$division    = trim($_POST['division'] ?? '');
$district    = trim($_POST['district'] ?? '');
$password    = $_POST['password'] ?? '';
$confirm     = $_POST['confirmPassword'] ?? '';
$lastDonation = trim($_POST['lastDonation'] ?? '');

if (!$name || !$email || !$phone || !$bloodGroup || !$gender || !$dob || !$division || !$district || !$password) {
    exit(json_encode(["success" => false, "message" => "All required fields must be filled."]));
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit(json_encode(["success" => false, "message" => "Invalid email address."]));
}
if (strlen($password) < 6) {
    exit(json_encode(["success" => false, "message" => "Password must be at least 6 characters."]));
}
if ($password !== $confirm) {
    exit(json_encode(["success" => false, "message" => "Passwords do not match."]));
}

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    exit(json_encode(["success" => false, "message" => "An account with this email already exists."]));
}
$stmt->close();

$hash = password_hash($password, PASSWORD_DEFAULT);
$lastDonationVal = $lastDonation !== '' ? $lastDonation : null;

$stmt = $conn->prepare("INSERT INTO users (name, email, phone, password_hash, blood_group, gender, dob, division, district, last_donation) VALUES (?,?,?,?,?,?,?,?,?,?)");
$stmt->bind_param("ssssssssss", $name, $email, $phone, $hash, $bloodGroup, $gender, $dob, $division, $district, $lastDonationVal);

if ($stmt->execute()) {
    $_SESSION['user_id'] = $stmt->insert_id;
    echo json_encode(["success" => true, "message" => "Account created successfully."]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Registration failed. Please try again."]);
}
$stmt->close();
