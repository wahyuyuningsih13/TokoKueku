<?php
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

// Search functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$query = "SELECT * FROM products WHERE 1=1";

if(!empty($search)) {
    $query .= " AND (name LIKE :search OR description LIKE :search)";
}

$query .= " ORDER BY id";
$stmt = $db->prepare($query);

if(!empty($search)) {
    $searchParam = "%$search%";
    $stmt->bindParam(':search', $searchParam);
}

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>

<style>
    .search-section {
        background: linear-gradient(135deg, #ffb6c1 0%, #ff1493 100%);
        padding: 60px 0;
        margin-top: -20px;
    }
    
    .search-box {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .search-box .input-group {
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        border-radius: 50px;
        overflow: hidden;
    }
    
    .search-box input {
        border: none;
        padding: 15px 25px;
        font-size: 1rem;
    }
    
    .search-box button {
        background: #008000;
        border: none;
        padding: 15px 30px;
        color: white;
        transition: all 0.3s;
    }
    
    .search-box button:hover {
        background: #ff1493;
    }
    
    .product-count {
        color: #ff1493;
        font-weight: 600;
        margin-bottom: 20px;
    }
    
    .no-results {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .no-results i {
        font-size: 4rem;
        color: #ffb6c1;
        margin-bottom: 20px;
    }
    
    .no-results h3 {
        color: #008000;
        margin-bottom: 15px;
    }
    
    .clear-search {
        color: #ff1493;
        text-decoration: none;
        font-weight: 600;
    }
    
    .clear-search:hover {
        text-decoration: underline;
    }
</style>

<!-- Search Header Section -->
<section class="search-section">
    <div class="container">
        <div class="search-box">
            <form method="GET" action="menu.php">
                <div class="input-group">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari menu favoritmu..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn" type="submit">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Menu Section with Search Results -->
<section class="py-5" style="background-color: beige;">
    <div class="container">
        <p class="title text-center" style="font-size: 1.2rem; font-weight: 700; color: #ffb6c1;">Our Menu</p>
        <h2 class="text-center mb-4" style="color: #008000; font-size: 2.5rem; font-weight: 700;">
            SweetTooth Menu
        </h2>
        
        <?php if(!empty($search)): ?>
        <div class="product-count text-center mb-4">
            <i class="fas fa-search"></i> 
            Menampilkan <?php echo count($products); ?> hasil untuk "<strong><?php echo htmlspecialchars($search); ?></strong>"
            <a href="menu.php" class="clear-search ms-2">
                <i class="fas fa-times-circle"></i> Hapus filter
            </a>
        </div>
        <?php endif; ?>
        
        <?php if(empty($products)): ?>
            <div class="no-results">
                <i class="fas fa-search"></i>
                <i class="fas fa-cake"></i>
                <h3>Menu tidak ditemukan</h3>
                <p>Maaf, kami tidak menemukan produk dengan kata kunci "<strong><?php echo htmlspecialchars($search); ?></strong>"</p>
                <p>Coba gunakan kata kunci lain atau lihat menu lengkap kami.</p>
                <a href="menu.php" class="btn" style="background: #ff1493; color: white; margin-top: 15px;">
                    <i class="fas fa-eye"></i> Lihat Semua Menu
                </a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach($products as $product): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card product-card h-100" style="border-radius: 15px; overflow: hidden; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s;">
                        <img src="image/<?php echo htmlspecialchars($product['image']); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>"
                             style="height: 250px; object-fit: cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0" style="color: #008000;"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <span class="fw-bold" style="color: #ff1493;">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></span>
                            </div>
                            <p class="card-text small text-muted"><?php echo htmlspecialchars($product['description']); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <button class="btn-add-cart btn btn-sm" 
                                        data-id="<?php echo $product['id']; ?>"
                                        style="background: #ff1493; color: white; border-radius: 20px; padding: 8px 15px;">
                                    <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                                </button>
                                <div class="menu-stars" style="color: #ff1493;">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-regular fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script src="js/script.js"></script>
<script>
// Add to cart functionality for menu page
document.querySelectorAll('.btn-add-cart').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.id;
        
        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                showToast('Produk ditambahkan ke keranjang!', 'success');
                updateCartCount();
            } else if(data.redirect) {
                window.location.href = 'login.php';
            } else {
                showToast(data.message || 'Gagal menambahkan produk', 'error');
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

// Optional: Auto-search on typing with debounce
let searchTimeout;
const searchInput = document.querySelector('input[name="search"]');
if(searchInput) {
    searchInput.addEventListener('keyup', function(e) {
        if(e.key === 'Enter') return;
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if(this.value.length > 2 || this.value.length === 0) {
                this.form.submit();
            }
        }, 500);
    });
}
</script>

<?php include 'includes/footer.php'; ?>