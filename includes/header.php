<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Toko Kue - NyamNyam Bakery</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- My Style -->
    <link rel="stylesheet" href="style.css">
    <style>
        /* ========== NAVBAR UNIFORM ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: "Nunito", sans-serif;
            background-color: beige;
            padding-top: 80px;
        }
        
        /* Navbar Container */
        .navbar {
            background-color: rgb(245, 245, 219) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
        }
        
        .navbar .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 7%;
        }
        
        /* Brand */
        .nav-brand h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #ffb6c1;
            margin: 0;
        }
        
        /* Desktop Menu */
        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 2rem;
        }
        
        .nav-menu li a {
            font-weight: 600;
            color: #008000;
            font-size: 1.2rem;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-menu li a::after {
            content: "";
            display: block;
            border-bottom: 2px solid #ffb6c1;
            padding-bottom: 0.5rem;
            transform: scaleX(0);
            transition: 0.2s linear;
        }
        
        .nav-menu li a:hover::after {
            transform: scaleX(0.5);
        }
        
        .nav-menu li a:hover {
            color: #ff1493;
        }
        
        /* Nav Buttons */
        .nav-btn {
            display: flex;
            align-items: center;
            gap: 1.2rem;
        }
        
        .nav-btn a {
            color: #ff1493;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            text-decoration: none;
            position: relative;
        }
        
        .nav-btn a:hover {
            color: #4a9db9;
            transform: scale(1.1);
        }
        
        /* Cart Badge */
        .cart-count {
            position: absolute;
            top: -10px;
            right: -12px;
            background: #ff1493;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
            min-width: 18px;
            text-align: center;
        }
        
        /* ========== SIDEBAR (SAMA UNTUK MOBILE & DESKTOP) ========== */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 10000;
            display: none;
            transition: all 0.3s ease;
        }
        
        .sidebar-overlay.active {
            display: block;
        }
        
        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: -320px;
            width: 320px;
            height: 100vh;
            background: linear-gradient(180deg, #fff8e7 0%, #ffe4e1 100%);
            z-index: 10001;
            transition: left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 5px 0 25px rgba(0, 0, 0, 0.2);
            overflow-y: auto;
        }
        
        .mobile-sidebar.active {
            left: 0;
        }
        
        /* Sidebar Header */
        .sidebar-header {
            background: linear-gradient(135deg, #ffb6c1 0%, #ff1493 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }
        
        .sidebar-header h3 {
            margin: 0;
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .sidebar-header h3 i {
            margin-right: 10px;
        }
        
        .sidebar-header p {
            margin-top: 8px;
            font-size: 0.85rem;
            opacity: 0.9;
        }
        
        /* Sidebar User Info */
        .sidebar-user {
            padding: 20px;
            background: rgba(255, 182, 193, 0.2);
            border-bottom: 1px solid #ffb6c1;
        }
        
        .sidebar-user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .sidebar-user-avatar {
            width: 50px;
            height: 50px;
            background: #ff1493;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        
        .sidebar-user-details h4 {
            margin: 0;
            font-size: 1rem;
            color: #008000;
        }
        
        .sidebar-user-details p {
            margin: 0;
            font-size: 0.8rem;
            color: #ff1493;
        }
        
        /* Sidebar Menu */
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            border-bottom: 1px solid rgba(0, 128, 0, 0.1);
        }
        
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: #008000;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            gap: 15px;
        }
        
        .sidebar-menu li a i {
            width: 25px;
            font-size: 1.2rem;
            color: #ff1493;
        }
        
        .sidebar-menu li a:hover {
            background: #ffb6c1;
            color: white;
            padding-left: 35px;
        }
        
        .sidebar-menu li a:hover i {
            color: white;
        }
        
        /* Humberger Menu Button (hanya muncul di mobile, tapi sama stylingnya) */
        #humberger-menu {
            display: block;
            cursor: pointer;
        }
        
        /* Desktop: Sembunyikan tombol humberger, tapi sidebar tetap bisa diakses via tombol lain? 
           Atau tampilkan juga tombol khusus untuk buka sidebar di desktop */
        @media (min-width: 992px) {
            #humberger-menu {
                display: none; /* Sembunyikan di desktop, tapi kita buat tombol terpisah jika perlu */
            }
        }
        
        /* Toast Notification */
        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 10002;
            animation: slideIn 0.3s ease;
            background: white;
            border-radius: 10px;
            padding: 12px 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Responsive Navbar */
        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
            
            .navbar .container-fluid {
                padding: 0 5%;
            }
            
            .nav-brand h2 {
                font-size: 1.5rem;
            }
            
            .nav-menu {
                display: none !important;
            }
            
            .nav-btn {
                gap: 0.8rem;
            }
            
            .nav-btn a {
                font-size: 1rem;
            }
            
            .mobile-sidebar {
                width: 280px;
            }
        }
        
        /* Untuk desktop, nav-menu tetap tampil */
        @media (min-width: 769px) {
            .nav-menu {
                display: flex !important;
            }
            
            #humberger-menu {
                display: none;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Mobile Sidebar (SAMA TAMPILAN UNTUK MOBILE & DESKTOP - bisa diakses via tombol) -->
<div class="mobile-sidebar" id="mobileSidebar">
    <div class="sidebar-header">
        <h3><i class="fas fa-cake-candles"></i> SweetTooth</h3>
        <p>Menu Utama</p>
    </div>
    
    <?php if(isset($_SESSION['user_id'])): ?>
    <div class="sidebar-user">
        <div class="sidebar-user-info">
            <div class="sidebar-user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="sidebar-user-details">
                <h4><?php echo htmlspecialchars($_SESSION['username']); ?></h4>
                <p><?php echo $_SESSION['role'] == 'admin' ? 'Administrator' : 'Member'; ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <ul class="sidebar-menu">
        <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="menu.php"><i class="fas fa-utensils"></i> Menu</a></li>
        <li><a href="index.php#about"><i class="fas fa-info-circle"></i> Tentang Kami</a></li>
        <li><a href="index.php#contact"><i class="fas fa-envelope"></i> Kontak</a></li>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            
            <?php if($_SESSION['role'] == 'customer'): ?>
                <!-- Menu CUSTOMER (bisa belanja) -->
                <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Keranjang <span id="sidebarCartCount" class="badge bg-danger rounded-pill ms-2">0</span></a></li>
                <li><a href="orders.php"><i class="fas fa-box"></i> Pesanan Saya</a></li>
                
            <?php elseif($_SESSION['role'] == 'admin'): ?>
                <!-- Menu ADMIN (kelola toko) -->
                <li><a href="admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard Admin</a></li>
            <?php endif; ?>
            
            <!-- Menu yang sama untuk customer dan admin -->
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            
        <?php else: ?>
            <!-- Menu untuk BELUM login -->
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
        <?php endif; ?>
    </ul>
</div>

<!-- Navbar -->
<nav class="navbar fixed-top">
    <div class="container-fluid">
        <div class="nav-brand">
            <h2>SweetTooth</h2>
        </div>
        
     <!-- Desktop Menu -->
    <ul class="nav-menu">
        <li><a href="index.php">Home</a></li>
        <li><a href="menu.php">Menu</a></li>
        <li><a href="index.php#about">About</a></li>
        <li><a href="index.php#contact">Kontak</a></li>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <?php if($_SESSION['role'] == 'customer'): ?>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php elseif($_SESSION['role'] == 'admin'): ?>
                <!-- Menu ADMIN (hanya admin dan logout) -->
                <li><a href="admin/dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php endif; ?>
            <li class="nav-user">
                <span style="color: #ff1493; font-weight: 600;">
                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                </span>
            </li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>

        <?php endif; ?>
    </ul>
        
        <!-- Nav Buttons -->
        <div class="nav-btn">
            <?php if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'): ?>
            <!-- Tampilkan cart hanya untuk NON-ADMIN (customer atau belum login) -->
            <a href="cart.php" class="btn-cart position-relative">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="cart-count" id="cartCount">0</span>
            </a>
            <?php endif; ?>
            <a href="#" id="humberger-menu">
                <i class="fa-solid fa-bars"></i>
            </a>
        </div>
    </div>
</nav>

<script>
// ========== SIDEBAR FUNCTIONALITY ==========
const humbergerBtn = document.getElementById('humberger-menu');
const mobileSidebar = document.getElementById('mobileSidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');

function openSidebar() {
    mobileSidebar.classList.add('active');
    sidebarOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeSidebar() {
    mobileSidebar.classList.remove('active');
    sidebarOverlay.classList.remove('active');
    document.body.style.overflow = '';
}

if (humbergerBtn) {
    humbergerBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        openSidebar();
    });
}

if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', closeSidebar);
}

// Close sidebar when clicking on a link (optional, biar langsung nutup)
document.querySelectorAll('.sidebar-menu a').forEach(link => {
    link.addEventListener('click', () => {
        setTimeout(closeSidebar, 200);
    });
});

// ========== UPDATE CART COUNT ==========
function updateCartCount() {
    fetch('get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            const count = data.count || 0;
            const cartCountElem = document.getElementById('cartCount');
            const sidebarCartCount = document.getElementById('sidebarCartCount');
            if(cartCountElem) {
                cartCountElem.textContent = count;
            }
            if(sidebarCartCount) {
                sidebarCartCount.textContent = count;
            }
        })
        .catch(error => console.error('Error:', error));
}

// ========== SHOW TOAST ==========
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast-notification alert alert-${type === 'success' ? 'success' : 'danger'} shadow`;
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Call on page load
document.addEventListener('DOMContentLoaded', updateCartCount);

// Prevent body scroll when sidebar is open
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && mobileSidebar.classList.contains('active')) {
        closeSidebar();
    }
});
</script>