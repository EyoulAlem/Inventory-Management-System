<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

// Threshold
$low_stock_threshold = 5;

// Summary
$total_products = 0;
$total_quantity = 0;
$low_stock_count = 0;

$sql_summary = "SELECT COUNT(*) AS product_count, SUM(quantity) AS total_qty FROM products";
$result_summary = $conn->query($sql_summary);
if ($result_summary) {
    $summary = $result_summary->fetch_assoc();
    $total_products = $summary['product_count'] ?? 0;
    $total_quantity = $summary['total_qty'] ?? 0;
}

// Product details
$sql_products = "SELECT p.*, s.name AS supplier_name FROM products p LEFT JOIN suppliers s ON p.supplier_id = s.id ORDER BY p.name ASC";
$result_products = $conn->query($sql_products);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Inventory Report - Inventory System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
  <style>
    h2 {
      color: #1abc9c;
      font-weight: 700;
      margin-top: 20px;
    }
    .summary-box {
      background: #ffffff;
      border-left: 6px solid #1abc9c;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      border-radius: 10px;
      padding: 1.5rem 2rem;
      margin-bottom: 2rem;
      color: #2c3e50;
    }
    .summary-box p {
      margin: 0.3rem 0;
      font-size: 1.15rem;
    }
    .low-stock {
      background-color: #fdecea !important;
      color: #c0392b !important;
      font-weight: 600;
    }
    .btn-export {
      background-color: #1abc9c;
      color: #fff;
      font-weight: 600;
      border-radius: 8px;
      padding: 10px 20px;
      margin-bottom: 1.2rem;
      margin-right: 12px;
      border: none;
      box-shadow: 0 2px 8px rgb(26 188 156 / 0.3);
      transition: background-color 0.3s ease;
    }
    .btn-export:hover {
      background-color: #16a085;
      color: #fff;
      box-shadow: 0 4px 14px rgb(22 160 133 / 0.5);
    }
    .btn-back {
      background-color: #34495e;
      color: #ecf0f1;
      border-radius: 8px;
      padding: 10px 18px;
      font-weight: 600;
      box-shadow: 0 2px 6px rgb(52 73 94 / 0.3);
      transition: background-color 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      margin-bottom: 1.5rem;
    }
    .btn-back:hover {
      background-color: #2c3e50;
      color: #fff;
      text-decoration: none;
      box-shadow: 0 4px 12px rgb(44 62 80 / 0.6);
    }
    .table {
      background: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgb(0 0 0 / 0.05);
    }
    .table thead {
      background-color: #1abc9c;
      color: white;
      font-weight: 600;
    }
    .table th, .table td {
      vertical-align: middle;
      font-size: 0.95rem;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background: #f0f4f8;
      min-height: 100vh;
    }
    nav.navbar {
      background-color: #121212;
      box-shadow: 0 4px 10px rgba(23, 195, 178, 0.3);
    }
    .navbar-brand, .nav-link {
      color: #17c3b2 !important;
      font-weight: 600;
    }
    .nav-link:hover {
      color: #0fa191 !important;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg">
  <div class="container-fluid px-4">
    <a class="navbar-brand" href="../dashboard/index.php">
      <i class="fa-solid fa-warehouse"></i> Inventory System
    </a>
    <div class="d-flex">
      <a href="../dashboard/index.php" class="nav-link">
        <i class="fa-solid fa-arrow-left"></i> Dashboard
      </a>
    </div>
  </div>
</nav>
<div class="container">
    <h2 class="mb-4"><i class="fa-solid fa-chart-column"></i> Inventory Report</h2>

    <div class="summary-box">
      <p><strong>Total Products:</strong> <?= $total_products ?></p>
      <p><strong>Total Quantity in Stock:</strong> <?= $total_quantity ?></p>
      <p><strong>Low Stock Items (&lt; <?= $low_stock_threshold ?>):</strong> 
        <?php
          if ($result_products && $result_products->num_rows > 0) {
              $result_products->data_seek(0);
              while ($row = $result_products->fetch_assoc()) {
                  if ($row['quantity'] < $low_stock_threshold) {
                      $low_stock_count++;
                  }
              }
              $result_products->data_seek(0); // Reset pointer for table loop
          }
          echo $low_stock_count;
        ?>
      </p>
    </div>

    <div class="mb-3 d-flex justify-content-start">
      <a href="export_csv.php" class="btn btn-export"><i class="fa-solid fa-file-csv"></i> Export CSV</a>
    </div>

    <div class="table-responsive shadow-sm rounded">
      <table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Supplier</th>
            <th>Total Amount (USD)</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result_products && $result_products->num_rows > 0): ?>
            <?php $i = 1; while ($row = $result_products->fetch_assoc()): ?>
              <?php $total_amount = $row['quantity'] * $row['price']; ?>
              <tr class="<?= ($row['quantity'] < $low_stock_threshold) ? 'low-stock' : '' ?>">
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td><?= (int)$row['quantity'] ?></td>
                <td><?= htmlspecialchars($row['supplier_name'] ?? 'N/A') ?></td>
                <td>$<?= number_format($total_amount, 2) ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center">No product data available.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
