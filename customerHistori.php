<?php
session_start();
include("koneksi.php");

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$userName = $_SESSION['username'];

// Ambil ID customer dari session (jika belum tersimpan, cari berdasarkan username)
$customerID = $_SESSION['id'] ?? null;
if (!$customerID) {
    $safeUser = mysqli_real_escape_string($connect, $userName);
    $qUser = mysqli_query($connect, "SELECT ID FROM accounts WHERE username = '$safeUser' LIMIT 1");
    if ($rUser = mysqli_fetch_assoc($qUser)) {
        $customerID = $rUser['ID'];
        $_SESSION['id'] = $customerID;
    }
}

// Hitung item keranjang untuk badge di topbar & sidebar
$cartCount = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cartCount += (int)$qty;
    }
}

// Ambil parameter Sort, Filter, dan Pencarian
$cari        = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$sortNama    = $_GET['sort_nama'] ?? '';
$sortTanggal = $_GET['sort_tanggal'] ?? '';
$filterHarga = $_GET['filter_harga'] ?? '';

// Kondisi WHERE: Hanya transaksi milik Customer yang sedang login
$whereConditions = ["p.customerID = " . (int)$customerID];

if ($cari !== '') {
    $safeCari = mysqli_real_escape_string($connect, $cari);
    $whereConditions[] = "p.purchaseID IN (
        SELECT pd2.purchaseID 
        FROM purchasedetail pd2 
        JOIN meds m2 ON pd2.medID = m2.ID 
        WHERE m2.Name LIKE '%$safeCari%'
    )";
}
$whereSql = "WHERE " . implode(" AND ", $whereConditions);

// Susun ORDER BY berdasarkan pilihan sort & filter
$orderClauses = [];
if ($sortNama === 'az') {
    $orderClauses[] = "MIN(m.Name) ASC";
} elseif ($sortNama === 'za') {
    $orderClauses[] = "MAX(m.Name) DESC";
}

if ($filterHarga === 'murah') {
    $orderClauses[] = "p.totalPurchase ASC";
} elseif ($filterHarga === 'mahal') {
    $orderClauses[] = "p.totalPurchase DESC";
}

if ($sortTanggal === 'terlama') {
    $orderClauses[] = "p.purchaseTime ASC";
} elseif ($sortTanggal === 'terbaru') {
    $orderClauses[] = "p.purchaseTime DESC";
}

// Default urutan: transaksi terbaru paling atas
$orderBySql = !empty($orderClauses) ? implode(", ", $orderClauses) : "p.purchaseTime DESC";

$sql = "
    SELECT 
        p.purchaseID,
        p.purchaseTime,
        p.totalPurchase,
        SUM(pd.amount) AS totalQty
    FROM purchase p
    JOIN purchasedetail pd ON p.purchaseID = pd.purchaseID
    JOIN meds m ON pd.medID = m.ID
    $whereSql
    GROUP BY p.purchaseID, p.purchaseTime, p.totalPurchase
    ORDER BY $orderBySql
";

