<?php
include "db_conn.php";


if(isset($_POST['link_item'])){
    $m_id = $_POST['menu_id'];
    $p_id = $_POST['product_id'];
    $stmt = $conn->prepare("INSERT INTO menuproducts (MenuID, ProductID) VALUES (?, ?)");
    $stmt->execute([$m_id, $p_id]);
    header("Location: manage_menu_products.php");
}


$all_menus = $conn->query("SELECT * FROM menus")->fetchAll();
$all_products = $conn->query("SELECT * FROM products")->fetchAll();


$query = "SELECT mp.ID, m.Name as MenuName, p.Name as ProductName 
          FROM menuproducts mp
          JOIN menus m ON mp.MenuID = m.ID
          JOIN products p ON mp.ProductID = p.ID";
$links = $conn->query($query)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
<div class="container mt-5">
    <h3>Products to Menus</h3>
    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-5">
            <select name="menu_id" class="form-select" required>
                <option value="">Select Menu...</option>
                <?php foreach($all_menus as $m): ?>
                    <option value="<?= $m['ID'] ?>"><?= $m['Name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-5">
            <select name="product_id" class="form-select" required>
                <option value="">Select Product...</option>
                <?php foreach($all_products as $p): ?>
                    <option value="<?= $p['ID'] ?>"><?= $p['Name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button name="link_item" class="btn btn-success w-100">Link</button>
        </div>
    </form>

    <table class="table table-bordered bg-white">
        <thead class="table-dark">
            <tr><th>Menu</th><th>Product</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php foreach($links as $l): ?>
            <tr>
                <td><?= $l['MenuName'] ?></td>
                <td><?= $l['ProductName'] ?></td>
                <td><a href="?del=<?= $l['ID'] ?>" class="btn btn-danger btn-sm">Remove Link</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>