<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$postId = (int)($_POST['postId'] ?? 0);
$userId = $_SESSION['user_id'];
if (!$postId) exit(json_encode(["success" => false, "message" => "Missing postId"]));

$stmt = $conn->prepare("SELECT 1 FROM post_likes WHERE post_id=? AND user_id=?");
$stmt->bind_param("ii", $postId, $userId);
$stmt->execute();
$already = $stmt->get_result()->num_rows > 0;

if ($already) {
    $stmt = $conn->prepare("DELETE FROM post_likes WHERE post_id=? AND user_id=?");
    $stmt->bind_param("ii", $postId, $userId);
    $stmt->execute();
    $conn->query("UPDATE posts SET likes = GREATEST(likes-1,0) WHERE id=" . $postId);
    $liked = false;
} else {
    $stmt = $conn->prepare("INSERT INTO post_likes (post_id, user_id) VALUES (?,?)");
    $stmt->bind_param("ii", $postId, $userId);
    $stmt->execute();
    $conn->query("UPDATE posts SET likes = likes+1 WHERE id=" . $postId);
    $liked = true;
}

$result = $conn->query("SELECT likes FROM posts WHERE id=" . $postId)->fetch_assoc();
echo json_encode(["success" => true, "liked" => $liked, "likes" => (int)$result['likes']]);
