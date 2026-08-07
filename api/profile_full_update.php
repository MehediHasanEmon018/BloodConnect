<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$userId = $_SESSION['user_id'];

function f($key, $default = '') {
    return trim($_POST[$key] ?? $default);
}

$name        = f('fullName');
$email       = strtolower(f('email'));
$phone       = f('phone');
$bloodGroup  = f('bloodGroup');
$dob         = f('dob') ?: null;
$gender      = f('gender');
$city        = f('city');       // stored in `division`
$district    = f('district');
$postalCode  = f('postalCode');
$country     = f('country', 'Bangladesh');
$address     = f('address');
$weight      = f('weight');
$lastDonation = f('lastDonation') ?: null;
$availability = f('availability');
$emergencyContact = f('emergencyContact');
$bio         = f('bio');
$facebook    = f('facebook');
$linkedin    = f('linkedin');
$instagram   = f('instagram');
$website     = f('website');

$emailNotif = isset($_POST['emailNotification']) ? 1 : 0;
$smsNotif   = isset($_POST['smsNotification']) ? 1 : 0;
$emergNotif = isset($_POST['emergencyNotification']) ? 1 : 0;
$showEmail  = isset($_POST['showEmail']) ? 1 : 0;
$showPhone  = isset($_POST['showPhone']) ? 1 : 0;
$showLoc    = isset($_POST['showLocation']) ? 1 : 0;
$availableToDonate = isset($_POST['availableToDonate']) ? 1 : 0;

$profileImage = $_POST['profileImage'] ?? null;
$coverImage   = $_POST['coverImage'] ?? null;

if (!$name || !$email || !$phone) {
    exit(json_encode(["success" => false, "message" => "Name, email and phone are required."]));
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit(json_encode(["success" => false, "message" => "Invalid email address."]));
}

// prevent switching to an email already used by someone else
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $userId);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    exit(json_encode(["success" => false, "message" => "That email is already in use by another account."]));
}

$sql = "UPDATE users SET name=?, email=?, phone=?, blood_group=?, dob=?, gender=?, division=?, district=?,
        postal_code=?, country=?, address=?, weight=?, last_donation=?, availability=?, emergency_contact=?,
        bio=?, facebook=?, linkedin=?, instagram=?, website=?,
        email_notification=?, sms_notification=?, emergency_notification=?, show_email=?, show_phone=?, show_location=?,
        available_to_donate=?";
$types = "ssssssssssssssssssssiiiiiii";

$params = [
    $name, $email, $phone, $bloodGroup, $dob, $gender, $city, $district,
    $postalCode, $country, $address, $weight, $lastDonation, $availability, $emergencyContact,
    $bio, $facebook, $linkedin, $instagram, $website,
    $emailNotif, $smsNotif, $emergNotif, $showEmail, $showPhone, $showLoc, $availableToDonate
];

if ($profileImage) { $sql .= ", photo=?"; $types .= "s"; $params[] = $profileImage; }
if ($coverImage)   { $sql .= ", cover_image=?"; $types .= "s"; $params[] = $coverImage; }

$sql .= " WHERE id=?";
$types .= "i";
$params[] = $userId;

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if (!$stmt->execute()) {
    http_response_code(500);
    exit(json_encode(["success" => false, "message" => "Update failed: " . $conn->error]));
}

// Keep the donors list in sync with the "Available to Donate" toggle.
// We tag the auto-created listing with a fixed marker description so this
// never touches real posts the user made from Create Post.
$syncMarker = "Available to donate blood. (auto-listed from profile)";

$existing = $conn->prepare("SELECT id FROM posts WHERE user_id = ? AND description = ? LIMIT 1");
$existing->bind_param("is", $userId, $syncMarker);
$existing->execute();
$existingRow = $existing->get_result()->fetch_assoc();

if ($availableToDonate) {

    $locationVal = trim($city . ($city && $district ? ', ' : '') . $district);

    if ($existingRow) {
        $upd = $conn->prepare("UPDATE posts SET blood_group=?, location=?, contact=? WHERE id=?");
        $upd->bind_param("sssi", $bloodGroup, $locationVal, $phone, $existingRow['id']);
        $upd->execute();
    } else {
        $postType = "Blood Available";
        $hospitalVal = "";
        $urgencyVal = "Normal";
        $ins = $conn->prepare("INSERT INTO posts (user_id, post_type, blood_group, hospital, location, contact, urgency, description, emergency) VALUES (?,?,?,?,?,?,?,?,0)");
        $ins->bind_param("isssssss", $userId, $postType, $bloodGroup, $hospitalVal, $locationVal, $phone, $urgencyVal, $syncMarker);
        $ins->execute();
    }

} else if ($existingRow) {
    $del = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $del->bind_param("i", $existingRow['id']);
    $del->execute();
}

echo json_encode(["success" => true]);