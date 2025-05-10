<?php
include 'components/connection.php';
session_start();

if (isset($_GET['pid'])) {
    $product_id = filter_var($_GET['pid'], FILTER_SANITIZE_STRING);

    $select_product = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $select_product->execute([$product_id]);

    if ($select_product->rowCount() > 0) {
        $product = $select_product->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "<p class='warning'>المنتج غير موجود.</p>";
        exit;
    }
} else {
    echo "<p class='warning'>لم يتم تحديد المنتج.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<title>تفاصيل المنتج</title>
<style>
    .product-details {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
    }
    .product-details img {
        max-width: 100%;
        height: auto;
        margin-bottom: 20px;
    }
    .product-details h1 {
        font-size: 24px;
        margin-bottom: 10px;
    }
    .product-details p {
        margin-bottom: 10px;
    }
    .product-details .price {
        font-size: 20px;
        color: green;
        margin-bottom: 20px;
    }
</style>
</head>
<body>
    <?php include 'components/header.php'; ?>
    <div class="product-details">
        <img src="img/<?= $product['image']; ?>" alt="<?= $product['name']; ?>">
        <h1><?= $product['name']; ?></h1>
        <p><?= $product['description']; ?></p>
        <p class="price">السعر: $<?= $product['price']; ?></p>
        <p>المخزون المتوفر: <?= $product['stock']; ?></p>
        <a href="view_products.php" class="btn">العودة إلى المتجر</a>
    </div>
    <?php include 'components/footer.php'; ?>
</body>
</html>