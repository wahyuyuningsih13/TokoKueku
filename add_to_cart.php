<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'redirect' => true]);
    exit();
}

require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();
$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

// Check if product already in cart
$query = "SELECT id, quantity FROM cart WHERE user_id = :user_id AND product_id = :product_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':product_id', $product_id);
$stmt->execute();

if($stmt->rowCount() > 0) {
    $cart = $stmt->fetch(PDO::FETCH_ASSOC);
    $new_quantity = $cart['quantity'] + 1;
    $query = "UPDATE cart SET quantity = :quantity WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':quantity', $new_quantity);
    $stmt->bindParam(':id', $cart['id']);
} else {
    $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, 1)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
}

if($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menambahkan ke keranjang']);
}
?>