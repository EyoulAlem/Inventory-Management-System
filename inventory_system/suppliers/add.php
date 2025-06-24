<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);

    if (empty($name)) {
        $error = "Supplier name is required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO suppliers (name, contact) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $contact);
        if ($stmt->execute()) {
            $success = "Supplier added successfully.";
            $name = $contact = "";
        } else {
            $error = "Error adding supplier.";
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
  <title>Add Supplier - Inventory System</title>

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
    <h2><i class="fa-solid fa-truck"></i> Add Supplier</h2>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label for="name" class="form-label">Supplier Name *</label>
        <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($name ?? '') ?>" required />
      </div>

      <div class="mb-4">
        <label for="contact" class="form-label">Contact Info</label>
        <input type="text" name="contact" id="contact" class="form-control" value="<?= htmlspecialchars($contact ?? '') ?>" />
      </div>

      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary px-4">
          <i class="fa-solid fa-plus"></i> Add Supplier
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
