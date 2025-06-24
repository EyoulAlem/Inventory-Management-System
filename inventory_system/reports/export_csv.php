<?php
require_once "../includes/auth.php";
require_once "../config/db.php";

// Headers to force download as CSV file
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=inventory_report.csv');

// Open output stream
$output = fopen('php://output', 'w');

// Output column headings
fputcsv($output, ['Product Name', 'Category', 'Quantity', 'Supplier']);

// Fetch all products with supplier info
$sql = "SELECT p.name, p.category, p.quantity, s.name AS supplier_name FROM products p LEFT JOIN suppliers s ON p.supplier_id = s.id ORDER BY p.name ASC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['name'],
            $row['category'],
            $row['quantity'],
            $row['supplier_name'] ?? 'N/A'
        ]);
    }
}

fclose($output);
exit;
