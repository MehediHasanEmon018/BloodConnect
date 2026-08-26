<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false, "message" => "Please log in."])); }

$postType     = trim($_POST['postType'] ?? '');
$bloodGroup   = trim($_POST['bloodGroup'] ?? '');
$hospital     = trim($_POST['hospital'] ?? '');
$location     = trim($_POST['location'] ?? '');
$contact      = trim($_POST['contact'] ?? '');
$urgency      = trim($_POST['urgency'] ?? '');
$requiredDate = trim($_POST['requiredDate'] ?? '') ?: null;
$description  = trim($_POST['description'] ?? '');
$image        = $_POST['image'] ?? null; // base64 data URL from compressImage(), same as before
$emergency    = !empty($_POST['emergency']) ? 1 : 0;

if (!$postType || !$bloodGroup) {
    exit(json_encode(["success" => false, "message" => "Post type and blood group are required."]));
}

$stmt = $conn->prepare("INSERT INTO posts (user_id, post_type, blood_group, hospital, location, contact, urgency, required_date, description, image, emergency) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
$userId = $_SESSION['user_id'];
$stmt->bind_param("isssssssssi", $userId, $postType, $bloodGroup, $hospital, $location, $contact, $urgency, $requiredDate, $description, $image, $emergency);

if ($stmt->execute()) {
    $postId = $stmt->insert_id;

    if ($postType === "Blood Request" || $postType === "Blood Available" || $emergency) {
        $me = getCurrentUser($conn);
        $patientName = $me['name'] ?: 'Not specified';
        $units = 1;
        $hospitalVal = $hospital ?: 'Not specified';
        $locationVal = $location ?: 'Not specified';
        $neededDate = $requiredDate ?: date('Y-m-d');
        // Keep the row's Type in sync with the post: "Blood Available" posts
        // are donors offering blood, everything else (a request, or any
        // post flagged Emergency) is treated as a "Blood Request".
        $reqType = ($postType === "Blood Available") ? "Blood Available" : "Blood Request";
        $req = $conn->prepare("INSERT INTO blood_requests (requester_id, type, patient_name, blood_group, units, hospital, location, phone, urgency, needed_date, notes, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,'Pending')");
        $req->bind_param("isssissssss", $userId, $reqType, $patientName, $bloodGroup, $units, $hospitalVal, $locationVal, $contact, $urgency, $neededDate, $description);
        if (!$req->execute()) {
            error_log("posts_create.php: blood_requests insert failed for post $postId: " . $req->error);
        }
    }

    echo json_encode(["success" => true, "id" => $postId]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not publish the post."]);
}