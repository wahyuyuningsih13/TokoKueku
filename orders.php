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

$query = "SELECT o.*, COUNT(oi.id) as item_count 
          FROM orders o 
          LEFT JOIN order_items oi ON o.id = oi.order_id 
          WHERE o.user_id = :user_id 
          GROUP BY o.id 
          ORDER BY o.order_date DESC";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'includes/header.php'; ?>

<div class="container py-5 mt-5">
    <h2 class="mb-4" style="color: #ff1493;">📋 Pesanan Saya</h2>
    
    <?php if(empty($orders)): ?>
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
            <h3>Belum Ada Pesanan</h3>
            <p>Yuk, mulai belanja di <a href="index.php#menu">Menu Kami</a></p>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach($orders as $order): ?>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Order #<?php echo $order['id']; ?></span>
                        <span class="badge <?php 
                            echo $order['status'] == 'pending' ? 'bg-warning' : 
                                ($order['status'] == 'paid' ? 'bg-info' : 
                                ($order['status'] == 'completed' ? 'bg-success' : 'bg-secondary')); 
                        ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <i class="fas fa-calendar"></i> 
                            <?php echo date('d M Y H:i', strtotime($order['order_date'])); ?>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-box"></i> <?php echo $order['item_count']; ?> Produk
                        </p>
                        <p class="mb-3">
                            <i class="fas fa-money-bill"></i> 
                            <strong>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></strong>
                        </p>
                        <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-sm" style="background: #ffb6c1; color: #008000;">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>