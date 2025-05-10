<?php
include 'components/connection.php';
session_start();

if (isset($_POST['update_stock'])) {
    $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_STRING);
    $new_stock = filter_var($_POST['new_stock'], FILTER_SANITIZE_NUMBER_INT);

    $update_stock = $conn->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
    $update_stock->execute([$new_stock, $product_id]);

    if ($update_stock) {
        $success_msg[] = "تم تحديث المخزون بنجاح.";
    } else {
        $warning_msg[] = "حدث خطأ أثناء تحديث المخزون.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Stock</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background-color: #f8f9fa;
    font-family: 'Arial', sans-serif;
}

.container {
    max-width: 600px;
    margin: 50px auto;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #343a40;
}

.success {
    color: #155724;
    background-color: #d4edda;
    padding: 10px;
    border: 1px solid #c3e6cb;
    margin: 10px 0;
    border-radius: 5px;
    text-align: center;
}

.warning {
    color: #721c24;
    background-color: #f8d7da;
    padding: 10px;
    border: 1px solid #f5c6cb;
    margin: 10px 0;
    border-radius: 5px;
    text-align: center;
}

form label {
    font-weight: bold;
    margin-top: 10px;
}

form input, form button {
    margin-top: 10px;
}

button {
    width: 100%;
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background-color: #0056b3;
}
</style>
</head>
<body>
    <div class="container">
        <h1>Update Stock</h1>
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
        <form method="post">
            <label for="product_id">Product ID:</label>
            <input type="text" name="product_id" class="form-control" required>
            <label for="new_stock">Add Stock:</label>
            <input type="number" name="new_stock" class="form-control" required min="1">
            <button type="submit" name="update_stock" class="btn btn-primary">Update Stock</button>
        </form>
    </div>
</body>
</html>