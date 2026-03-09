<?php
include "db_conn.php";

if(isset($_GET['del'])){
    $conn->prepare("DELETE FROM menuproducts WHERE ID = ?")->execute([$_GET['del']]);
    header("Location: manage_menu_products.php");
    exit();
}

if(isset($_POST['save_link'])){
    $m_id = $_POST['menu_id'];
    $p_id = $_POST['product_id'];
    $link_id = $_POST['link_id'];

    if(!empty($link_id)){
        $conn->prepare("UPDATE menuproducts SET MenuID = ?, ProductID = ? WHERE ID = ?")->execute([$m_id, $p_id, $link_id]);
    } else {
        $conn->prepare("INSERT INTO menuproducts (MenuID, ProductID) VALUES (?, ?)")->execute([$m_id, $p_id]);
    }
    header("Location: manage_menu_products.php");
    exit();
}

$edit_link = isset($_GET['edit']) ? $conn->query("SELECT * FROM menuproducts WHERE ID=".$_GET['edit'])->fetch(PDO::FETCH_ASSOC) : null;

$all_menus = $conn->query("SELECT * FROM menus")->fetchAll();
$all_products = $conn->query("SELECT * FROM products")->fetchAll();
$links = $conn->query("SELECT mp.ID, m.Name as MName, p.Name as PName FROM menuproducts mp JOIN menus m ON mp.MenuID = m.ID JOIN products p ON mp.ProductID = p.ID ORDER BY mp.ID DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css">
    <title>Manage Links</title>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm">
    <div class="container"><a class="navbar-brand fw-bold" href="index.php">LINKING PANEL</a></div>
</nav>

<div class="container mt-5">
    <h3 class="menu-header"><?= $edit_link ? 'Update' : 'Create' ?> Link</h3>
    <form method="POST" class="row g-3 mb-4 shadow-sm p-3 bg-white rounded">
        <input type="hidden" name="link_id" value="<?= $edit_link['ID'] ?? '' ?>">
        <div class="col-md-5">
            <select name="menu_id" class="form-select" required>
                <option value="">Select Menu...</option>
                <?php foreach($all_menus as $m): ?>
                    <option value="<?= $m['ID'] ?>" <?= (isset($edit_link) && $edit_link['MenuID'] == $m['ID']) ? 'selected' : '' ?>><?= $m['Name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-5">
            <select name="product_id" class="form-select" required>
                <option value="">Select Product...</option>
                <?php foreach($all_products as $p): ?>
                    <option value="<?= $p['ID'] ?>" <?= (isset($edit_link) && $edit_link['ProductID'] == $p['ID']) ? 'selected' : '' ?>><?= $p['Name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2"><button name="save_link" class="btn <?= $edit_link ? 'btn-info' : 'btn-success' ?> w-100"><?= $edit_link ? 'Update' : 'Link' ?></button></div>
    </form>

    <table class="table table-bordered bg-white shadow-sm">
        <thead class="table-dark"><tr><th>Menu</th><th>Product</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach($links as $l): ?>
            <tr>
                <td class="text-success fw-bold"><?= htmlspecialchars($l['MName']) ?></td>
                <td><?= htmlspecialchars($l['PName']) ?></td>
                <td>
                    <a href="?edit=<?= $l['ID'] ?>" class="btn btn-info btn-sm text-white">Edit</a>
                    <a href="?del=<?= $l['ID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remove?')">Remove</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>