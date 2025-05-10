<?php
include 'components/connection.php';
session_start();
if(isset($_SESSION['user_id'])){
    $user_id=$_SESSION['user_id'];
}else{
    $user_id='';
}
if(isset($_POST['logout'])){
    session_destroy();
    header("location: login.php");
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

<title>Green Coffee - home page</title>
</head><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
 integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" 
crossorigin="anonymous" referrerpolicy="no-referrer" />
<body>
    <?php include 'components/header.php';?>
        <div class="main">
            <div class="about">
                <div class="banner">
                <h1>about us</h1>
                </div>
                <div class="title2">
                    <a href="home.php">home</a><span>about</span>
                </div>
                <div class="about-category">
                    <div class="box">
                        <img src="img/3.webp" alt="">
                        <div class="detail">
                            <span> Coffee</span>
                            <h2>lemon Coffee</h2>
                            <a href="view_products.php" class="btn">shop now</a>

                        </div>
                    </div>
                    
                    <div class="box">
                        <img src="img/about.png" alt="">
                        <div class="detail">
                            <span> Coffee</span>
                            <h2>lemon Coffee</h2>
                            <a href="view_products.php" class="btn">shop now</a>

                        </div>
                    </div>
                    <div class="box">
                        <img src="img/1.webp" alt="">
                        <div class="detail">
                            <span> Coffee</span>
                            <h2>lemon Coffee</h2>
                            <a href="view_products.php" class="btn">shop now</a>

                        </div>
                    </div>
                    <div class="box">
                        <img src="img/3.webp" alt="">
                        <div class="detail">
                            <span> Coffee</span>
                            <h2>lemon Coffee</h2>
                            <a href="view_products.php" class="btn">shop now</a>

                        </div>
                    </div>
                </div>
                <section class="services">
                    <div class="title">
                        <img src="img/download.png" alt="" class="logo">
                        <h1>why choose us</h1>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatibus.</p>
                    </div>
                    <div class="box-contanier">
                        <div class="box">
                            <img src="img/icon2.png" alt="">
                            <div class="detail">
                                <h3>great saving</h3>
                                <p>save big every order</p>
                            </div>
                        </div>
                        <div class="box">
                            <img src="img/icon1.png" alt="">
                            <div class="detail">
                                <h3>24*7 support</h3>
                                <p>save big every order</p>
                            </div>
                        </div>
                        <div class="box">
                            <img src="img/icon0.png" alt="">
                            <div class="detail">
                                <h3>gift vouchers</h3>
                                <p>vouchers on every festival</p>

                            </div>
                        </div>
                        <div class="box">
                            <img src="img/icon.png" alt="">
                            <div class="detail">
                                <h3>worldwide delivery</h3>
                                <p>save big every order</p>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="about">
                    <div class="row">
                        <div class="img-box">
                            <img src="img/3.png" alt="">
                        </div>
                        <div class="detail">
                            <h1>visit  out  wibsite</h1>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatibus.</p>
                            <a href="view_products.php" class="btn">shop now</a>



                        </div>

                    </div>
                </div>
                <div class="tesstimonial-contanier">
                <div class="title">
                    <img src="img/download.png" alt="" class ="logo">
                    <h1> what people say about us</h1>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam dicta eligendi harum nostrum odio, quia possimus magni sequi obcaecati voluptas quaerat molestiae facere animi, ipsum, </p>
                    <div>
                </div>
                </div>
                
                <div class="container">
                    <div class="testimonial-item active"> 
                        <img src="img/01.jpg" alt="">
                        <h1>sara smith</h1>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                            Rem amet delectus quis iste 
                            itaque veritatis eum
                             libero nesciunt. Vel fugiat dolorum eaque neque vitae? 
                             Eum magnam repellendus nemo
                              cupiditate corporis?</p>
                    </div>
                    <div class="testimonial-item "> 
                        <img src="img/02.jpg" alt="">
                        <h1>jhon smith</h1>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                            Rem amet delectus quis iste 
                            itaque veritatis eum
                             libero nesciunt. Vel fugiat dolorum eaque neque vitae? 
                             Eum magnam repellendus nemo
                              cupiditate corporis?</p>
                    </div>
                    <div class="testimonial-item"> 
                        <img src="img/03.jpg" alt="">
                        <h1>lara smith</h1>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                            Rem amet delectus quis iste 
                            itaque veritatis eum
                             libero nesciunt. Vel fugiat dolorum eaque neque vitae? 
                             Eum magnam repellendus nemo
                              cupiditate corporis?</p>
                    </div>
                    <!-- <div class="left-arrow" onclick="nextSlide()"> <i class="bx bxs-left-arrow-alt"></i> </div>
                    <div class="right-arrow" onclick="prevSlide()">  <i class="bx bxs-rigth-arrow-alt"></i></div> -->
                </div>
               
               
                </div>
                
                <?php include 'components/footer.php';?>
            </div>
        </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="script.js" defer></script>
<?php include 'components/alert.php';?>
</body>
</html>