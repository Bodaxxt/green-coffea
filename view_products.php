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

// إضافة المنتج إلى المفضلة
if (isset($_POST['add_to_wishlist'])) {
    $id = unique_id();
    $product_id = $_POST['product_id'];

    $verify_wishlist = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?;");
    $verify_wishlist->execute([$user_id, $product_id]);

    $verify_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $verify_cart->execute([$user_id, $product_id]);

    if ($verify_wishlist->rowCount() > 0) {
        $warning_msg[] = 'المنتج موجود بالفعل في المفضلة.';
    } elseif ($verify_cart->rowCount() > 0) {
        $warning_msg[] = 'المنتج موجود بالفعل في سلة المشتريات.';
    } else {
        $select_price = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
        $select_price->execute([$product_id]);
        $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

        $insert_wishlist = $conn->prepare("INSERT INTO wishlist (id, user_id, product_id, price) VALUES (?, ?, ?, ?)");
        $insert_wishlist->execute([$id, $user_id, $product_id, $fetch_price['price']]);

        $success_msg[] = 'تمت إضافة المنتج إلى المفضلة بنجاح.';
    }
}
// إضافة المنتج إلى السلة
if (isset($_POST['add_to_cart'])) {
    $id = unique_id();
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_STRING);
    $qty = filter_var($_POST['qty'], FILTER_SANITIZE_STRING);

    if ($qty < 1) {
        $qty = 1;
    }

    $verify_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $verify_cart->execute([$user_id, $product_id]);

    $max_cart_items = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $max_cart_items->execute([$user_id]);

    if ($verify_cart->rowCount() > 0) {
        $warning_msg[] = 'المنتج موجود بالفعل في سلة المشتريات.';
    } elseif ($max_cart_items->rowCount() >= 20) {
        $warning_msg[] = 'لا يمكنك إضافة المزيد من المنتجات. السلة ممتلئة.';
    } else {
        $select_price = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
        $select_price->execute([$product_id]);
        $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

        if (!$fetch_price) {
            $warning_msg[] = 'المنتج غير موجود أو نفد من المخزون.';
        } else {
            $insert_cart = $conn->prepare("INSERT INTO cart (id, user_id, product_id, price, qty) VALUES (?, ?, ?, ?, ?)");
            $insert_cart->execute([$id, $user_id, $product_id, $fetch_price['price'], $qty]);
            $success_msg[] = 'تمت إضافة المنتج إلى السلة بنجاح.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<style type="text/css">
<?php include 'style.css';?>
</style>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<title>Green Coffee - shop page</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>
<body>
    <?php include 'components/header.php'; ?>
    <?php
    if (!empty($success_msg)) {
        foreach ($success_msg as $msg) {
            echo "<script>swal('Success', '$msg', 'success');</script>";
        }
    }
    if (!empty($warning_msg)) {
        foreach ($warning_msg as $msg) {
            echo "<script>swal('Warning', '$msg', 'warning');</script>";
        }
    }
    ?>
    <div class="main">
        <div class="banner">
            <h1>shop</h1>
        </div>
        <section class="products">
            <div class="box-container">
                <?php
                $select_products = $conn->prepare("SELECT * FROM products WHERE stock > 0");
                $select_products->execute();

                if ($select_products->rowCount() > 0) {
                    while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <form method="post" class="box" id="product-form-<?=$fetch_products['id'];?>">
                    <img src="img/<?=$fetch_products['image'];?>" alt="" class="img">
                    <div class="button">
                        <button type="submit" name="add_to_cart" class="add-to-cart-btn" data-id="<?=$fetch_products['id'];?>">
                            <i class="bx bx-cart"></i>
                        </button>
                        <button type="submit" name="add_to_wishlist" class="add-to-wishlist-btn" data-id="<?=$fetch_products['id'];?>">
                            <i class="bx bx-heart"></i>
                        </button>
                        <a href="view_page.php?pid=<?=$fetch_products['id'];?>" class="bx bxs-show"></a>
                    </div>
                    <div class="name"><?=$fetch_products['name']; ?></div>
                    <input type="hidden" name="product_id" value="<?=$fetch_products['id'];?>">
                    <div class="flex">
                        <p class="price">price $<?=$fetch_products['price'];?>/-</p>
                        <p class="stock">Stock: <?=$fetch_products['stock'];?></p>
                        <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty">
                    </div>
                    <a href="checkout.php?get_id=<?=$fetch_products['id'];?>" class="btn">buy now</a>
                </form>
                <?php 
                    }
                } else {
                    echo "<p class='empty'>No products added yet!</p>";
                }
                ?>
            </div>
        </section>
    </div>
</body>
</html>