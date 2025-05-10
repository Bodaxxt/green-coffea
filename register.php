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
    $id = unique_id();
    $name=$_POST['name'];
    $name=filter_var($name, FILTER_SANITIZE_STRING);
    $email=$_POST['email'];
    $email=filter_var($email, FILTER_SANITIZE_STRING);
    $pass=$_POST['pass'];
    $pass=filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass=$_POST['cpass'];
    $cpass=filter_var($cpass, FILTER_SANITIZE_STRING);
    $select_user=$conn->prepare("select * from users where email=?;");
    $select_user->execute([$email]);
    $row=$select_user->fetch(PDO :: FETCH_ASSOC);
    if($pass !=$cpass){
        $message[]='confirm your password !!'; 
        echo 'confirm your password !!';
   }else{
     
    if($select_user->rowCount() > 0){
        $message[]='email alrealy exist';
        echo 'email alrealy exist';
   }else{
            $row=$select_user->fetch(PDO :: FETCH_ASSOC);
            $insert_user = $conn->prepare("INSERT into users(id,name,email,password) values(?,?,?,?);");
            
            $insert_user->execute([$id,$name,$email,$pass]);
            header('location: login.php');

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
    <title>green tea -register now</title>
</head>
<body>
    <div class="main-container">
        <section class="form-container">
            <div class="title">
                <img src="img/download.png" alt="logo">
                <h1>register now</h1>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit.</p>
            </div>
            <form method="post" action="">
                <div class="input-field">
                    <p>your name <span>:</span></p>
                    <input type="text" name="name" required placeholder="enter your name" maxlength="50">
                </div>
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
                <div class="input-field">
                    <p>confirm password <span>:</span></p>
                    <input type="password" name="cpass" required placeholder="confirm your password" maxlength="50"
                    oninput="this.value= this.value.replace(/\s\g '')">
                </div>
                <input type="submit" name="submit" value="register now" class="btn">
                <p>already have an account ? <a href="login.php">login now</a></p>
            </form>
        </section>
    </div>
</body>
</html>