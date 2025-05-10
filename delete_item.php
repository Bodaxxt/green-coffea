<?php
include 'components/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = filter_var($_POST['cart_id'], FILTER_SANITIZE_STRING);

    // التحقق من وجود العنصر في السلة
    $verify_item = $conn->prepare("SELECT * FROM cart WHERE id = ?");
    $verify_item->execute([$cart_id]);

    if ($verify_item->rowCount() > 0) {
        // حذف العنصر
        $delete_item = $conn->prepare("DELETE FROM cart WHERE id = ?");
        $delete_item->execute([$cart_id]);
        echo "Item deleted successfully";
    } else {
        echo "Item not found in cart";
    }
} else {
    echo "Invalid request";
}
?>