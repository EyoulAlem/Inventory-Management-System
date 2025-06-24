<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

$error = "";
$success = "";

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
        $stmt = $conn->prepare("INSERT INTO products (name, category, quantity, price, supplier_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssidi", $name, $category, $quantity, $price, $supplier_id);

        if ($stmt->execute()) {
            $success = "Product added successfully.";
            $name = $category = "";
            $quantity = 0;
            $price = 0.00;
            $supplier_id = null;
        } else {
            $error = "Failed to add product.";
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
  <title>Add Product - Inventory System</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f0f4f8;
      min-height: 100vh;
      padding: 2rem;
    }

    .form-container {
      max-width: 600px;
      margin: auto;
      background: #fff;
      padding: 2rem 2.5rem;
      border-radius: 16px;
      box-shadow: 0 8px 30px rgba(23, 195, 178, 0.15);
    }

    h2 {
      color: #17c3b2;
      font-weight: 600;
      margin-bottom: 1.5rem;
      text-align: center;
    }

    .form-label {
      font-weight: 600;
      color: #121212;
    }

    .form-control:focus {
      border-color: #17c3b2;
      box-shadow: 0 0 0 0.2rem rgba(23, 195, 178, 0.25);
    }

    .btn-primary {
      background: #17c3b2;
      border: none;
      font-weight: 600;
      transition: background 0.3s ease;
    }

    .btn-primary:hover {
      background: #0fa191;
    }

    .btn-secondary {
      background: #121212;
      border: none;
      font-weight: 600;
      color: #fff;
    }

    .alert {
      border-radius: 8px;
    }
  </style>
</head>
<body>

  <div class="form-container">
    <h2><i class="fa-solid fa-box"></i> Add Product</h2>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label for="name" class="form-label">Product Name *</label>
        <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($name ?? '') ?>" required />
      </div>

      <div class="mb-3">
        <label for="category" class="form-label">Category *</label>
        <input type="text" name="category" id="category" class="form-control" value="<?= htmlspecialchars($category ?? '') ?>" required />
      </div>

      <div class="mb-3">
        <label for="quantity" class="form-label">Quantity *</label>
        <input type="number" name="quantity" id="quantity" class="form-control" min="0" value="<?= htmlspecialchars($quantity ?? 0) ?>" required />
      </div>

      <div class="mb-3">
        <label for="price" class="form-label">Price (USD) *</label>
        <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" value="<?= htmlspecialchars($price ?? 0.00) ?>" required />
      </div>

      <div class="mb-4">
        <label for="supplier_id" class="form-label">Supplier</label>
        <select name="supplier_id" id="supplier_id" class="form-select">
          <option value="">-- Select Supplier --</option>
          <?php if ($suppliersResult && $suppliersResult->num_rows > 0): ?>
            <?php while ($supplier = $suppliersResult->fetch_assoc()): ?>
              <option value="<?= $supplier['id']; ?>" <?= (isset($supplier_id) && $supplier_id == $supplier['id']) ? 'selected' : ''; ?>>
                <?= htmlspecialchars($supplier['name']); ?>
              </option>
            <?php endwhile; ?>
          <?php else: ?>
            <option disabled>No suppliers found</option>
          <?php endif; ?>
        </select>
      </div>

      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary px-4">
          <i class="fa-solid fa-plus"></i> Add Product
        </button>
        <a href="list.php" class="btn btn-secondary px-4">
          <i class="fa-solid fa-arrow-left"></i> Back
        </a>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
