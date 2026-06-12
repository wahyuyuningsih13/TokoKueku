<?php
session_start();
require_once '../config/database.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$success_message = '';
if(isset($_GET['success'])) {
    if($_GET['success'] == 'added') {
        $success_message = 'Product added successfully!';
    } elseif($_GET['success'] == 'updated') {
        $success_message = 'Product updated successfully!';
    } elseif($_GET['success'] == 'deleted') {
        $success_message = 'Product deleted successfully!';
    }
}

$database = new Database();
$db = $database->getConnection();

// Handle delete product
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM products WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: products.php?success=deleted");
    exit();
}

// Get all products
$query = "SELECT * FROM products ORDER BY id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body {
            background: #f5f5dc;
            font-family: 'Nunito', sans-serif;
        }
        .sidebar {
            background: linear-gradient(135deg, #ffb6c1 0%, #ff1493 100%);
            min-height: 100vh;
        }
        .sidebar .nav-link {
            color: white;
            padding: 12px 20px;
            margin: 5px 0;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.2);
            transform: translateX(5px);
        }
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.3);
        }
        .btn-add {
            background: #ff1493;
            color: white;
            border: none;
        }
        .btn-add:hover {
            background: #ffb6c1;
            color: #008000;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="p-4 text-center text-white">
                    <i class="fas fa-cake-candles fa-3x"></i>
                    <h4 class="mt-2">SweetTooth</h4>
                    <small>Admin Panel</small>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a class="nav-link active" href="products.php">
                        <i class="fas fa-cake me-2"></i> Products
                    </a>
                    <a class="nav-link" href="orders.php">
                        <i class="fas fa-shopping-cart me-2"></i> Orders
                    </a>
                    <a class="nav-link" href="users.php">
                        <i class="fas fa-users me-2"></i> Users
                    </a>
                    <a class="nav-link" href="../logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 style="color: #ff1493;">Product Management</h2>
                    <a href="product_add.php" class="btn btn-add">
                        <i class="fas fa-plus"></i> Add Product
                    </a>
                </div>
                
                <div class="card shadow-sm">
                    <div class="card-body">
                                <?php if($success_message): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle me-2"></i> <?php echo $success_message; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($products as $product): ?>
                                    <tr>
                                        <td><?php echo $product['id']; ?></td>
                                        <td>
                                            <img src="../image/<?php echo htmlspecialchars($product['image']); ?>" 
                                                 width="50" height="50" style="object-fit: cover; border-radius: 8px;">
                                        </td>
                                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                                        <td>Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                                        <td><?php echo substr(htmlspecialchars($product['description']), 0, 50); ?>...</td>
                                        <td>
                                            <a href="product_edit.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?delete=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Delete this product?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>