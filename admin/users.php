<?php
session_start();
require_once __DIR__ . '/../repos/Users.php';

// Security Check
if (!isset($_SESSION['user_id']) || !isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header("Location: /auth/sign_in.php");
    exit;
}

$usersRepo = new Users();
$users = $usersRepo->getUsersForAdmin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        :root { --primary: #2563eb; --bg: #f3f4f6; --text: #1f2937; --white: #ffffff; --border: #e5e7eb; }
        body { font-family: sans-serif; background-color: var(--bg); color: var(--text); margin: 0; display: flex; min-height: 100vh; }
        
        /* Reuse Sidebar Styles */
        .sidebar { width: 250px; background-color: var(--white); border-right: 1px solid var(--border); position: fixed; height: 100%; top: 0; left: 0; }
        .brand { padding: 1.5rem; font-size: 1.5rem; font-weight: bold; color: var(--primary); border-bottom: 1px solid var(--border); }
        .nav { list-style: none; padding: 0; margin: 0; }
        .nav-item a { display: block; padding: 1rem 1.5rem; text-decoration: none; color: var(--text); transition: background 0.2s; }
        .nav-item a:hover { background-color: #eff6ff; color: var(--primary); }
        .nav-item a.active { background-color: #eff6ff; color: var(--primary); font-weight: 600; border-right: 3px solid var(--primary); }

        /* Main Content */
        .main-content { margin-left: 250px; flex: 1; padding: 2rem; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .header h1 { margin: 0; font-size: 1.8rem; }
        
        /* Table */
        .table-container { background: var(--white); border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background-color: #f9fafb; padding: 0.75rem 1.5rem; font-weight: 600; color: #6b7280; border-bottom: 1px solid var(--border); }
        td { padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover { background-color: #f9fafb; }
        
        .role-badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .role-admin { background-color: #dbeafe; color: #1e40af; }
        .role-user { background-color: #f3f4f6; color: #374151; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="brand">E-Server Admin</div>
        <ul class="nav">
            <li class="nav-item"><a href="/admin/index.php">Dashboard</a></li>
            <li class="nav-item"><a href="/admin/users.php" class="active">Users</a></li>
            <li class="nav-item"><a href="/auth/logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="header">
            <h1>All Users</h1>
            <div>Total: <?= count($users) ?></div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Gmail</th>
                        <th>Role</th>
                        <th>Joined Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr><td colspan="5" style="text-align:center;">No users found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>#<?= $user['id'] ?></td>
                                <td style="font-weight: 500;"><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['gmail']) ?></td>
                                <td>
                                    <?php if ($user['isAdmin']): ?>
                                        <span class="role-badge role-admin">Admin</span>
                                    <?php else: ?>
                                        <span class="role-badge role-user">User</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>