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

if (isset($_POST['place_order'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $address = filter_var($_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ', ' . $_POST['pincode'], FILTER_SANITIZE_STRING);
    $address_type = filter_var($_POST['address_type'], FILTER_SANITIZE_STRING);
    $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);

    $verify_cart = $conn->prepare("SELECT * FROM cart WHERE user_id=?");
    $verify_cart->execute([$user_id]);

    if (isset($_GET['get_id'])) {
        $get_product = $conn->prepare("SELECT * FROM products WHERE id=? LIMIT 1");
        $get_product->execute([$_GET['get_id']]);

        if ($get_product->rowCount() > 0) {
            while ($fetch_p = $get_product->fetch(PDO::FETCH_ASSOC)) {
                if ($fetch_p['stock'] >= 1) {
                    $update_stock = $conn->prepare("UPDATE products SET stock = stock - 1 WHERE id = ?");
                    $update_stock->execute([$fetch_p['id']]);

                    $insert_order = $conn->prepare("INSERT INTO orders (id, user_id, name, number, email, address, address_type, method, product_id, price, qty) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
                    $insert_order->execute([unique_id(), $user_id, $name, $number, $email, $address, $address_type, $method, $fetch_p['id'], $fetch_p['price'], 1]);

                    $success_msg[] = "تمت عملية الشراء بنجاح.";
                } else {
                    $warning_msg[] = "الكمية المطلوبة غير متوفرة.";
                }
            }
        } else {
            $warning_msg[] = "المنتج غير موجود.";
        }
    } elseif ($verify_cart->rowCount() > 0) {
        while ($f_cart = $verify_cart->fetch(PDO::FETCH_ASSOC)) {
            $select_product = $conn->prepare("SELECT stock FROM products WHERE id = ?");
            $select_product->execute([$f_cart['product_id']]);
            $fetch_stock = $select_product->fetch(PDO::FETCH_ASSOC);

            if ($fetch_stock['stock'] >= $f_cart['qty']) {
                $update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $update_stock->execute([$f_cart['qty'], $f_cart['product_id']]);

                $insert_order = $conn->prepare("INSERT INTO orders (id, user_id, name, number, email, address, address_type, method, product_id, price, qty) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
                $insert_order->execute([unique_id(), $user_id, $name, $number, $email, $address, $address_type, $method, $f_cart['product_id'], $f_cart['price'], $f_cart['qty']]);
            } else {
                $warning_msg[] = "الكمية المطلوبة غير متوفرة للمنتج: " . $f_cart['product_id'];
            }
        }

        $delete_cart_id = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $delete_cart_id->execute([$user_id]);

        $success_msg[] = "تمت عملية الشراء بنجاح.";
        header('location: order.php');
    } else {
        $warning_msg[] = 'السلة فارغة أو حدث خطأ.';
    }
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
<title>Green Coffee - checkout page</title>
</head>
<body>
    <?php include 'components/header.php';?>
    <?php
    if (!empty($success_msg)) {
        foreach ($success_msg as $msg) {
            echo "<p class='success'>$msg</p>";
        }
    }
    if (!empty($warning_msg)) {
        foreach ($warning_msg as $msg) {
            echo "<p class='warning'>$msg</p>";
        }
    }
    ?>
    <div class="main">
        <div class="banner">
            <h1>checkout summary</h1>
        </div>
        <div class="title2">
            <a href="home.php">home</a><span> checkout summary</span>
        </div>
        <section class="checkout">
            <div class="title">
                <img src="img/download.png" class="logo">
                <h1>checkout summary</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto dolorum deserunt minus veniam</p>
            </div>
            <div class="row">
                <form method="post">
                    <h3>billing details</h3>
                    <div class="flex">
                        <div class="box">
                            <div class="input-field">
                                <p>your name <span>*</span></p>
                                <input type="text" name="name" required maxlength="50" placeholder="Enter your name" class="input">
                            </div>
                            <div class="input-field">
                                <p>your number <span>*</span></p>
                                <input type="number" name="number" required maxlength="15" placeholder="Enter your number">
                            </div>
                            <div class="input-field">
                                <p>your email <span>*</span></p>
                                <input type="email" name="email" required maxlength="50" placeholder="Enter your email">
                            </div>
                            <div class="input-field">
                                <p>payment method <span>*</span></p>
                                <select name="method" class="input">
                                    <option value="cash on delivery">cash on delivery</option>
                                    <option value="credit card">credit card</option>
                                    <option value="net banking">net banking</option>
                                    <option value="vodafone cash">vodafone cash</option>
                                    <option value="instapay">instapay</option>
                                </select>
                            </div>
                            <div class="input-field">
                                <p>address <span>*</span></p>
                                <select name="address_type" class="input">
                                    <option value="home">home</option>
                                    <option value="office">office</option>
                                </select>
                            </div>
                        </div>
                        <div class="box">
                            <div class="input-field">
                                <p>address line 1 <span>*</span></p>
                                <input type="text" name="flat" required maxlength="50" placeholder="flat & building number">
                            </div>
                            <div class="input-field">
                                <p>address line 2 <span>*</span></p>
                                <input type="text" name="street" required maxlength="50" placeholder="street">
                            </div>
                            <div class="input-field">
                                <p>city name <span>*</span></p>
                                <input type="text" name="city" required maxlength="50" placeholder="enter your city name">
                            </div>
                            <div class="input-field">
                                <p>country <span>*</span></p>
                                <input type="text" name="country" required maxlength="50" placeholder="enter your country name">
                            </div>
                            <div class="input-field">
                                <p>pincode <span>*</span></p>
                                <input type="text" name="pincode" required maxlength="7" placeholder="enter your pincode">
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="place_order" class="btn">place order</button>
                </form>
                <div class="summary">
                    <h3>my bag</h3>
                    <div class="box-container">
                        <?php
                        $grand_total = 0;
                        if (isset($_GET['get_id'])) {
                            $select_get = $conn->prepare("SELECT * FROM products WHERE id = ?");
                            $select_get->execute([$_GET['get_id']]);
                            while ($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)) {
                                $sub_total = $fetch_get['price'];
                                $grand_total += $sub_total;
                        ?>
                        <div class="flex">
                            <img src="img/<?=$fetch_get['image']; ?>" class="image">
                            <div>
                                <h3 class="name"><?=$fetch_get['name']; ?></h3>
                                <p class="price"><?=$fetch_get['price']; ?>/-</p>
                            </div>
                        </div>
                        <?php
                            }
                        } else {
                            $select_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
                            $select_cart->execute([$user_id]);
                            if ($select_cart->rowCount() > 0) {
                                while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                                    $select_products = $conn->prepare("SELECT * FROM products WHERE id = ?");
                                    $select_products->execute([$fetch_cart['product_id']]);
                                    $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
                                    $sub_total = ($fetch_cart['qty'] * $fetch_product['price']);
                                    $grand_total += $sub_total;
                        ?>
                        <div class="flex">
                            <img src="img/<?=$fetch_product['image']; ?>">
                            <div>
                                <h3 class="name"><?=$fetch_product['name']; ?></h3>
                                <p class="price"><?=$fetch_product['price']; ?> x <?=$fetch_cart['qty']; ?></p>
                            </div>
                        </div>
                        <?php
                                }
                            } else {
                                echo '<p class="empty">your cart is empty</p>';
                            }
                        }
                        ?>
                    </div>
                    <div class="grand-total"><span>total amount payable: </span>$<?=$grand_total ?>/-</div>
                </div>
            </div>
        </section>
        <?php include 'components/footer.php';?>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js" defer></script>
    <?php include 'components/alert.php';?>
</body>
</html>