<?php
include 'components/connection.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = null;
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("location: login.php");
    exit;
}

if (isset($_POST['submit-btn'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['massage'], FILTER_SANITIZE_STRING);

    // إدخال الرسالة في قاعدة البيانات
    $insert_message = $conn->prepare("INSERT INTO messages (user_id, name, email, number, message) VALUES (?, ?, ?, ?, ?)");
    $insert_message->execute([$user_id, $name, $email, $number, $message]);
    if ($insert_message) {
        echo "<script>alert('Your message has been sent successfully and saved in the database!');</script>";
    } else {
        echo "<script>alert('Failed to save your message. Please try again later.');</script>";
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
<title>Green Coffee - Contact Us</title>
</head>
<body>
    <?php include 'components/header.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>Contact Us</h1>
        </div>
        <div class="title2">
            <a href="home.php">Home</a><span>Contact Us</span>
        </div>
        <section class="services">
            <div class="box-contanier">
                <div class="box">
                    <img src="img/icon2.png" alt="">
                    <div class="detail">
                        <h3>Great Saving</h3>
                        <p>Save big every order</p>
                    </div>
                </div>
                <div class="box">
                    <img src="img/icon1.png" alt="">
                    <div class="detail">
                        <h3>24*7 Support</h3>
                        <p>Save big every order</p>
                    </div>
                </div>
                <div class="box">
                    <img src="img/icon0.png" alt="">
                    <div class="detail">
                        <h3>Gift Vouchers</h3>
                        <p>Vouchers on every festival</p>
                    </div>
                </div>
                <div class="box">
                    <img src="img/icon.png" alt="">
                    <div class="detail">
                        <h3>Worldwide Delivery</h3>
                        <p>Save big every order</p>
                    </div>
                </div>
            </div>
        </section>
        <div class="form-container">
            <form method="post">
                <div class="title">
                    <img src="img/download.png" alt="logo">
                    <h1>Leave a Message</h1>
                </div>
                <div class="input-field"> 
                    <p>Your Name <sup>*</sup></p>
                    <input type="text" name="name" required>
                </div>
                <div class="input-field"> 
                    <p>Your Email <sup>*</sup></p>
                    <input type="email" name="email" required>
                </div>
                <div class="input-field"> 
                    <p>Your Number <sup>*</sup></p>
                    <input type="text" name="number" required>
                </div>
                <div class="input-field"> 
                    <p>Your Message <sup>*</sup></p>
                    <textarea name="massage" required></textarea>
                </div>
                <button type="submit" name="submit-btn" class="btn">Send Message</button>
            </form>
        </div>
        <div class="address">
            <div class="title">
                <img src="img/download.png" alt="logo">
                <h1>Contact Details</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>
            <div class="box-container">
                <div class="box">
                    <i class="bx bxs-map-pin"></i>
                    <div>
                        <h4>Address</h4>
                        <p>NEW CAIRO</p>
                    </div>
                </div>
                <div class="box">
                    <i class="bx bxs-phone-call"></i>
                    <div>
                        <h4>Phone</h4>
                        <p>123456789</p>
                    </div>
                </div>
                <div class="box">
                    <i class="bx bxs-map-pin"></i>
                    <div>
                        <h4>Email</h4>
                        <p>abdrhmanahmed123@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'components/footer.php'; ?>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js" defer></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>