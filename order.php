<?php
include 'components/connection.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<style type="text/css">
<?php include 'style.css'; ?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<title>Green Coffee - order page</title>
</head>
<body>
    <?php include 'components/header.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>my order</h1>
        </div>
        <div class="title2">
            <a href="home.php">home</a><span> order</span>
        </div>
        <section class="orders">
            <div class="title">
                <img src="img/download.png" class="logo">
                <h1>my order</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto dolorum deserunt minus veniam tenetur.</p>
            </div>
            <div class="box-container">
                <?php 
                $select_orders = $conn->prepare("
                    SELECT orders.*, products.name AS product_name, products.image AS product_image 
                    FROM orders 
                    JOIN products ON orders.product_id = products.id 
                    WHERE orders.user_id = ? 
                    ORDER BY orders.date DESC
                ");
                $select_orders->execute([$user_id]);

                if ($select_orders->rowCount() > 0) {
                    while ($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                ?>
                        <div class="box" style="border: 2px solid; width: 300px; margin: 10px auto;">
                            <a href="view_order.php?get_id=<?= $fetch_order['id']; ?>">
                                <p class="date">
                                    <i class="bi bi-calendar-fill"></i>
                                    <span><?= $fetch_order['date']; ?></span>
                                </p>
                                <img src="img/<?= $fetch_order['product_image']; ?>" class="image" alt="Product Image">
                                <div class="row">
                                    <h3 class="name"><?= $fetch_order['product_name']; ?></h3>
                                    <p class="price">Price: $<?= $fetch_order['price']; ?> x <?= $fetch_order['qty']; ?></p>
                                    <p class="status" style="color:<?php 
                                        if ($fetch_order['status'] == 'delivered') {
                                            echo 'green';
                                        } elseif ($fetch_order['status'] == 'canceled') {
                                            echo 'red';
                                        } else {
                                            echo 'orange';
                                        }
                                    ?>">
                                        <?= $fetch_order['status']; ?>
                                    </p>
                                </div>
                            </a>
                        </div>
                <?php
                    }
                } else {
                    echo '<p class="empty">No orders placed yet.</p>';
                }
                ?>
            </div>
        </section>
        <?php include 'components/footer.php'; ?>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js" defer></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>