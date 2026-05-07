<?php
// /api/pending-user.php
session_start();
header('Content-Type: application/json');

// Prevent direct access if no pending registration
if (!isset($_SESSION['pending_user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No pending registration']);
    exit;
}

// Optional: Clear session after retrieval (one-time view)
// unset($_SESSION['pending_user_id']);

$response = [
    'email' => $_SESSION['pending_email'] ?? null,
    'reference_id' => $_SESSION['pending_ref'] ?? 'REG-' . rand(10000, 99999),
    'submitted_at' => $_SESSION['pending_time'] ?? date('c')
];

echo json_encode($response);
?>