<?php
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

// Fetch only 3 products for homepage
$query = "SELECT * FROM products ORDER BY id LIMIT 3";
$stmt = $db->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero" id="home" style="min-height: 100vh; display: flex; align-items: center; background-image: url('image/banner.jpg'); background-repeat: no-repeat; background-size: cover; background-position: center; position: relative; margin-top: -80px;">
    <div class="hero-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(0deg, rgb(245, 245, 220) 0%, rgba(245, 245, 220, 0.3) 100%);"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="row">
            <div class="col-lg-7">
                <h1 style="font-size: 3.5rem; color: #ffb6c1; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">Nikmati Kue dan Roti yang Manis Semanis Dirimu</h1>
                <p style="font-size: 1.2rem; margin-top: 1rem; color: #008000; font-weight: 500;">
                    Roti lembut, bahan berkualitas, dan dipanggang hari ini juga. Pesan sekarang dan rasakan kelezatan yang masih hangat dari panggangan kami.
                </p>
                <a href="menu.php" class="btn btn-order" style="margin-top: 1rem; display: inline-block; padding: 1rem 3rem; font-size: 1.2rem; font-weight: 600; border-radius: 30px; color: #ffb6c1; background-color: #ff1493; text-decoration: none; transition: all 0.3s;">Lihat Semua Menu</a>
            </div>
        </div>
    </div>
</section>

<!-- Menu Section - Only 3 products -->
<section id="menu" class="py-5" style="background-color: beige;">
    <div class="container">
        <p class="title text-center" style="font-size: 1.2rem; font-weight: 700; color: #ffb6c1;">Our Menu</p>
        <h2 class="text-center mb-5" style="color: #008000; font-size: 2.5rem; font-weight: 700;">SweetTooth Menu</h2>
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
        <div class="text-center mt-4">
            <a href="menu.php" class="btn" style="background: #ff1493; color: white; padding: 12px 30px; border-radius: 30px;">
                Lihat Semua Menu <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="about py-5" style="background-color: rgb(247, 247, 205);">
    <div class="container">
        <p class="title text-center" style="font-size: 1.2rem; font-weight: 700; color: #ffb6c1;">About Us</p>
        <h1 class="text-center mb-5" style="color: #ff1493; font-size: 2.5rem; font-weight: 700;">Tentang SweetTooth</h1>
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-text">
                    <h2 style="color: #008000;">Crafting Your Daily Bread</h2>
                    <br>
                    <p style="color: #008000;">SweetTooth adalah rumah bagi pecinta roti dan pastry sejati. Kami mengombinasikan bahan-bahan lokal pilihan dengan standar kualitas internasional untuk menciptakan produk yang jujur dan lezat.</p>
                    <p class="fw-bold" style="color: #ff1493;">Mengapa Kami?</p>
                    <p style="color: #008000;">✓ Handcrafted: Semua produk kami dibuat secara manual oleh tim baker berpengalaman.</p>
                    <p style="color: #008000;">✓ Premium Ingredients: Mulai dari cokelat hitam terbaik hingga gandum utuh organik.</p>
                    <p style="color: #008000;">✓ Sustainability: Kami berkomitmen meminimalkan limbah plastik dalam setiap kemasan kami.</p>
                    <br>
                    <a href="#" class="more" style="background: #ff1493; padding: 10px 20px; border-radius: 25px; color: #ffb6c1; text-decoration: none;">Read More</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="img-box position-relative mt-4 mt-lg-0">
                    <img src="image/store.png" alt="Bakery Store" class="img-fluid rounded" style="width: 100%;">
                    <div class="btn-map position-absolute top-50 start-50 translate-middle">
                        <a href="https://maps.app.goo.gl/qDPe2Q1MGKta5d4q8" target="_blank" style="background: rgba(0,0,0,0.7); display: block; padding: 15px; border-radius: 50%; color: white; transition: 0.3s;">
                            <i class="fa-solid fa-location-dot fa-2x"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="contact py-5" style="background-color: beige;">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <h2 class="h5" style="color: #008000;">Alamat</h2>
                <p style="color: #fd7b8e;">Jl Pahlawan Sunaryo, Dsn Tudan No. 13</p>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <h2 class="h5" style="color: #008000;">Kontak</h2>
                <p style="color: #fd7b8e;"><i class="fa-brands fa-whatsapp"></i> +6285136070144</p>
                <p style="color: #fd7b8e;"><i class="fa-regular fa-envelope"></i> wahyuyuningsih13@gmail.com</p>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <h2 class="h5" style="color: #008000;">Jam Operasional</h2>
                <p style="color: #fd7b8e;">Senin - Sabtu : 09.00 - 19.00</p>
                <p style="color: #fd7b8e;">Minggu : Tutup</p>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <h2 class="h5" style="color: #008000;">Ikuti Akun Medsos Kami</h2>
                <p>
                    <a href="#" class="social me-3" style="color: #ff1493;"><i class="fa-brands fa-youtube fa-lg"></i></a>
                    <a href="#" class="social me-3" style="color: #ff1493;"><i class="fa-brands fa-tiktok fa-lg"></i></a>
                    <a href="#" class="social me-3" style="color: #ff1493;"><i class="fa-brands fa-facebook fa-lg"></i></a>
                    <a href="#" class="social" style="color: #ff1493;"><i class="fa-brands fa-instagram fa-lg"></i></a>
                </p>
            </div>
        </div>
    </div>
</section>

<footer class="credit py-3" style="background-color: rgb(245, 245, 220); text-align: center;">
    <div class="container">
        Created By <b>Solikha Wahyu Ningsih</b> | &copy; 2026.
    </div>
</footer>

<script src="js/script.js"></script>
<script>
// Add to cart functionality
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
</script>

</body>
</html>