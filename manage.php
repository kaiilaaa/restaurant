<?php
include "db_conn.php";


if(isset($_GET["del"])){
    $conn->prepare("DELETE FROM products WHERE ID=?")->execute([$_GET["del"]]);
    header("Location: manage.php?msg=Deleted successfully");
}

if(isset($_POST["save"])){
    $name = $_POST['Name'];
    $price = $_POST['Price'];
    $path = $_POST['ImagePath'];
    $id = $_POST['id'];

    if(!empty($id)){
        $sql = "UPDATE products SET Name=?, Price=?, ImagePath=? WHERE ID=?";
        $conn->prepare($sql)->execute([$name, $price, $path, $id]);
    } else {
        $sql = "INSERT INTO products (Name, Price, ImagePath) VALUES (?,?,?)";
        $conn->prepare($sql)->execute([$name, $price, $path]);
    }
    header("Location: manage.php");
}

// FETCH DATA FOR EDIT
$edit = null;
if(isset($_GET['edit'])){
    $stmt = $conn->prepare("SELECT * FROM products WHERE ID=?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Management Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="index.php">ADMIN PANEL</a>
    <div class="navbar-nav ms-auto">
      <a class="nav-link" href="index.php">Menu</a>
      <a class="nav-link active" href="manage.php">Manage Products</a>
      <a class="nav-link" href="manage_menu.php">Manage Menus</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-primary text-white fw-bold"><?= $edit ? 'Edit' : 'Add' ?> Product</div>
        <div class="card-body">
            <form method="post" class="row g-3">
                <input type="hidden" name="id" value="<?= $edit['ID'] ?? '' ?>">
                <div class="col-md-4">
                    <input type="text" name="Name" class="form-control" placeholder="Name" value="<?= $edit['Name'] ?? '' ?>" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="Price" class="form-control" placeholder="Price" value="<?= $edit['Price'] ?? '' ?>" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="ImagePath" class="form-control" placeholder="Image Filename" value="<?= $edit['ImagePath'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" name="save" class="btn btn-success w-100">Save</button>
                </div>
            </form>
        </div>
    </div>

    <table class="table table-striped bg-white shadow-sm rounded overflow-hidden">
        <thead class="table-dark">
            <tr><th>ID</th><th>Name</th><th>Price</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php 
            $res = $conn->query("SELECT * FROM products ORDER BY ID DESC")->fetchAll();
            foreach ($res as $row): ?>
            <tr>
                <td><?= $row['ID'] ?></td>
                <td class="fw-bold"><?= htmlspecialchars($row['Name']) ?></td>
                <td>₱<?= number_format($row['Price'], 2) ?></td>
                <td>
                    <a href="?edit=<?= $row['ID'] ?>" class="btn btn-sm btn-info text-white">Edit</a>
                    <a href="?del=<?= $row['ID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>