$query = mysqli_query($connect, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Histori Pembelian Saya - HealthyCare</title>
  <link rel="stylesheet" href="dashboard.css">
  <script src="theme.js"></script>
</head>
<body>

  <div class="app-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
      <a href="indexCustomer.php" class="sidebar-brand">
        <div class="brand-icon">
          <img src="assets/app-icon/appLogo.png" alt="Logo HealthyCare">
        </div>
        <span class="brand-name">HealthyCare</span>
      </a>

      <nav class="sidebar-nav">
        <div class="nav-label">Belanja</div>
        <a href="indexCustomer.php" class="nav-link">Katalog Obat</a>
        <a href="customerHistori.php" class="nav-link active">Histori Pembelian</a>
      </nav>
    </aside>

    <!-- Konten Utama -->
    <div class="main-content">
      <header class="topbar">
        <form class="search-box" method="GET" action="">
          <!-- Ikon Kaca Pembesar -->
          <span class="search-icon-left">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
          </span>

          <!-- Input Ketik -->
          <input type="text" name="cari" class="search-input" placeholder="Cari obat atau kategori..." value="<?= htmlspecialchars($cari ?? '') ?>" autocomplete="off">

          <!-- Tombol Hapus (X) -->
          <button type="button" class="search-clear-btn" title="Hapus pencarian">&times;</button>
        </form>

        <div style="display: flex; align-items: center; gap: 12px;">
          <!-- Tombol Ikon Keranjang dengan Badge -->
          <a href="cart.php" class="cart-btn-top" title="Keranjang Saya">
            <img src="assets/app-icon/cartIcon.png" alt="Cart">
            <span class="cart-badge-dot"><?= $cartCount ?></span>
          </a>

          <!-- Tombol Terang / Gelap -->
          <button type="button" class="theme-btn-top" id="btn-theme" title="Ganti Mode Terang / Gelap">
            <img src="assets/app-icon/sun.png" id="icon-theme" alt="Mode">
          </button>

          <!-- User Pill dengan Ikon Customer -->
          <a href="user.php" class="user-pill">
            <div class="user-avatar-sm">
              <img src="assets/user-icon/customer.png" alt="Customer">
            </div>
            <span><?= htmlspecialchars($userName) ?></span>
          </a>
        </div>
      </header>

      <main class="page-body">
        <section id="histori-saya">
          <div class="section-header">
            <div>
              <h1 class="section-title">Histori Pembelian Saya</h1>
              <p class="section-subtitle">Riwayat transaksi pembelian obat milik akun Anda.</p>
            </div>
          </div>

          <div class="table-card">
            <!-- Toolbar Sort & Filter -->
            <form class="table-toolbar" method="GET" action="customerHistori.php">
              <?php if ($cari !== ''): ?>
                <input type="hidden" name="cari" value="<?= htmlspecialchars($cari) ?>">
              <?php endif; ?>

              <div class="filter-group">
                <select name="sort_nama" class="filter-select">
                  <option value="">Sort Nama Obat</option>
                  <option value="az" <?= ($sortNama === 'az') ? 'selected' : '' ?>>Nama Obat (A - Z)</option>
                  <option value="za" <?= ($sortNama === 'za') ? 'selected' : '' ?>>Nama Obat (Z - A)</option>
                </select>

                <select name="sort_tanggal" class="filter-select">
                  <option value="">Sort Tanggal Transaksi</option>
                  <option value="terbaru" <?= ($sortTanggal === 'terbaru') ? 'selected' : '' ?>>Tanggal (Terbaru)</option>
                  <option value="terlama" <?= ($sortTanggal === 'terlama') ? 'selected' : '' ?>>Tanggal (Terlama)</option>
                </select>

                <select name="filter_harga" class="filter-select">
                  <option value="">Filter Harga</option>
                  <option value="murah" <?= ($filterHarga === 'murah') ? 'selected' : '' ?>>Harga Termurah</option>
                  <option value="mahal" <?= ($filterHarga === 'mahal') ? 'selected' : '' ?>>Harga Termahal</option>
                </select>
              </div>

              <div style="display: flex; gap: 8px;">
                <?php if ($cari !== '' || $sortNama !== '' || $sortTanggal !== '' || $filterHarga !== ''): ?>
                  <a href="customerHistori.php" class="btn btn-outline btn-sm">Reset</a>
                <?php endif; ?>
                <button type="submit" class="btn btn-emerald btn-sm">Terapkan</button>
              </div>
            </form>

            <div class="table-wrap">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Tanggal Pembelian</th>
                    <th>Obat yang Dibeli</th>
                    <th>Jumlah</th>
                    <th>Subtotal Pembelian</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($query && $query->num_rows > 0): ?>
                    <?php while ($purchase = $query->fetch_assoc()): ?>
                      <?php
                        $pid = (int)$purchase['purchaseID'];
                        $itemOrder = ($sortNama === 'za') ? 'DESC' : 'ASC';

                        // Ambil daftar obat pada transaksi ini
                        $itemsQuery = mysqli_query($connect, "
                            SELECT m.ID, m.Name, m.Image, pd.amount
                            FROM purchasedetail pd
                            JOIN meds m ON pd.medID = m.ID
                            WHERE pd.purchaseID = $pid
                            ORDER BY m.Name $itemOrder
                        ");

                        $firstMedId = null;
                      ?>
                      <tr>
                        <td><?= date('d M Y, H:i', strtotime($purchase['purchaseTime'])) ?></td>
                        <td>
                          <div style="display: flex; flex-direction: column; gap: 8px;">
                            <?php while ($item = $itemsQuery->fetch_assoc()): ?>
                              <?php
                                if (!$firstMedId) {
                                    $firstMedId = $item['ID'];
                                }
                                $imgPath = (!empty($item['Image']) && file_exists("assets/product-image/" . $item['Image']))
                                  ? "assets/product-image/" . htmlspecialchars($item['Image'])
                                  : "assets/app-icon/appLogo.png";
                              ?>
                              <div class="td-product">
                                <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($item['Name']) ?>" class="td-thumb">
                                <span>
                                  <?= htmlspecialchars($item['Name']) ?>
                                  <small style="color: #94a3b8; font-weight: 400;">(x<?= (int)$item['amount'] ?>)</small>
                                </span>
                              </div>
                            <?php endwhile; ?>
                          </div>
                        </td>
                        <td><?= (int)$purchase['totalQty'] ?> pcs</td>
                        <td><strong>Rp <?= number_format($purchase['totalPurchase'], 0, ',', '.') ?></strong></td>
                        <td>
                          <div class="td-actions">
                            <a href="invoice.php?id=<?= $pid ?>" class="btn btn-outline btn-sm">Invoice</a>
                            <a href="indexCustomer.php<?= $firstMedId ? '#detail-obat-' . $firstMedId : '' ?>" class="btn btn-emerald btn-sm">Beli Lagi</a>
                          </div>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" style="text-align: center; padding: 32px; color: #94a3b8;">
                        Anda belum memiliki riwayat pembelian obat.
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>

<script>
    // Logika Otomatis Tombol Hapus (X) di Search Bar
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
        // Jika halaman sedang menampilkan hasil pencarian, otomatis reset kembali ke semua data
        if (window.location.search.includes('cari=')) {
          box.submit();
        }
      });
    });
  </script>

</body>
</html>