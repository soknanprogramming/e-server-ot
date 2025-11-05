<?php
session_start();
require_once __DIR__ . '/../repos/Users.php';

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $gmail = $_POST['gmail'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($gmail) || empty($password)) {
        $errorMessage = "Please fill in all fields.";
    } elseif (!filter_var($gmail, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format.";
    } else {
        $usersRepo = new Users();
        $result = $usersRepo->userSignup($username, $password, $gmail);

        if ($result === true) {
            // Signup successful, redirect to login page
            header("Location: /auth/sign_in.php?signup=success");
            exit;
        } else {
            // Signup failed, display the error message from the repo
            $errorMessage = $result;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; }
        form { border: 1px solid #ccc; padding: 20px; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        .error { color: red; }
        .text-center { text-align: center; margin-top: 15px; }
    </style>
</head>
<body>
    <form action="/auth/sign_up.php" method="POST">
        <h2>Sign Up</h2>
        <?php if ($errorMessage): ?>
            <p class="error"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="gmail">Email</label>
            <input type="email" id="gmail" name="gmail" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Sign Up</button>
        <div class="text-center">
            <p>Already have an account? <a href="/auth/sign_in.php">Sign In</a></p>
        </div>
    </form>
</body>
</html>