<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

$error = "";
$success = "";

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: list.php");
    exit;
}

// Fetch product to edit
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$productResult = $stmt->get_result();
$product = $productResult->fetch_assoc();
$stmt->close();

if (!$product) {
    header("Location: list.php");
    exit;
}

// Fetch suppliers for dropdown
$suppliersResult = $conn->query("SELECT id, name FROM suppliers ORDER BY name ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);
    $supplier_id = $_POST['supplier_id'] ?: null;

    if (empty($name) || empty($category) || $quantity < 0 || $price <= 0) {
        $error = "Please fill in all required fields correctly.";
    } else {
        $stmt = $conn->prepare("UPDATE products SET name=?, category=?, quantity=?, price=?, supplier_id=? WHERE id=?");
        $stmt->bind_param("ssidii", $name, $category, $quantity, $price, $supplier_id, $id);

        if ($stmt->execute()) {
            $success = "Product updated successfully.";
            // Refresh product data after update
            $product['name'] = $name;
            $product['category'] = $category;
            $product['quantity'] = $quantity;
            $product['price'] = $price;
            $product['supplier_id'] = $supplier_id;
        } else {
            $error = "Failed to update product.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Product - Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            padding: 2rem;
        }
        .btn-primary {
            background-color: #764ba2;
            border: none;
        }
        .btn-primary:hover {
            background-color: #5a368a;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Edit Product</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name *</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($product['name']); ?>" required />
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category *</label>
                <input type="text" name="category" id="category" class="form-control" value="<?= htmlspecialchars($product['category']); ?>" required />
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity *</label>
                <input type="number" min="0" name="quantity" id="quantity" class="form-control" value="<?= htmlspecialchars($product['quantity']); ?>" required />
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price (USD) *</label>
                <input type="number" min="0" step="0.01" name="price" id="price" class="form-control" value="<?= htmlspecialchars($product['price']); ?>" required />
            </div>
            <div class="mb-3">
                <label for="supplier_id" class="form-label">Supplier</label>
                <select name="supplier_id" id="supplier_id" class="form-select">
                    <option value="">-- Select Supplier --</option>
                    <?php if ($suppliersResult && $suppliersResult->num_rows > 0): ?>
                        <?php while ($supplier = $suppliersResult->fetch_assoc()): ?>
                            <option value="<?= $supplier['id']; ?>" <?= ($product['supplier_id'] == $supplier['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($supplier['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option disabled>No suppliers found</option>
                    <?php endif; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i> Update Product</button>
            <a href="list.php" class="btn btn-secondary ms-2">Back to List</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
