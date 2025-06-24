<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

// Total Products
$total_products = 0;
$result = $conn->query("SELECT COUNT(*) as count FROM products");
if ($result && $row = $result->fetch_assoc()) {
    $total_products = $row['count'];
}

// Total Suppliers
$total_suppliers = 0;
$result = $conn->query("SELECT COUNT(*) as count FROM suppliers");
if ($result && $row = $result->fetch_assoc()) {
    $total_suppliers = $row['count'];
}

// Low Stock Items (quantity < 5)
$low_stock = 0;
$result = $conn->query("SELECT COUNT(*) as count FROM products WHERE quantity < 5");
if ($result && $row = $result->fetch_assoc()) {
    $low_stock = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Dashboard - Inventory System</title>

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
    margin: 0;
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
  .navbar-text {
    color: #17c3b2;
  }
  .navbar-brand:hover,
  .nav-link:hover {
    color: #0fa191 !important;
  }

  .container-custom {
    max-width: 1100px;
    margin: 40px auto;
    padding: 0 1rem;
  }

 .dashboard-grid {
  display: flex;
  gap: 50px;
  
  flex-wrap: wrap;
  align-items: flex-start; /* ensures top alignment */
}

.dashboard-card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 6px 18px rgba(23, 195, 178, 0.15);
  padding: 20px;
  width: 300px;
  height: 200px;  /* fixed height for uniformity */
  text-align: center;
  transition: transform 0.3s ease;
  margin-left:10px;
}

  .dashboard-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(23, 195, 178, 0.3);
  }
  .dashboard-card i {
    font-size: 3.5rem;
    color: #17c3b2;
    margin-bottom: 15px;
    
  }
  .dashboard-card h5 {
    font-weight: 700;
    font-size: 1.2rem;
    color: #17c3b2;
    margin-bottom: 5px;
  }
  .dashboard-card h3 {
    font-weight: 800;
    font-size: 2.8rem;
    margin: 0;
    color: #121212;
  }

  .btn-nav {
    display: inline-flex;
    align-items: center;
    gap: 30px;
    justify-content: center;
    background: #17c3b2;
    color: #121212;
    font-weight: 700;
    font-size: 1rem;
    border: none;
    border-radius: 10px;
    padding: 14px 30px;
    box-shadow: 0 8px 24px rgba(23, 195, 178, 0.3);
    cursor: pointer;
    text-decoration: none;
    transition: background 0.3s ease, box-shadow 0.3s ease;
    margin-top: 100px;
    margin-left:40px;
  }
  .btn-nav:hover, .btn-nav:focus {
    background: #0fa191;
    color: white;
    box-shadow: 0 12px 36px rgba(15, 161, 145, 0.6);
    outline: none;
    text-decoration: none;
  }

  h2 {
    color: #17c3b2;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-align: center;
    margin-bottom: 1.5rem;
  }

  /* Responsive for small screens */
  @media (max-width: 767px) {
    .dashboard-grid {
      flex-direction: column;
      align-items: center;
    }
    .dashboard-card {
      width: 90%;
    }
    .btn-nav {
      width: 100%;
    }
  }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
  <div class="container-fluid px-4">
    <a class="navbar-brand" href="#">Inventory System</a>
    <div class="d-flex align-items-center">
      <span class="navbar-text me-3">Hello, <?= htmlspecialchars($_SESSION['user_name']); ?></span>
      <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </a>
    </div>
  </div>
</nav>

<div class="container-custom">
  <h2>Dashboard</h2>

  <div class="dashboard-grid">
    <div class="dashboard-card">
      <i class="fa-solid fa-box"></i>
      <h5>Total Products</h5>
      <h3><?= $total_products ?></h3>
    </div>
    <div class="dashboard-card">
      <i class="fa-solid fa-truck"></i>
      <h5>Total Suppliers</h5>
      <h3><?= $total_suppliers ?></h3>
    </div>
    <div class="dashboard-card">
      <i class="fa-solid fa-boxes-stacked"></i>
      <h5>Low Stock Items</h5>
      <h3><?= $low_stock ?></h3>
    </div>
  </div>

  <div class="text-center">
    <a href="../products/list.php" class="btn-nav"><i class="fa-solid fa-box"></i> Manage Products</a>
    <a href="../reports/inventory.php" class="btn-nav"><i class="fa-solid fa-chart-simple"></i> View Reports</a>
    <a href="../suppliers/list.php" class="btn-nav"><i class="fa-solid fa-truck"></i> Manage Suppliers</a>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
