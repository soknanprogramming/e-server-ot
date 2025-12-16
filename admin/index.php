<?php
session_start();

// 1. Security: Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    // Not an admin or not logged in? Redirect to login or home
    header("Location: /auth/sign_in.php");
    exit;
}

require_once __DIR__ . '/../repos/Orders.php';
require_once __DIR__ . '/../repos/Users.php';

// 2. Fetch Data
$ordersRepo = new Orders();
$usersRepo = new Users();

// Get all detailed orders (using the new function we added)
$allOrders = $ordersRepo->getAllOrdersDetailed();
$users = $usersRepo->getUsersForAdmin();

// Calculate Stats
$totalOrders = count($allOrders);
$totalUsers = count($users);
$pendingOrders = 0;
foreach ($allOrders as $order) {
    if (!$order['IsHelp']) {
        $pendingOrders++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Simple Admin CSS */
        :root {
            --primary: #2563eb;
            --bg: #f3f4f6;
            --text: #1f2937;
            --white: #ffffff;
            --border: #e5e7eb;
        }
        body { font-family: sans-serif; background-color: var(--bg); color: var(--text); margin: 0; display: flex; min-height: 100vh; }
        
        /* Sidebar */
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
        
        /* Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .card { background: var(--white); padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .card h3 { margin: 0 0 0.5rem 0; color: #6b7280; font-size: 0.875rem; text-transform: uppercase; }
        .card .value { font-size: 2rem; font-weight: bold; color: var(--text); }

        /* Table */
        .table-container { background: var(--white); border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background-color: #f9fafb; padding: 0.75rem 1.5rem; font-weight: 600; color: #6b7280; border-bottom: 1px solid var(--border); }
        td { padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover { background-color: #f9fafb; }
        
        .status-badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-completed { background-color: #d1fae5; color: #065f46; }
        
        .user-cell { display: flex; flex-direction: column; }
        .user-sub { font-size: 0.85rem; color: #6b7280; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="brand">E-Server Admin</div>
        <ul class="nav">
            <li class="nav-item"><a href="/admin/index.php" class="active">Dashboard</a></li>
            
            <li class="nav-item"><a href="/admin/users.php">Users</a></li>
            
            <li class="nav-item"><a href="/auth/logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="header">
            <h1>Dashboard</h1>
            <div>Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></div>
        </div>

        <div class="stats-grid">
            <div class="card">
                <h3>Total Orders</h3>
                <div class="value"><?php echo $totalOrders; ?></div>
            </div>
            <div class="card">
                <h3>Pending Orders</h3>
                <div class="value"><?php echo $pendingOrders; ?></div>
            </div>
            <div class="card">
                <h3>Total Users</h3>
                <div class="value"><?php echo $totalUsers; ?></div>
            </div>
        </div>

        <h2>All Orders</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Service / Problem</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                        <th>Detail</th>
                     </tr>
                </thead>
                <tbody>
                    <?php if (empty($allOrders)): ?>
                        <tr><td colspan="6" style="text-align:center;">No orders found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($allOrders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td>
                                    <div class="user-cell">
                                        <span><?php echo htmlspecialchars($order['Username']); ?></span>
                                        <span class="user-sub"><?php echo htmlspecialchars($order['PhoneNumber']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="user-cell">
                                        <span><?php echo htmlspecialchars($order['category_name']); ?></span>
                                        <span class="user-sub"><?php echo htmlspecialchars($order['problem_name']); ?></span>
                                    </div>
                                </td>
                                <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $order['IsHelp'] ? 'status-completed' : 'status-pending'; ?>">
                                        <?php echo $order['IsHelp'] ? 'Completed' : 'Pending'; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($order['IsHelp']): ?>
                                        <a href="update_status.php?id=<?= $order['id'] ?>&status=0" style="color: #d97706; text-decoration: underline; margin-right: 10px;">Mark Pending</a>
                                    <?php else: ?>
                                        <a href="update_status.php?id=<?= $order['id'] ?>&status=1" style="color: #059669; font-weight: bold; text-decoration: underline; margin-right: 10px;">Mark Done</a>
                                    <?php endif; ?>
                                    
                                    <a href="delete_order.php?id=<?= $order['id'] ?>" 
                                    onclick="return confirm('Are you sure you want to delete this order?');" 
                                    style="color: #dc2626; text-decoration: none;">
                                    Delete
                                    </a>
                                </td>
                                <td>
                                    <a href="order_detail.php?id=<?= $order['id'] ?>" style="font-weight: bold; color: #2563eb; text-decoration: none;">
                                        #<?= $order['id'] ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>