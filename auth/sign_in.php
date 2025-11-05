<?php
session_start();
require_once __DIR__ . '/../repos/Users.php';

$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $errorMessage = "Please enter both username and password.";
    } else {
        $usersRepo = new Users();
        $user = $usersRepo->userLogin($username, $password);

        if ($user) {
            // Login successful, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['isAdmin'] = (bool)$user['isAdmin'];

            // Redirect to the main index page
            header("Location: /index.php");
            exit;
        } else {
            // Login failed
            $errorMessage = "Invalid username or password.";
        }
    }
} elseif (isset($_GET['signup']) && $_GET['signup'] === 'success') {
    // Check for the signup success message in the URL
    $successMessage = "Sign up successful! You can now sign in.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; }
        form { border: 1px solid #ccc; padding: 20px; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        .error { color: red; }
        .success { color: green; }
        .text-center { text-align: center; margin-top: 15px; }
    </style>
</head>
<body>
    <form action="/auth/sign_in.php" method="POST">
        <h2>Sign In</h2>
        <?php if ($successMessage): ?>
            <p class="success"><?php echo $successMessage; ?></p>
        <?php endif; ?>
        <?php if ($errorMessage): ?>
            <p class="error"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Sign In</button>
        <div class="text-center">
            <p>Don't have an account? <a href="/auth/sign_up.php">Sign Up</a></p>
        </div>
    </form>
</body>
</html>