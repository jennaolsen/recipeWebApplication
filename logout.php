<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    session_unset();
    session_destroy();

    // If GET request → do redirect (for profile page)
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $redirect = $_GET['redirect'] ?? 'index.php';
        header("Location: $redirect");
        exit;
    }

    // If POST request → return JSON (for AJAX logout)
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Logged out successfully']);
    exit;
}

http_response_code(403);
echo json_encode(['message' => 'Forbidden']);
exit;
?>