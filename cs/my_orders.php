<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Получить заказы пользователя
$sql = "SELECT o.id AS order_id, o.created_at, m.name, m.price, oi.quantity
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN menu_items m ON oi.menu_item_id = m.id
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[$row['order_id']]['created_at'] = $row['created_at'];
    $orders[$row['order_id']]['items'][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders | Union Coffee Pavlodar</title>
    <link rel="stylesheet" href="menu.css">
    <style>
        .orders { max-width: 900px; margin: 20px auto; }
        .order-block { border: 1px solid #ccc; margin-bottom: 20px; padding: 15px; border-radius: 8px; background: #f9f9f9; }
        .order-block h3 { margin-bottom: 10px; }
        .order-item { padding: 5px 0; border-bottom: 1px dashed #ccc; }
    </style>
</head>
<body>
<header>
    <h1>Union Coffee Pavlodar</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="menu.php">Menu</a>
        <a href="my_orders.php" class="active">My Orders</a>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
            <a href="admin.php">Admin Panel</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main class="orders">
    <h2>My Orders</h2>

    <?php if (empty($orders)): ?>
        <p>You have no orders yet.</p>
    <?php else: ?>
        <?php foreach ($orders as $order_id => $order): ?>
            <div class="order-block">
                <h3>Order #<?= $order_id ?> <small>(<?= $order['created_at'] ?>)</small></h3>
                <?php foreach ($order['items'] as $item): ?>
                    <div class="order-item">
                        <?= htmlspecialchars($item['name']) ?> — <?= $item['quantity'] ?> × <?= $item['price'] ?>₸
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<footer>
    <p>&copy; 2025 Union Coffee Pavlodar</p>
</footer>
</body>
</html>
