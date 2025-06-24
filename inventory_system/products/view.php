<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: list.php");
    exit;
}

// Fetch product with supplier info
$stmt = $conn->prepare("SELECT p.*, s.name AS supplier_name FROM products p LEFT JOIN suppliers s ON p.supplier_id = s.id WHERE p.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    header("Location: list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Product Details - <?= htmlspecialchars($product['name']) ?></title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f0f4f8;
      padding: 2rem;
    }
    .card {
      max-width: 650px;
      margin: 30px auto;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(23, 195, 178, 0.2);
      border: none;
    }
    .card-header {
      background-color: #17c3b2;
      color: #fff;
      border-radius: 16px 16px 0 0;
      font-weight: 600;
      font-size: 1.6rem;
      padding: 1rem 1.5rem;
    }
    .list-group-item {
      font-size: 1.05rem;
      padding: 1rem 1.5rem;
      border: none;
      border-bottom: 1px solid #e0e0e0;
    }
    .list-group-item:last-child {
      border-bottom: none;
    }
    .btn-back {
      margin-top: 25px;
      background-color: #17c3b2;
      color: #fff;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: 600;
      transition: background 0.3s ease;
    }
    .btn-back:hover {
      background-color: #0fa191;
      text-decoration: none;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="card">
      <div class="card-header">
        <i class="fa-solid fa-box"></i> Product Details
      </div>
      <ul class="list-group list-group-flush">
        <li class="list-group-item"><strong>Name:</strong> <?= htmlspecialchars($product['name']) ?></li>
        <li class="list-group-item"><strong>Category:</strong> <?= htmlspecialchars($product['category']) ?></li>
        <li class="list-group-item"><strong>Quantity:</strong> <?= (int)$product['quantity'] ?></li>
        <li class="list-group-item"><strong>Price (USD):</strong> $<?= number_format($product['price'], 2) ?></li>
        <li class="list-group-item"><strong>Supplier:</strong> <?= htmlspecialchars($product['supplier_name'] ?? 'N/A') ?></li>
        <li class="list-group-item"><strong>Created At:</strong> <?= htmlspecialchars($product['created_at']) ?></li>
      </ul>
    </div>

    <div class="text-center">
      <a href="list.php" class="btn btn-back">
        <i class="fa-solid fa-arrow-left"></i> Back to Products
      </a>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
