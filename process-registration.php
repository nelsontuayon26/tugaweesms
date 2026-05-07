<?php
// /process-registration.php
session_start();

// Your existing registration logic here...
// $userId = createUser($_POST);
// $email = $_POST['email'];

// After successful database insert:
$_SESSION['pending_user_id'] = $userId;        // The new user's ID
$_SESSION['pending_email'] = $email;            // User's email
$_SESSION['pending_ref'] = 'REG-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
$_SESSION['pending_time'] = date('c');

// Redirect to pending page
header('Location: /pending-approval.html');
exit;
?>