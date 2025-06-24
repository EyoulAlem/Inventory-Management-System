<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

// Fetch suppliers
$sql = "SELECT * FROM suppliers ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Suppliers - Inventory System</title>

  <!-- Bootstrap CSS & FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
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
    h2 {
      color: #1abc9c;
      font-weight: 700;
      margin-bottom: 1.5rem;
      margin-top:30px;
      margin-left:-100px;
    }
    .btn-add {
      background-color: #1abc9c;
      color: #fff;
      font-weight: 600;
      border-radius: 8px;
      padding: 10px 20px;
      box-shadow: 0 2px 8px rgb(26 188 156 / 0.3);
      transition: background-color 0.3s ease;
      margin-bottom: 1.5rem;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
    }
    .btn-add:hover {
      background-color: #16a085;
      color: #fff;
      text-decoration: none;
      box-shadow: 0 4px 14px rgb(22 160 133 / 0.5);
    }
    table {
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgb(0 0 0 / 0.05);
    }
    thead {
      background-color: #1abc9c;
      color: #fff;
      font-weight: 600;
    }
    th, td {
      vertical-align: middle !important;
      font-size: 0.95rem;
    }
    .btn-primary, .btn-danger {
      border-radius: 6px;
      padding: 6px 12px;
      font-size: 0.9rem;
      transition: background-color 0.3s ease;
    }
    .btn-primary {
      background-color: #16a085;
      border: none;
      color: #fff;
      box-shadow: 0 2px 6px rgb(22 160 133 / 0.3);
    }
    .btn-primary:hover {
      background-color: #13876a;
      box-shadow: 0 4px 10px rgb(19 135 106 / 0.6);
      color: #fff;
    }
    .btn-danger {
      background-color: #e74c3c;
      border: none;
      color: #fff;
      box-shadow: 0 2px 6px rgb(231 76 60 / 0.3);
    }
    .btn-danger:hover {
      background-color: #c0392b;
      box-shadow: 0 4px 10px rgb(192 57 43 / 0.6);
      color: #fff;
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
    <h2>Suppliers</h2>

    <a href="add.php" class="btn-add">
      <i class="fa-solid fa-plus"></i> Add Supplier
    </a>

    <table class="table table-striped table-hover shadow-sm rounded">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Contact</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $i++; ?></td>
              <td><?= htmlspecialchars($row['name']); ?></td>
              <td><?= htmlspecialchars($row['contact']); ?></td>
              <td><?= htmlspecialchars($row['created_at']); ?></td>
              <td>
                <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-primary" title="Edit Supplier">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" title="Delete Supplier" onclick="return confirm('Are you sure you want to delete this supplier?');">
                  <i class="fa-solid fa-trash"></i>
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" class="text-center text-muted">No suppliers found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
