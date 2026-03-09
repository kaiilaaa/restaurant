<?php
include "db_conn.php";


if(isset($_GET["del_id"])){
    $conn->prepare("DELETE FROM menus WHERE ID = ?")->execute([$_GET["del_id"]]);
    header("Location: manage_menu.php?msg=Menu deleted");
}

if(isset($_POST["save_menu"])){
    $name = trim($_POST['menu_name']);
    $id = $_POST['menu_id'];
    $now = date('Y-m-d H:i:s');

    if(!empty($name)){
        if(!empty($id)){
            $conn->prepare("UPDATE menus SET Name=?, DateUpdated=? WHERE ID=?")->execute([$name, $now, $id]);
        } else {
            $conn->prepare("INSERT INTO menus (Name, DateCreated, DateUpdated) VALUES (?,?,?)")->execute([$name, $now, $now]);
        }
        header("Location: manage_menu.php");
    }
}

$edit = null;
if(isset($_GET['edit_id'])){
    $stmt = $conn->prepare("SELECT * FROM menus WHERE ID = ?");
    $stmt->execute([$_GET['edit_id']]);
    $edit = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Menus</title>
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
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white"><?= $edit ? 'Edit' : 'Add' ?> Category</div>
            <div class="card-body">
                <form method="post" class="row g-3">
                    <input type="hidden" name="menu_id" value="<?= $edit['ID'] ?? '' ?>">
                    <div class="col-md-9"><input type="text" name="menu_name" class="form-control" placeholder="e.g., Fries" value="<?= $edit['Name'] ?? '' ?>" required></div>
                    <div class="col-md-3"><button type="submit" name="save_menu" class="btn btn-success w-100">Save</button></div>
                </form>
            </div>
        </div>

        <table class="table table-striped bg-white shadow-sm rounded">
            <thead class="table-dark">
                <tr><th>ID</th><th>Menu Name</th><th>Created At</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php 
                $menus = $conn->query("SELECT * FROM menus ORDER BY ID DESC")->fetchAll();
                foreach ($menus as $m): ?>
                <tr>
                    <td><?= $m['ID'] ?></td>
                    <td><?= htmlspecialchars($m['Name']) ?></td>
                    <td><?= $m['DateCreated'] ?></td>
                    <td>
                        <a href="?edit_id=<?= $m['ID'] ?>" class="btn btn-sm btn-info text-white">Edit</a>
                        <a href="?del_id=<?= $m['ID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>