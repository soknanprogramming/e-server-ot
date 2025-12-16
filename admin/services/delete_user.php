<?php
session_start();
require_once __DIR__ . '/../../repos/Users.php';

// Security: Admin Only
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header("Location: /auth/sign_in.php");
    exit;
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Prevent deleting yourself
    if ($userId == $_SESSION['user_id']) {
        echo "<script>alert('You cannot delete your own account.'); window.location.href='/admin/users.php';</script>";
        exit;
    }

    $usersRepo = new Users();
    $usersRepo->deleteUser($userId);
}

header("Location: /admin/users.php");
exit;