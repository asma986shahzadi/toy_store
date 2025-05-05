<?php
session_start();
require '../db.php';

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../unauthorized.php');
    exit;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add a new toy
    if (isset($_POST['add_toy'])) {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $image = '';

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            if ($_FILES['image']['size'] <= 2 * 1024 * 1024) {
                $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
                $targetDir = '../uploads/';
                $targetFile = $targetDir . $imageName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $image = $imageName;
                }
            } else {
                echo "<script>alert('Image size exceeds 2MB!');</script>";
            }
        }

        $stmt = $pdo->prepare("INSERT INTO toys (name, category, price, stock, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $category, $price, $stock, $image]);
    }

    // Edit toy
    if (isset($_POST['edit_toy'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $image = $_POST['existing_image'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            if ($_FILES['image']['size'] <= 2 * 1024 * 1024) {
                $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
                $targetDir = '../uploads/';
                $targetFile = $targetDir . $imageName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $image = $imageName;
                }
            } else {
                echo "<script>alert('Image size exceeds 2MB!');</script>";
            }
        }

        $stmt = $pdo->prepare("UPDATE toys SET name = ?, category = ?, price = ?, stock = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $category, $price, $stock, $image, $id]);
    }

    // Delete toy
    if (isset($_POST['delete_toy'])) {
        $id = $_POST['id'];

        $stmt = $pdo->prepare("SELECT image FROM toys WHERE id = ?");
        $stmt->execute([$id]);
        $toy = $stmt->fetch();
        if ($toy && !empty($toy['image'])) {
            $imagePath = '../uploads/' . $toy['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $stmt = $pdo->prepare("DELETE FROM toys WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Fetch all toys
$stmt = $pdo->query("SELECT * FROM toys ORDER BY id DESC");
$toys = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Toys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f7f9fc;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #0044cc;
            color: white;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: rgba(255,255,255,0.1);
        }
        .main {
            padding: 30px;
        }
        .toy-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }
        #previewImage {
            margin-top: 10px;
            width: 100px;
            height: 100px;
            object-fit: cover;
            display: none;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 sidebar p-3">
            <h4 class="mb-4">Admin Panel</h4>
            <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="manage_toys.php"><i class="fas fa-cubes"></i> Manage Toys</a>
            <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
            <a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a>
            <a href="../common/login.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>

        <!-- Main -->
        <main class="col-md-10 main">
            <h2>Manage Toys</h2>
            <button class="btn btn-success my-3" id="addToyBtn">+ Add New Toy</button>

            <form id="toyForm" class="border p-4 rounded bg-white mb-4" method="post" enctype="multipart/form-data" style="display:none;">
                <input type="hidden" name="id" id="toyId">
                <input type="hidden" name="existing_image" id="existingImage">

                <div class="mb-3">
                    <label for="toyName" class="form-label">Toy Name</label>
                    <input type="text" class="form-control" name="name" id="toyName" required>
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" class="form-control" name="category" id="category" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price (Rs.)</label>
                    <input type="number" class="form-control" name="price" id="price" required>
                </div>

                <div class="mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" name="stock" id="stock" required>
                </div>

                <div class="mb-3">
                    <label for="imageUpload" class="form-label">Toy Image (Max 2MB)</label>
                    <input type="file" class="form-control" name="image" id="imageUpload" accept="image/*">
                    <img id="previewImage" alt="Preview">
                </div>

                <button type="submit" name="add_toy" class="btn btn-primary" id="saveToyBtn">Save Toy</button>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered bg-white">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Toy Name</th>
                            <th>Category</th>
                            <th>Price (Rs.)</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($toys as $toy): ?>
                            <tr>
                                <td><?= htmlspecialchars($toy['id']) ?></td>
                                <td>
                                    <?php if (!empty($toy['image'])): ?>
                                        <img src="../uploads/<?= htmlspecialchars($toy['image']) ?>" class="toy-img rounded">
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($toy['name']) ?></td>
                                <td><?= htmlspecialchars($toy['category']) ?></td>
                                <td><?= htmlspecialchars($toy['price']) ?></td>
                                <td><?= htmlspecialchars($toy['stock']) ?></td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm edit-btn"
                                        data-id="<?= $toy['id'] ?>"
                                        data-name="<?= htmlspecialchars($toy['name']) ?>"
                                        data-category="<?= htmlspecialchars($toy['category']) ?>"
                                        data-price="<?= htmlspecialchars($toy['price']) ?>"
                                        data-stock="<?= htmlspecialchars($toy['stock']) ?>"
                                        data-image="<?= htmlspecialchars($toy['image']) ?>">
                                        Edit
                                    </button>

                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $toy['id'] ?>">
                                        <button type="submit" name="delete_toy" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this toy?');">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<script>
const addToyBtn = document.getElementById('addToyBtn');
const toyForm = document.getElementById('toyForm');
const toyIdInput = document.getElementById('toyId');
const nameInput = document.getElementById('toyName');
const categoryInput = document.getElementById('category');
const priceInput = document.getElementById('price');
const stockInput = document.getElementById('stock');
const imageInput = document.getElementById('imageUpload');
const existingImageInput = document.getElementById('existingImage');
const previewImage = document.getElementById('previewImage');
const saveToyBtn = document.getElementById('saveToyBtn');

addToyBtn.addEventListener('click', () => {
    toyForm.style.display = 'block';
    toyForm.reset();
    previewImage.style.display = 'none';
    saveToyBtn.name = 'add_toy';
});

imageInput.addEventListener('change', () => {
    if (imageInput.files && imageInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImage.src = e.target.result;
            previewImage.style.display = 'block';
        };
        reader.readAsDataURL(imageInput.files[0]);
    }
});

document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', () => {
        toyForm.style.display = 'block';
        toyIdInput.value = button.dataset.id;
        nameInput.value = button.dataset.name;
        categoryInput.value = button.dataset.category;
        priceInput.value = button.dataset.price;
        stockInput.value = button.dataset.stock;
        existingImageInput.value = button.dataset.image;
        if (button.dataset.image) {
            previewImage.src = '../uploads/' + button.dataset.image;
            previewImage.style.display = 'block';
        } else {
            previewImage.style.display = 'none';
        }
        saveToyBtn.name = 'edit_toy';
    });
});
</script>
</body>
</html>