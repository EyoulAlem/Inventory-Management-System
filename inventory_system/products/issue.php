<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

if (!isset($_GET['id'])) {
    header("Location: list.php");
    exit;
}

$id = (int) $_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quantity_to_reduce = (int) $_POST['quantity'];

    if ($quantity_to_reduce <= 0 || $quantity_to_reduce > $product['quantity']) {
        $error = "Invalid quantity.";
    } else {
        $new_quantity = $product['quantity'] - $quantity_to_reduce;
        $conn->query("UPDATE products SET quantity = $new_quantity WHERE id = $id");
        $success = "Quantity updated successfully.";
        // Refresh product data
        $product['quantity'] = $new_quantity;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Issue Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6fa; font-family: 'Poppins', sans-serif; }
        .container { max-width: 500px; margin-top: 80px; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="container">
    <h4 class="mb-3">Issue Product: <strong><?= htmlspecialchars($product['name']) ?></strong></h4>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="quantity" class="form-label">Current Quantity: <?= $product['quantity'] ?></label>
            <input type="number" name="quantity" class="form-control" min="1" max="<?= $product['quantity'] ?>" required>
        </div>
        <button type="submit" class="btn btn-warning">Issue / Reduce Stock</button>
        <a href="list.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
