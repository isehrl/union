<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $item_id = intval($_POST['item_id']);
    $quantity = intval($_POST['quantity']);

    $stmt = $conn->prepare("INSERT INTO orders (user_id, item_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $user_id, $item_id, $quantity);
    $stmt->execute();
    $stmt->close();

    header("Location: my_orders.php");
    exit;
}
?>
