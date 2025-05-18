<?php
// update_item.php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_POST['id'];
$title = $_POST['title'];
$category = $_POST['category'];
$description = $_POST['description'];
$price = $_POST['price'];
$image = $_POST['image_path'];

$stmt = $conn->prepare("UPDATE menu_items SET name = ?, category = ?, description = ?, price = ?, image_path = ? WHERE id = ?");
$stmt->bind_param("sssdsi", $title, $category, $description, $price, $image, $id);
$stmt->execute();

header("Location: admin.php");
exit;
?>