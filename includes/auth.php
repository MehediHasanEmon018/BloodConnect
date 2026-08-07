<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirect to login if not authenticated. Call at the top of any protected page.
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: Login.php");
        exit;
    }
}

// Fetch the full current user row (without password_hash), or null.
function getCurrentUser($conn) {
    if (!isLoggedIn()) return null;
    $stmt = $conn->prepare("SELECT id, name, email, phone, blood_group, gender, dob, division, district,
        last_donation, photo, available_to_donate, cover_image, postal_code, country, address, weight,
        availability, emergency_contact, bio, facebook, linkedin, instagram, website,
        email_notification, sms_notification, emergency_notification,
        show_email, show_phone, show_location, reliability, created_at
        FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}

// Escape output for safe HTML rendering (fixes the XSS issue in donor/post rendering).
function h($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}