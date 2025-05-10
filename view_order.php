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

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    $get_id = '';
    header('location:order.php');
}

// Handle order cancellation
if (isset($_POST['cancel_order'])) {
    $cancel_order_id = $_POST['order_id'];
    $update_status = $conn->prepare("UPDATE orders SET status = 'canceled' WHERE id = ? AND user_id = ?");
    $update_status->execute([$cancel_order_id, $user_id]);
    header('location:order.php'); // Redirect to orders page
    exit;
}
?>

<style type="text/css">
<?php include 'style.css';?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<title>Green Coffee - order detail page</title>
</head>
<body>
    <?php include 'components/header.php';?>
        <div class="main">
        <div class="banner">
                <h1> order detail</h1>
                </div>
                <div class="title2">
                    <a href="home.php">home</a><span> order detail</span>
                </div>
                <section class="orders_detail">
                    
                        <div class="title">
                            <img src="img/download.png" class="logo" >
                            <h1>order detail </h1>
                            <p>lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto dolorum deserunt
                            minus veniam 
                            tenetur
                            </p>  
                             
                        </div>
                    <div class="box_container" >
                        <?php  
                            $grand_total=0;
                            $select_orders = $conn->prepare("SELECT id, date, name, number, email, address, status, product_id, price, qty FROM orders WHERE id = ? LIMIT 1");
                            $select_orders->execute([$get_id]);
                            if ($select_orders->rowCount()>0) {
                                while($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)){
                                    $select_products = $conn->prepare("SELECT * FROM products WHERE id = ? limit 1");
                                    $select_products ->execute([$fetch_order['product_id']]);
                                    if ($select_products->rowCount()>0) {
                                        while($fetch_product=$select_products->fetch(PDO::FETCH_ASSOC)){
                                            $sub_total=($fetch_order['price']* $fetch_order['qty']);
                                            $grand_total += $sub_total ;
                        ?>
                        <div class="box" >
                            <div class="col" >
                                <p class="title"><i class="bi bi-calendar-fill"></i><?= $fetch_order['date'];?></p>
                                <img src="img/<?= $fetch_product['image']; ?>" class="image">
                                <p class="price"><?= $fetch_product['price']; ?> x <?= $fetch_order['qty'];?> </p>
                                <h3 class="name"><?= $fetch_product['name']; ?></h3>
                                <p class="grand-total">total amount payable : <span>$<?= $grand_total; ?></span></p>
                            </div> 
                            <div class="col">
                                <p class="title">billing address </p>
                                <p class="user"><i class="bi bi-person-bounding-box"></i><?=$fetch_order['name']; ?> </p>
                                <p class="user"><i class="bi bi-phone"></i><?=$fetch_order['number']; ?> </p>
                                <p class="user"><i class="bi bi-envelope"></i><?=$fetch_order['email']; ?> </p>
                                <p class="user"><i class="bi bi-pen-map-fill"></i><?=$fetch_order['address']; ?> </p>
                                <p class="title">status</p>
                                <p class="status" style="color:<?php 
                                    if (isset($fetch_order['status'])) {
                                        if ($fetch_order['status'] == 'delivered') {
                                            echo 'green';
                                        } elseif ($fetch_order['status'] == 'canceled') {
                                            echo 'red';
                                        } else {
                                            echo 'orange';
                                        }
                                    } else {
                                        echo 'gray'; // Default color if status is not set
                                    }
                                ?>">
                                    <?= isset($fetch_order['status']) ? $fetch_order['status'] : 'Unknown'; ?>
                                </p>
                                <?php if ($fetch_order['status']=='canceled'){ ?>
                                    <a href="checkout.php?get_id=<?= $fetch_product['id'];?>" class="btn">order again </a>
                                <?php }else{ ?>
                                    <form method="post">
                                        <input type="hidden" name="order_id" value="<?= $fetch_order['id']; ?>">
                                        <button type="submit" name="cancel_order" class="btn" onclick="return confirm('Do you want to cancel this order?')">Cancel Order</button>
                                    </form> 

                                <?php }    ?>
                            </div>
                                
                        </div> 
                        <?php
                                 }
                                    }else{
                                        echo '<p class="empty">product not found</p>';

                                    }
                                }
                            }else{
                                echo '<p class="empty">no order found</p>';

                            }
                         ?>      
                    </div>    
                </section>
                <?php include 'components/footer.php';?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="script.js" defer></script>
<?php include 'components/alert.php';?>
</body>
</html>