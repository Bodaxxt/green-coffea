<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Status</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg,rgb(16, 175, 77),rgb(21, 109, 4));
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #fff;
        }
        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 400px;
            width: 100%;
            backdrop-filter: blur(10px);
        }
        .container h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #ffdfba;
        }
        .container p {
            font-size: 18px;
            margin-bottom: 20px;
            color: #ffe6e6;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background:rgb(96, 235, 108);
            color: #fff;
            text-decoration: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .btn:hover {
            background:rgb(16, 179, 43);
            transform: scale(1.1);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Save the email to the database or send it to an API
                echo "<h1>Thank You!</h1>";
                echo "<p>Your subscription was successful.</p>";
            } else {
                echo "<h1>Oops!</h1>";
                echo "<p>Invalid email address. Please try again.</p>";
            }
        }
        ?>
        <a href="javascript:history.back()" class="btn">Go Back</a>
    </div>
</body>
</html>