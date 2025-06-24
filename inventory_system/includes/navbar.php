<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<nav class="navbar navbar-expand-lg" style="background-color: #764ba2;">
  <div class="container-fluid px-5">
    <a class="navbar-brand text-white" href="../dashboard/index.php">Inventory System</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link text-white" href="../dashboard/index.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="../products/list.php">Products</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="../reports/inventory.php">Reports</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="../suppliers/list.php">Suppliers</a></li>
      </ul>
    </div>
    <div class="d-flex">
      <span class="navbar-text text-white me-3">
        Hello, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?>
      </span>
      <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </a>
    </div>
  </div>
</nav>
