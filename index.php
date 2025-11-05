<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not, redirect to the sign-in page
    header("Location: /auth/sign_in.php");
    exit;
}

// Check if the logged-in user is an admin
if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
    header("Location: /admin");
} else {
    header("Location: /clients");
}

exit;