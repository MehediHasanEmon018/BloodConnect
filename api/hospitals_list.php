<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$result = $conn->query("SELECT id, name, location, blood_groups, phone FROM hospitals ORDER BY name ASC");
$hospitals = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode(["success" => true, "hospitals" => $hospitals]);
