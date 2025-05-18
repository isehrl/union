<?php
// add_item.php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image_path']; // Just the path for now

    $stmt = $conn->prepare("INSERT INTO menu_items (name, category, description, price, image_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $title, $category, $description, $price, $image);
    $stmt->execute();

    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Item</title>
</head>
<body>
    <h1>Add Menu Item</h1>
    <form action="" method="post">
        <label>Title: <input type="text" name="title" required></label><br>
        <label>Category: <input type="text" name="category" required></label><br>
        <label>Description:<br><textarea name="description" required></textarea></label><br>
        <label>Price (₸): <input type="number" name="price" required></label><br>
        <label>Image Path: <input type="text" name="image_path" required></label><br>
        <button type="submit">Add</button>
    </form>
</body>
</html>
