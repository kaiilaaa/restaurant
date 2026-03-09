<?php
include "db_conn.php";

// DELETE ACTION
if(isset($_GET["delete_id"])){
    $id = $_GET["delete_id"];
    $sql = "DELETE FROM products WHERE ID = ?"; 
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    header("Location: manage.php?msg=Deleted successfully");
}

// INSERT / UPDATE ACTION (Combined)
if(isset($_POST["submit"])){
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
    header("Location: manage.php?msg=Operation successful");
}


$edit_data = null;
if(isset($_GET['edit_id'])){
    $stmt = $conn->prepare("SELECT * FROM products WHERE ID = ?");
    $stmt->execute([$_GET['edit_id']]);
    $edit_data = $stmt->fetch();
}

$products = $conn->query("SELECT * FROM products")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Admin</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Menu</a></li>
        <li class="nav-item"><a class="nav-link active" href="manage.php">Manage</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white"><?= $edit_data ? 'Edit' : 'Add' ?> Product</div>
        <div class="card-body">
            <form method="post" class="row g-3">
                <input type="hidden" name="id" value="<?= $edit_data['ID'] ?? '' ?>">
                <div class="col-md-4"><input type="text" name="Name" class="form-control" placeholder="Name" value="<?= $edit_data['Name'] ?? '' ?>" required></div>
                <div class="col-md-3"><input type="number" step="0.01" name="Price" class="form-control" placeholder="Price" value="<?= $edit_data['Price'] ?? '' ?>" required></div>
                <div class="col-md-3"><input type="text" name="ImagePath" class="form-control" placeholder="Image Filename" value="<?= $edit_data['ImagePath'] ?? '' ?>"></div>
                <div class="col-md-2"><button type="submit" name="submit" class="btn btn-success w-100">Save</button></div>
            </form>
        </div>
    </div>

    <table class="table table-striped table-hover shadow-sm bg-white">
        <thead class="table-dark">
            <tr><th>ID</th><th>Name</th><th>Price</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php foreach ($products as $row): ?>
            <tr>
                <td><?= $row['ID'] ?></td>
                <td><?= htmlspecialchars($row['Name']) ?></td>
                <td>₱<?= number_format($row['Price'], 2) ?></td>
                <td>
                    <a href="manage.php?edit_id=<?= $row['ID'] ?>" class="btn btn-sm btn-info">Edit</a>
                    <a href="manage.php?delete_id=<?= $row['ID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>