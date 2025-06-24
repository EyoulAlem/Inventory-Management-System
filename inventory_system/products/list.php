<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

// Fetch all products with supplier name
$sql = "SELECT p.*, s.name AS supplier_name FROM products p LEFT JOIN suppliers s ON p.supplier_id = s.id ORDER BY p.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Products - Inventory System</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />

<style>
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
  .container-custom {
    max-width: 1100px;
    margin: 40px auto;
  }
  .btn-add {
    background: #17c3b2;
    border: none;
    color: #121212;
    font-weight: 700;
    border-radius: 10px;
    padding: 12px 22px;
    transition: background 0.3s ease;
    margin-bottom: 25px;
    display: inline-flex;
    align-items: center;
  }
  .btn-add i {
    margin-right: 10px;
  }
  .btn-add:hover {
    background: #0fa191;
    color: #fff;
  }

  /* Updated compact grid */
 .product-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr); /* 3 cards per row */
  gap: 30px;
}

.product-card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 6px 18px rgba(23, 195, 178, 0.15);
  padding: 16px;
  transition: transform 0.3s ease;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

  .product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(23, 195, 178, 0.3);
  }
  .product-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #17c3b2;
    margin-bottom: 5px;
  }
  .product-category,
  .product-details,
  .supplier-name {
    font-size: 0.85rem;
    color: #555;
    margin-bottom: 6px;
  }
  .product-actions {
    margin-top: auto;
  }
  .product-actions a {
    margin-right: 10px;
     margin-top: 10px;
    border-radius: 6px;
    padding: 5px 10px;
    font-weight: 600;
    font-size: 0.85rem;
    text-decoration: none;
    color: #fff;
    display: inline-flex;
    align-items: center;
    transition: background 0.3s ease;
  }
  .btn-info {
    background: #0fa191;
  }
  .btn-info:hover {
    background: #117a65;
  }
  .btn-primary {
    background: #17c3b2;
    color: #121212;
  }
  .btn-primary:hover {
    background: #0fa191;
    color: #fff;
  }
  .btn-danger {
    background: #e63946;
  }
  .btn-danger:hover {
    background: #b42a36;
  }
  .btn-warning {
    background: #ffb703;
    color: #121212;
  }
  .btn-warning:hover {
    background: #e6a500;
    color: #000;
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

<div class="container-custom">
  <h2 class="mb-4" style="color:#17c3b2;">Products</h2>
  <a href="add.php" class="btn btn-add">
    <i class="fa-solid fa-plus"></i> Add Product
  </a>

  <div class="product-grid">
  <?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="product-card">
        <div class="product-name"><?= htmlspecialchars($row['name']); ?></div>
        <div class="product-category">Category: <?= htmlspecialchars($row['category']); ?></div>
        <div class="product-details">Quantity: <?= (int)$row['quantity']; ?></div>
        <div class="product-details">Price: $<?= number_format($row['price'], 2); ?></div>
        <div class="supplier-name">Supplier: <?= htmlspecialchars($row['supplier_name'] ?? 'N/A'); ?></div>
        <div class="product-actions">
          <a href="view.php?id=<?= $row['id']; ?>" class="btn-info" title="View">
            <i class="fa-solid fa-eye"></i>&nbsp;View
          </a>
          <a href="edit.php?id=<?= $row['id']; ?>" class="btn-primary" title="Edit">
            <i class="fa-solid fa-pen-to-square"></i>&nbsp;Edit
          </a>
          <a href="#" class="btn-danger delete-btn" data-id="<?= $row['id']; ?>" title="Delete">
            <i class="fa-solid fa-trash"></i>&nbsp;Delete
          </a>
          <a href="issue.php?id=<?= $row['id']; ?>" class="btn-warning" title="Issue/Use">
            <i class="fa-solid fa-arrow-down"></i>&nbsp;Issue
          </a>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p class="text-center text-muted">No products found.</p>
  <?php endif; ?>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelectorAll('.delete-btn').forEach(button => {
  button.addEventListener('click', function(e) {
    e.preventDefault();
    const productId = this.getAttribute('data-id');
    Swal.fire({
      title: 'Are you sure?',
      text: "This action cannot be undone!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#17c3b2',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      customClass: { popup: 'shadow-lg rounded-3' }
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = 'delete.php?id=' + productId;
      }
    });
  });
});
</script>

</body>
</html>
