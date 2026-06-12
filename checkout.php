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

// Get user data
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get cart items
$query = "SELECT c.id as cart_id, c.quantity, p.* FROM cart c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(empty($cart_items)) {
    header("Location: cart.php");
    exit();
}

$total = 0;
foreach($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Initialize form data with user data (gunakan username sebagai nama)
$form_address = isset($_POST['address']) ? $_POST['address'] : ($user['address'] ?? '');
$form_phone = isset($_POST['phone']) ? $_POST['phone'] : ($user['phone'] ?? '');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    
    $db->beginTransaction();
    
    try {
        // Update user data if phone/address columns exist
        // Cek apakah kolom phone dan address ada di tabel users
        $columns = $db->query("SHOW COLUMNS FROM users")->fetchAll(PDO::FETCH_COLUMN);
        
        if(in_array('phone', $columns)) {
            $query = "UPDATE users SET phone = :phone WHERE id = :user_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
        }
        
        if(in_array('address', $columns)) {
            $query = "UPDATE users SET address = :address WHERE id = :user_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
        }
        
        // Create order (gunakan username sebagai nama pengirim)
        $fullname = $user['username'];
        $query = "INSERT INTO orders (user_id, total_amount, shipping_address, phone) 
                  VALUES (:user_id, :total, :address, :phone)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        $order_id = $db->lastInsertId();
        
        // Insert order items
        foreach($cart_items as $item) {
            $query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                      VALUES (:order_id, :product_id, :quantity, :price)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':product_id', $item['product_id']);
            $stmt->bindParam(':quantity', $item['quantity']);
            $stmt->bindParam(':price', $item['price']);
            $stmt->execute();
        }
        
        // Clear cart
        $query = "DELETE FROM cart WHERE user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        $db->commit();
        
        header("Location: orders.php?success=1");
        exit();
    } catch(Exception $e) {
        $db->rollBack();
        $error = "Checkout gagal: " . $e->getMessage();
    }
}
?>
<?php include 'includes/header.php'; ?>

<div class="container py-5 mt-5">
    <h2 class="mb-4" style="color: #ff1493;">📦 Checkout</h2>
    
    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Informasi Pengiriman</h5>
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <!-- Menampilkan data customer (tanpa fullname) -->
                    <div class="mb-4 p-3" style="background: #f8f9fa; border-radius: 10px;">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <small class="text-muted">Nama Customer</small>
                                <div class="fw-bold" style="color: #008000;">
                                    <i class="fas fa-user me-2"></i><?php echo htmlspecialchars($user['username']); ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <small class="text-muted">Email</small>
                                <div class="fw-bold" style="color: #008000;">
                                    <i class="fas fa-envelope me-2"></i><?php echo htmlspecialchars($user['email']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="address" class="form-control" rows="3" required placeholder="Masukkan alamat lengkap Anda"><?php echo htmlspecialchars($form_address); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($form_phone); ?>" required placeholder="Masukkan nomor telepon aktif">
                        </div>
                        <button type="submit" class="btn w-100" style="background: #ff1493; color: white;">
                            <i class="fas fa-check-circle"></i> Konfirmasi Pesanan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mt-4 mt-lg-0">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Ringkasan Pesanan</h5>
                    <?php foreach($cart_items as $item): ?>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span><?php echo htmlspecialchars($item['name']); ?> x<?php echo $item['quantity']; ?></span>
                        <span>Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></span>
                    </div>
                    <?php endforeach; ?>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Total</strong>
                        <strong class="text-danger h5">Rp <?php echo number_format($total, 0, ',', '.'); ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>