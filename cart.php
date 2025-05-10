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

//update product in cart

if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);
    $qty = $_POST['qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_STRING);

    if ($qty < 1) {
        $qty = 1; // الحد الأدنى للكمية
    }

    $update_qty = $conn->prepare("UPDATE `cart` SET qty = ? WHERE id = ?");
    $update_qty->execute([$qty, $cart_id]);

    $success_msg[] = 'Cart quantity updated successfully';
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
<title>Green Coffee - Cart</title>
</head>
<body>
    <?php include 'components/header.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>My Cart</h1>
        </div>
        <div class="title2">
            <a href="home.php">Home </a><span>Cart</span>
        </div>
        <section class="products">
            <h1 class="title">Products Added in Cart</h1>
            <div class="box-container">
                <?php
                $grand_total = 0;
                $select_cart = $conn->prepare("
                    SELECT cart.*, products.name, products.image, products.price 
                    FROM cart 
                    JOIN products ON cart.product_id = products.id 
                    WHERE cart.user_id = ?
                ");
                $select_cart->execute([$user_id]);
                if ($select_cart->rowCount() > 0) {
                    while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        $sub_total = $fetch_cart['qty'] * $fetch_cart['price'];
                        $grand_total += $sub_total;
                        ?>
                        <div class="box">
                            <input type="hidden" class="cart-id" value="<?= $fetch_cart['id']; ?>">
                            <img src="img/<?= $fetch_cart['image']; ?>" class="img">
                            <h3 class="name"><?= $fetch_cart['name']; ?></h3>
                            <div class="flex">
                                <p class="price">Price: $<?= $fetch_cart['price']; ?>/-</p>
                                <input type="number" class="qty" min="1" max="99" value="<?= $fetch_cart['qty']; ?>" maxlength="2">
                                <button class="update-cart bx bxs-edit"></button>
                            </div>
                            <p class="sub-total">Sub Total: <span>$<?= $sub_total; ?></span></p>
                            <button class="delete-item btn">Delete</button>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p class='empty'>No products added yet!</p>";
                }
                ?>
            </div>
            <?php if ($grand_total != 0) { ?>
            <div class="cart-total">
                <p>Total Amount Payable: <span>$<?= $grand_total; ?>/-</span></p>
                <div class="button">
                    <button class="empty-cart btn">Empty Cart</button>
                    <a href="checkout.php" class="btn">Proceed to Checkout</a>
                </div>
            </div>
            <?php } ?>
        </section>
        <?php include 'components/footer.php'; ?>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Update Cart Quantity
    document.querySelectorAll('.update-cart').forEach(button => {
        button.addEventListener('click', function () {
            const box = this.closest('.box');
            const cartId = box.querySelector('.cart-id').value;
            const qty = box.querySelector('.qty').value;

            fetch('update_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `cart_id=${cartId}&qty=${qty}`
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            });
        });
    });

    // Delete Item from Cart
    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', function () {
            const box = this.closest('.box');
            const cartId = box.querySelector('.cart-id').value;

            if (confirm('Are you sure you want to delete this item?')) {
                fetch('delete_item.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `cart_id=${cartId}`
                })
                .then(response => response.text())
                .then(data => {
                    alert(data); // عرض رسالة النجاح أو الخطأ
                    location.reload(); // تحديث الصفحة
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });

    // Empty Cart
    document.querySelector('.empty-cart').addEventListener('click', function () {
        if (confirm('Are you sure you want to empty your cart?')) {
            fetch('empty_cart.php', {
                method: 'POST'
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // عرض رسالة النجاح أو الخطأ
                location.reload(); // تحديث الصفحة
            })
            .catch(error => console.error('Error:', error));
        }
    });
});
</script>
</body>
</html>