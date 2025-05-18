<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$isAdmin = ($_SESSION['user_role'] ?? '') === 'admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home | Union Coffee Pavlodar</title>
    <link rel="stylesheet" href="menu.css"> <!-- Reuse your existing CSS -->
    <style>
        main.home-content {
            max-width: 800px;
            margin: 50px auto;
            padding: 0 15px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        main.home-content h2 {
            font-size: 2.2rem;
            margin-bottom: 20px;
            color: #8B4513;
        }

        main.home-content p, main.home-content ul {
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        main.home-content ul.services-list {
            list-style: none;
            padding: 0;
            columns: 2; /* Two columns for better layout */
            column-gap: 40px;
            color: #555;
        }

        main.home-content ul.services-list li {
            margin-bottom: 8px;
        }

        main.home-content .address {
            font-weight: 600;
            margin-bottom: 25px;
            font-size: 1.2rem;
            color: #5a3e1b;
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

<main class="home-content">
    <h2>Welcome to Union Coffee Pavlodar!</h2>
    <p>Union Coffee Pavlodar is your cozy corner for delicious breakfasts, fresh salads, hearty soups, and tasty hot dishes. We pride ourselves on serving quality meals with fresh ingredients in a warm and welcoming atmosphere.</p>
    <p>Whether you're stopping by for your morning coffee or looking for a hearty lunch, our diverse menu offers something for every taste. Our friendly staff is here to make your visit unforgettable.</p>
    <p>Enjoy your meal and have a great day!</p>

    <div class="address">📍 Address: Krivenko Street, 23</div>

    <h3>Our Services and Features</h3>
    <ul class="services-list">
        <li>Pan-Asian cuisine</li>
        <li>European cuisine</li>
        <li>Business lunch</li>
        <li>Breakfast</li>
        <li>Table reservation</li>
        <li>Takeaway orders</li>
        <li>Wine list</li>
        <li>Summer terrace</li>
        <li>Kids' corner</li>
        <li>Children's room</li>
        <li>Animators and master classes for children</li>
        <li>Laptop-friendly environment</li>
        <li>Average check 3000 KZT</li>
        <li>Lunch from 2500 KZT</li>
        <li>Breakfast 00:00–00:00</li>
        <li>Lunch 12:00–15:00</li>
        <li>Up to 50 seats</li>
        <li>Delivery services</li>
        <li>Additional Wi-Fi</li>
        <li>Payment methods:</li>
        <li>Card payments</li>
        <li>Cash payments</li>
        <li>Card transfers</li>
        <li>QR code payments</li>
    </ul>
</main>

<footer>
    <p>&copy; 2025 Union Coffee Pavlodar</p>
</footer>
</body>
</html>
