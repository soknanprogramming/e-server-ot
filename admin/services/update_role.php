<?php
session_start();
require_once __DIR__ . '/../../repos/Users.php';

// Security: Admin Only
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header("Location: /auth/sign_in.php");
    exit;
}

if (isset($_GET['id']) && isset($_GET['role'])) {
    $userId = $_GET['id'];
    $role = (int)$_GET['role']; // 1 = Admin, 0 = User

    // Prevent changing your own role (so you don't lock yourself out)
    if ($userId == $_SESSION['user_id']) {
        echo "<script>alert('You cannot change your own role.'); window.location.href='/admin/users.php';</script>";
        exit;
    }

    $usersRepo = new Users();
    $usersRepo->updateUserRole($userId, $role);
}

header("Location: /admin/users.php");
exit;