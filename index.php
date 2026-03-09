<?php
include "db_conn.php";
$products = $conn->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Potato Corner Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css">
    
</head>
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">POTATO CORNER</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="index.php">Menu</a></li>
        <li class="nav-item"><a class="nav-link" href="manage.php">Manage</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="menu-header">Potato Corner</h1>
        <h2 class="menu-header h4">Menu List</h2>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($products as $p): ?>
        <div class="col">
            <div class="card h-100 product-card bg-transparent">
                <img src="images/<?= htmlspecialchars($p['ImagePath']) ?>" 
                     class="card-img-top px-4" 
                     style="height: 200px; object-fit: contain;">
                <div class="card-body">
                    <h5 class="product-name"><?= htmlspecialchars($p['Name']) ?></h5>
                    <p class="price-tag">PRICE: ₱<?= number_format($p['Price'], 0) ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>