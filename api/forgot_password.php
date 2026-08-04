<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');

$email = trim(strtolower($_POST['email'] ?? ''));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit(json_encode(["success" => false, "message" => "Please enter a valid email address."]));
}

// NOTE: this only checks whether the account exists. Actually emailing a reset
// link needs an SMTP setup (e.g. PHPMailer) that isn't configured for this
// project yet. Always returning the same message either way avoids leaking
// which emails are registered.
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$exists = $stmt->get_result()->num_rows > 0;

echo json_encode(["success" => true, "message" => "If an account exists for that email, a reset link would be sent to it."]);
