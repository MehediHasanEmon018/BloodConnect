<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$myId = $_SESSION['user_id'];

// mode=contacts -> everyone I've exchanged messages with, most recent first
if (($_GET['mode'] ?? '') === 'contacts') {
    $stmt = $conn->prepare("
        SELECT u.id, u.name, u.photo,
               MAX(m.created_at) AS lastTime,
               SUM(CASE WHEN m.receiver_id = ? AND m.is_read = 0 THEN 1 ELSE 0 END) AS unread
        FROM messages m
        JOIN users u ON u.id = IF(m.sender_id = ?, m.receiver_id, m.sender_id)
        WHERE m.sender_id = ? OR m.receiver_id = ?
        GROUP BY u.id, u.name, u.photo
        ORDER BY lastTime DESC
    ");
    $stmt->bind_param("iiii", $myId, $myId, $myId, $myId);
    $stmt->execute();
    $contacts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    exit(json_encode(["success" => true, "contacts" => $contacts]));
}

// otherwise: full conversation with a given user id
$otherId = (int)($_GET['userId'] ?? 0);
if (!$otherId) exit(json_encode(["success" => false, "message" => "Missing userId"]));

$stmt = $conn->prepare("SELECT id, sender_id AS senderId, receiver_id AS receiverId, message, image, created_at AS time
                         FROM messages
                         WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
                         ORDER BY created_at ASC");
$stmt->bind_param("iiii", $myId, $otherId, $otherId, $myId);
$stmt->execute();
$messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// mark incoming messages as read
$conn->query("UPDATE messages SET is_read = 1 WHERE sender_id = $otherId AND receiver_id = $myId");

echo json_encode(["success" => true, "messages" => $messages]);
