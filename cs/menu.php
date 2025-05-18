<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch menu items grouped by category
$stmt = $conn->query("SELECT * FROM menu_items ORDER BY category, id");
$menu_items = $stmt->fetch_all(MYSQLI_ASSOC);

$grouped = [];
foreach ($menu_items as $item) {
    $grouped[$item['category']][] = $item;
}

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order'])) {
    $user_id = $_SESSION['user_id'];
    $items = $_POST['items'] ?? [];

    if (empty($items)) {
        $order_error = "Please select at least one item to order.";
    } else {
        // Insert order
        $stmt_order = $conn->prepare("INSERT INTO orders (user_id) VALUES (?)");
        $stmt_order->bind_param("i", $user_id);
        $stmt_order->execute();
        $order_id = $stmt_order->insert_id;
        $stmt_order->close();

        // Insert order items
        $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity) VALUES (?, ?, ?)");
        foreach ($items as $menu_item_id => $qty) {
            $qty = intval($qty);
            if ($qty > 0) {
                $stmt_item->bind_param("iii", $order_id, $menu_item_id, $qty);
                $stmt_item->execute();
            }
        }
        $stmt_item->close();

        $order_success = "Thank you! Your order has been placed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Menu | Union Coffee Pavlodar</title>
    <link rel="stylesheet" href="menu.css" />
    <style>
        .order-message { margin: 15px 0; font-weight: bold; }
        .order-error { color: red; }
        .order-success { color: green; }
        .quantity-input {
            width: 40px;
            text-align: center;
            margin-left: 5px;
        }
        form.order-form {
            max-width: 900px;
            margin: 20px auto;
        }
        button.submit-order {
            margin-top: 20px;
            padding: 10px 25px;
            font-size: 1.1rem;
            background-color: #8B4513;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button.submit-order:hover {
            background-color: #a1622a;
        }
    </style>
</head>
<body>
<header>
    <h1>Union Coffee Pavlodar</h1>
    <nav>
    <a href="index.php">Home</a>
    <a href="menu.php">Menu</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="my_orders.php">My Orders</a>
    <?php endif; ?>
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <a href="admin.php">Admin Panel</a>
    <?php endif; ?>
    <a href="logout.php">Logout</a>
</nav>
</header>

<main class="menu-container">
    <?php if (!empty($order_error)): ?>
        <div class="order-message order-error"><?= htmlspecialchars($order_error) ?></div>
    <?php elseif (!empty($order_success)): ?>
        <div class="order-message order-success"><?= htmlspecialchars($order_success) ?></div>
    <?php endif; ?>

    <form method="post" class="order-form">
        <?php foreach ($grouped as $category => $items): ?>
            <h2><?= htmlspecialchars($category) ?></h2>
            <section class="menu-section">
                <div class="menu-grid">
                    <?php foreach ($items as $item): ?>
                        <div class="menu-item">
                            <div class="image-wrapper">
                                <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" />
                            </div>
                            <h4><?= htmlspecialchars($item['name']) ?></h4>
                            <p><?= htmlspecialchars($item['description']) ?></p>
                            <span><?= htmlspecialchars($item['price']) ?>₸</span>
                            <div>
                                <label>
                                    <input type="checkbox" name="items[<?= $item['id'] ?>][selected]" value="1" />
                                    Select
                                </label>
                                <input
                                    type="number"
                                    min="1"
                                    name="items[<?= $item['id'] ?>][quantity]"
                                    value="1"
                                    class="quantity-input"
                                    disabled
                                />
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
        <button type="submit" name="order" class="submit-order">Place Order</button>
    </form>
</main>

<script>
    // Enable quantity input only if checkbox is checked
    document.querySelectorAll('input[type="checkbox"][name^="items"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            let qtyInput = this.closest('div').querySelector('input[type="number"]');
            if (this.checked) {
                qtyInput.disabled = false;
            } else {
                qtyInput.disabled = true;
                qtyInput.value = 1;
            }
        });
    });
</script>

<footer>
    <p>&copy; 2025 Union Coffee Pavlodar</p>
</footer>
</body>
</html>

