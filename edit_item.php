<?php
// edit_item.php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Missing item ID");
}

$stmt = $conn->prepare("SELECT * FROM menu_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    die("Item not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Item</title>
</head>
<body>
    <h1>Edit Menu Item</h1>
    <form action="update_item.php" method="post">
        <input type="hidden" name="id" value="<?= $item['id'] ?>">
        <label>Title: <input type="text" name="title" value="<?= htmlspecialchars($item['name']) ?>" required></label><br>
        <label>Category: <input type="text" name="category" value="<?= htmlspecialchars($item['category']) ?>" required></label><br>
        <label>Description:<br><textarea name="description" required><?= htmlspecialchars($item['description']) ?></textarea></label><br>
        <label>Price (₸): <input type="number" name="price" value="<?= $item['price'] ?>" required></label><br>
        <label>Image Path: <input type="text" name="image_path" value="<?= htmlspecialchars($item['image_path']) ?>" required></label><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
