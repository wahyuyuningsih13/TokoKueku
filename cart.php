<?php
session_start();
require_once 'config/database.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$user_id = $_SESSION['user_id'];

// Handle remove item
if(isset($_GET['remove'])) {
    $cart_id = $_GET['remove'];
    $query = "DELETE FROM cart WHERE id = :id AND user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $cart_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    header("Location: cart.php");
    exit();
}

// Handle update quantity
if(isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];
    if($quantity > 0) {
        $query = "UPDATE cart SET quantity = :quantity WHERE id = :id AND user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':id', $cart_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    }
    header("Location: cart.php");
    exit();
}

// Get cart items
$query = "SELECT c.id as cart_id, c.quantity, p.* FROM cart c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<?php include 'includes/header.php'; ?>

<div class="container py-5 mt-5">
    <h2 class="mb-4" style="color: #ff1493;">🛒 Keranjang Belanja</h2>
    
    <?php if(empty($cart_items)): ?>
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
            <h3>Keranjang Kosong</h3>
            <p>Yuk, belanja dulu di <a href="index.php#menu">Menu Kami</a></p>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <?php foreach($cart_items as $item): ?>
                        <div class="row align-items-center mb-3 pb-3 border-bottom">
                            <div class="col-3 col-md-2">
                                <img src="image/<?php echo htmlspecialchars($item['image']); ?>" 
                                     class="img-fluid rounded" alt="<?php echo $item['name']; ?>">
                            </div>
                            <div class="col-5 col-md-4">
                                <h6 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                                <small class="text-muted">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></small>
                            </div>
                            <div class="col-4 col-md-3">
                                <form method="POST" class="d-flex align-items-center">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                           class="form-control form-control-sm w-50 me-2" min="1" style="width: 70px;">
                                    <button type="submit" name="update_cart" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="col-6 col-md-2">
                                <strong>Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></strong>
                            </div>
                            <div class="col-2 col-md-1 text-end">
                                <a href="?remove=<?php echo $item['cart_id']; ?>" class="text-danger" 
                                   onclick="return confirm('Hapus produk ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Ringkasan Belanja</h5>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Item</span>
                            <strong><?php echo count($cart_items); ?> Produk</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total Harga</span>
                            <strong class="text-danger h5">Rp <?php echo number_format($total, 0, ',', '.'); ?></strong>
                        </div>
                        <a href="checkout.php" class="btn w-100" style="background: #ff1493; color: white;">
                            <i class="fas fa-credit-card"></i> Checkout Sekarang
                        </a>
                        <a href="menu.php" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-shopping-bag"></i> Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>