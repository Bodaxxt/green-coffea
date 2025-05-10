<?php
include 'components/connection.php';
session_start();

if(isset($_SESSION['user_id'])){
    $user_id=$_SESSION['user_id'];
}else{
    $user_id='';
}
// register user 
error_reporting(E_ERROR | E_PARSE);

if(isset($_POST['submit'])){
    $email=$_POST['email'];
    $email=filter_var($email, FILTER_SANITIZE_STRING);
    $pass=$_POST['pass'];
    $pass=filter_var($pass, FILTER_SANITIZE_STRING);
    
    $select_user=$conn->prepare("SELECT * FROM users where email=? AND password=?;");
    $select_user->execute([$email, $pass]);
    $row=$select_user->fetch(PDO :: FETCH_ASSOC);

    if($select_user->rowCount()>0){
    $_SESSION['user_id']= $row['id'];
    $_SESSION['user_name']= $row['name'];
    $_SESSION['user_email']= $row['email'];
    header('location: home.php');
    }else{
    $message[]='incorrect username or password ';
    echo 'incorrect username or password ';
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
    <title>green tea -login now</title>
</head>
<body>
    <div class="main-container">
        <section class="form-container">
            <div class="title">
                <img src="img/download.png" alt="logo">
                <h1>Login now</h1>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit.</p>
            </div>
            <form method="post" action="">
                
                <div class="input-field">
                    <p>your email <span>:</span></p>
                    <input type="email" name="email" required placeholder="enter your email" maxlength="50"
                    oninput="this.value= this.value.replace(/\s\g '')">
                </div>
                <div class="input-field">
                    <p>your password <span>:</span></p>
                    <input type="password" name="pass" required placeholder="enter your password" maxlength="50"
                    oninput="this.value= this.value.replace(/\s\g '')">
                </div>
              
                <input type="submit" name="submit" value="register now" class="btn">
                <p>do not have an account ? <a href="register.php">register now</a></p>
            </form>
        </section>
    </div>
</body>
</html>