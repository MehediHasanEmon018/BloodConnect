<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $postId = (int)($_GET['postId'] ?? 0);
    $stmt = $conn->prepare("SELECT c.id, c.comment, c.created_at, u.name AS userName, u.photo AS userPhoto
                             FROM post_comments c JOIN users u ON u.id = c.user_id
                             WHERE c.post_id = ? ORDER BY c.created_at ASC");
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $comments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    exit(json_encode(["success" => true, "comments" => $comments]));
}

$postId  = (int)($_POST['postId'] ?? 0);
$comment = trim($_POST['comment'] ?? '');
$userId  = $_SESSION['user_id'];

if (!$postId || $comment === '') {
    exit(json_encode(["success" => false, "message" => "Comment cannot be empty."]));
}

$stmt = $conn->prepare("INSERT INTO post_comments (post_id, user_id, comment) VALUES (?,?,?)");
$stmt->bind_param("iis", $postId, $userId, $comment);
$stmt->execute();

echo json_encode(["success" => true, "id" => $stmt->insert_id]);
