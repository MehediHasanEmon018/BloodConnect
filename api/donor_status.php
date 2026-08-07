<?php
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { http_response_code(401); exit(json_encode(["success" => false])); }

$me = getCurrentUser($conn);
echo json_encode(["success" => true, "available" => (bool)($me['available_to_donate'] ?? false)]);