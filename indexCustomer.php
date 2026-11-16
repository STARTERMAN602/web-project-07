<?php
session_start();
if(!isset($_SESSION["userID"])) {
  header("location: login.php?status=not_loged");
  exit;
}
include("koneksi.php");

$userName = $_SESSION['username'] ?? 'Customer';

$cartCount = 0;
if (!empty($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $qty) {
    $cartCount += $qty;
  }
}

if(isset($_GET["cari"])) {
  $cari = $_GET["cari"];
  $query = mysqli_query($connect, "select * from meds where (Name like '%$cari%' or Category like '%$cari%') and isDeleted = 0");
} else {
  $query = mysqli_query($connect, "select * from meds where isDeleted = 0");
}

$medsList = [];
if ($query && $query->num_rows > 0) {
    while ($data = $query->fetch_assoc()) {
        $medsList[] = $data;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Medicine Catalog - HealthyCare</title>
  <link rel="stylesheet" href="dashboard.css">
  <script src="theme.js"></script>
</head>
<body>

  <div class="app-layout">
    <aside class="sidebar">
      <a href="indexCustomer.php" class="sidebar-brand">
        <div class="brand-icon">
          <img src="assets/app-icon/appLogo.png" alt="Logo HealthyCare">
        </div>
        <span class="brand-name">HealthyCare</span>
      </a>

      <nav class="sidebar-nav">
        <div class="nav-label">Shop</div>
        <a href="indexCustomer.php" class="nav-link active">Medicine Catalog</a>
        <a href="customerHistori.php" class="nav-link">Purchase History</a>
      </nav>
    </aside>

    <div class="main-content">
      <header class="topbar">
        <form class="search-box" method="GET" action="">
          <span class="search-icon-left">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
          </span>

          <input type="text" name="cari" class="search-input" placeholder="Search name or category ...." value="<?= $cari ?? '' ?>" autocomplete="off">

          <button type="button" class="search-clear-btn" title="Hapus pencarian">&times;</button>
        </form>

        <div style="display: flex; align-items: center; gap: 12px;">
          <a href="cart.php" class="cart-btn-top" title="Keranjang Saya">
            <img src="assets/app-icon/cartIcon.png" alt="Cart">
            <span class="cart-badge-dot"><?= $cartCount ?></span>
          </a>

          <button type="button" class="theme-btn-top" id="btn-theme" title="Ganti Mode Terang / Gelap">
            <img src="assets/app-icon/sun.png" id="icon-theme" alt="Mode">
          </button>

          <a href="user.php" class="user-pill">
            <div class="user-avatar-sm">
              <img src="assets/user-icon/customer.png" alt="Customer">
            </div>
            <span><?= $_SESSION["username"] ?></span>
          </a>
        </div>
      </header>

      <main class="page-body">
        <section id="katalog-obat">
          <div class="section-header">
            <div>
              <h1 class="section-title">Medicine Catalog</h1>
              <p class="section-subtitle">Choose medicine and add it to chart.</p>
            </div>
          </div>

          <?php 
          if (!empty($medsList)){ 
          ?>
            <div class="product-grid">
              <?php 
              foreach ($medsList as $med){ 
                $imgPath = (!empty($med["Image"]) && file_exists("assets/product-image/" . $med["Image"])) ? "assets/product-image/" . $med["Image"] : "assets/app-icon/appLogo.png";
                $isHabis = ($med["Stock"] <= 0);
              ?>
                <div class="product-card">
                  <a href="#detail-obat-<?= $med['ID'] ?>" class="product-img-link" title="Klik untuk lihat detail obat">
                    <img src="<?= $imgPath ?>" alt="<?= $med['Name'] ?>">
                  </a>
                  <div class="product-body">
                    <div class="product-tags">
                      <span class="badge badge-slate"><?= $med['Category'] ?></span>
                      <span class="badge badge-emerald"><?= $med['Type'] ?></span>
                    </div>
                    <div class="product-name"><?= $med['Name'] ?></div>
                    <div class="product-bottom">
                      <span class="product-price">Rp <?= number_format($med['Price'], 0, ',', '.') ?></span>
                      <span class="product-stock" style="<?= $isHabis ? 'color: #f43f5e; font-weight: 600;' : '' ?>">
                        <?= $isHabis ? 'Out of stock' : 'Stock: ' . $med['Stock'] ?>
                      </span>
                    </div>
                  </div>
                </div>
              <?php 
              } 
              ?>
            </div>
          <?php 
          }else{ 
          ?>
            <div class="table-card" style="padding: 36px; text-align: center; color: #94a3b8;">
              Meicine not found.
            </div>
          <?php 
          } 
          ?>
        </section>
      </main>
    </div>
  </div>

  <?php 
  foreach ($medsList as $med){ 
    $imgPath = (!empty($med["Image"]) && file_exists("assets/product-image/" . $med["Image"])) ? "assets/product-image/" . $med["Image"] : "assets/app-icon/appLogo.png";
    $isHabis = ($med["Stock"] <= 0);
  ?>
    <div id="detail-obat-<?= $med['ID'] ?>" class="modal-overlay">
      <div class="modal-box">
        <div class="modal-header">
          <h3>Medicine Details</h3>
          <a href="#" class="modal-close">&times;</a>
        </div>

        <form action="tambahCart.php" method="GET">
          <input type="hidden" name="id" value="<?= $med['ID'] ?>">

          <div class="detail-grid">
            <div class="detail-img-box">
              <img src="<?= $imgPath ?>" alt="<?= $med['Name'] ?>">
            </div>

            <div class="detail-info">
              <h3><?= $med['Name'] ?></h3>
              <div class="detail-price">Rp <?= number_format($med['Price'], 0, ',', '.') ?></div>

              <div class="detail-specs">
                <div>Category: <strong><?= $med['Category'] ?></strong></div>
                <div>Tipe: <strong><?= $med['Type'] ?></strong></div>
                <div>
                  Stok: 
                  <strong style="color: <?= $isHabis ? '#f43f5e' : '#34d399' ?>;">
                    <?= $isHabis ? 'Out of stock' : $med['Stock'] ?>
                  </strong>
                </div>
              </div>
            </div>
          </div>

          <div class="qty-row">
            <span>Purchase Quantity</span>
            <div class="qty-control">
              <button type="button" class="qty-btn" onclick="ubahQty(this, -1)">-</button>
              <input type="number" name="qty" class="qty-input" value="1" min="1" max="<?= max(1, (int)$med['Stock']) ?>" <?= $isHabis ? 'disabled' : '' ?>>
              <button type="button" class="qty-btn" onclick="ubahQty(this, 1)">+</button>
            </div>
          </div>

          <?php 
          if (!$isHabis){ 
          ?>
            <button type="submit" class="btn btn-emerald" style="width: 100%; height: 42px;">
              + Add to Chart
            </button>
          <?php 
          }else{ 
          ?>
            <button type="button" class="btn btn-outline" style="width: 100%; height: 42px; opacity: 0.6; cursor: not-allowed;" disabled>
              Out of stock
            </button>
          <?php 
          } 
          ?>
        </form>
      </div>
    </div>
  <?php 
  }
  ?>

  <script>
    function ubahQty(btn, delta) {
      const parent = btn.closest('.qty-control');
      const input  = parent.querySelector('.qty-input');
      let val = parseInt(input.value) || 1;
      let min = parseInt(input.min) || 1;
      let max = parseInt(input.max) || 999;

      val += delta;
      if (val < min) val = min;
      if (val > max) val = max;
      input.value = val;
    }

    document.querySelectorAll('.search-box').forEach(box => {
      const input = box.querySelector('.search-input');
      const clearBtn = box.querySelector('.search-clear-btn');
      if (!input || !clearBtn) return;

      const checkValue = () => {
        clearBtn.style.display = input.value.trim() !== '' ? 'flex' : 'none';
      };

      checkValue();
      input.addEventListener('input', checkValue);

      clearBtn.addEventListener('click', () => {
        input.value = '';
        checkValue();
        input.focus();
        if (window.location.search.includes('cari=')) {
          box.submit();
        }
      });
    });
  </script>

</body>
</html>