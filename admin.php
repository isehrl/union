<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch all menu items
$result = $conn->query("SELECT * FROM menu_items ORDER BY category, id");
$menu_items = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel | Union Coffee</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<header>
    <h1>Admin Panel</h1>
    <nav>
        <a href="add_item.php">Add New Item</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menu_items as $item): ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= htmlspecialchars($item['category']) ?></td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['description']) ?></td>
                    <td><?= htmlspecialchars($item['price']) ?>₸</td>
                    <td><img src="<?= htmlspecialchars($item['image_path']) ?>" alt="" width="60"></td>
                    <td>
                        <a href="edit_item.php?id=<?= $item['id'] ?>">Edit</a> |
                        <a href="delete_item.php?id=<?= $item['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<footer>
    <p>&copy; 2025 Union Coffee Pavlodar</p>
</footer>
</body>
</html>

