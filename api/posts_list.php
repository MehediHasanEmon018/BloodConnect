<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false, "message" => "Please log in."])); }

$postType   = trim($_GET['postType'] ?? '');   // 'Blood Available' | 'Blood Request' | ''
$bloodGroup = trim($_GET['bloodGroup'] ?? '');
$location   = trim($_GET['location'] ?? '');
$emergencyOnly = !empty($_GET['emergencyOnly']);

$sql = "SELECT p.*, u.name AS userName, u.photo AS userPhoto, u.email AS userEmail
        FROM posts p JOIN users u ON u.id = p.user_id WHERE 1=1";
$params = [];
$types = "";

if ($postType !== '') { $sql .= " AND p.post_type = ?"; $params[] = $postType; $types .= "s"; }
if ($bloodGroup !== '') { $sql .= " AND p.blood_group = ?"; $params[] = $bloodGroup; $types .= "s"; }
if ($location !== '') { $sql .= " AND p.location LIKE ?"; $params[] = "%$location%"; $types .= "s"; }
if ($emergencyOnly) { $sql .= " AND p.emergency = 1"; }
if (!empty($_GET['mine'])) { $sql .= " AND p.user_id = ?"; $params[] = $_SESSION['user_id']; $types .= "i"; }

$sql .= " ORDER BY p.created_at DESC LIMIT 200";

$stmt = $conn->prepare($sql);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$posts = [];
while ($row = $result->fetch_assoc()) {
    $row['emergency'] = (bool)$row['emergency'];
    $posts[] = $row;
}
echo json_encode(["success" => true, "posts" => $posts]);
