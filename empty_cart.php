<?php
include 'components/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];

    // التحقق من وجود عناصر في السلة
    $verify_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $verify_cart->execute([$user_id]);

    if ($verify_cart->rowCount() > 0) {
        // حذف جميع العناصر
        $empty_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $empty_cart->execute([$user_id]);
        echo "Cart emptied successfully";
    } else {
        echo "Cart is already empty";
    }
} else {
    echo "Invalid request";
}
?>