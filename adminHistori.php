<?php
include("koneksi.php");

// Ambil parameter pencarian, sort, dan filter dari URL (GET)
$cari        = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$sortNama    = $_GET['sort_nama'] ?? '';
$sortTanggal = $_GET['sort_tanggal'] ?? '';
$filterHarga = $_GET['filter_harga'] ?? '';

// Kondisi pencarian (nama pembeli atau nama obat)
$whereClause = "";
if ($cari !== '') {
    $safeCari = mysqli_real_escape_string($connect, $cari);
    $whereClause = "WHERE p.purchaseID IN (
        SELECT p2.purchaseID
        FROM purchase p2
        JOIN accounts a2 ON p2.customerID = a2.ID
        JOIN purchasedetail pd2 ON p2.purchaseID = pd2.purchaseID
        JOIN meds m2 ON pd2.medID = m2.ID
        WHERE a2.username LIKE '%$safeCari%' OR m2.Name LIKE '%$safeCari%'
    )";
}

// Susun urutan (ORDER BY) berdasarkan dropdown yang dipilih
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

// Default urutan: transaksi terbaru di paling atas
$orderBySql = !empty($orderClauses) ? implode(", ", $orderClauses) : "p.purchaseTime DESC";

$sql = "
    SELECT 
        p.purchaseID,
        p.purchaseTime,
        p.totalPurchase,
        a.username AS customerName,
        SUM(pd.amount) AS totalQty
    FROM purchase p
    JOIN accounts a ON p.customerID = a.ID
    JOIN purchasedetail pd ON p.purchaseID = pd.purchaseID
    JOIN meds m ON pd.medID = m.ID
    $whereClause
    GROUP BY p.purchaseID, p.purchaseTime, p.totalPurchase, a.username
    ORDER BY $orderBySql
";

$query = mysqli_query($connect, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Histori Pembelian - HealthyCare Admin</title>
  <link rel="stylesheet" href="dashboard.css">
  <script src="theme.js"></script>
</head>
<body>

  <div class="app-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
      <a href="indexAdmin.php" class="sidebar-brand">
        <div class="brand-icon">
          <img src="assets/app-icon/appLogo.png" alt="Logo HealthyCare">
        </div>
        <span class="brand-name">HealthyCare</span>
      </a>

      <nav class="sidebar-nav">
        <div class="nav-label">Manajemen</div>
        <a href="indexAdmin.php" class="nav-link">Kelola Data Obat</a>
        <a href="adminHistori.php" class="nav-link active">Histori Pembelian</a>
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
        <div style="display: flex; align-items: center; gap: 10px;">
          <!-- Tombol Terang / Gelap -->
          <button type="button" class="theme-btn-top" id="btn-theme" title="Ganti Mode Terang / Gelap">
            <img src="assets/app-icon/sun.png" id="icon-theme" alt="Mode">
          </button>
          <a href="user.php" class="user-pill">
            <div class="user-avatar-sm">
              <img src="assets/user-icon/admin.png" alt="Admin">
            </div>
            <span><?= htmlspecialchars($_SESSION['username'] ?? 'Admin Apotek') ?></span>
          </a>
        </div>
      </header>

      <main class="page-body">
        <section id="histori-pembelian">
          <div class="section-header">
            <div>
              <h1 class="section-title">Histori Pembelian Pembeli</h1>
              <p class="section-subtitle">Semua riwayat transaksi dari seluruh pembeli.</p>
            </div>
            <!-- Tombol Export Excel -->
            <a href="export.php?type=histori" class="btn btn-outline">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
              </svg>
              Export Excel
            </a>
          </div>

          <div class="table-card">
            <!-- Toolbar Sort & Filter -->
            <form class="table-toolbar" method="GET" action="adminHistori.php">
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
                  <a href="adminHistori.php" class="btn btn-outline btn-sm">Reset</a>
                <?php endif; ?>
                <button type="submit" class="btn btn-emerald btn-sm">Terapkan</button>
              </div>
            </form>

            <div class="table-wrap">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Tanggal Pembelian</th>
                    <th>Nama Pembeli</th>
                    <th>Obat yang Dibeli</th>
                    <th>Jumlah</th>
                    <th>Subtotal Pembelian</th>
                    <th>Detail</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($query && $query->num_rows > 0): ?>
                    <?php while ($purchase = $query->fetch_assoc()): ?>
                      <?php
                        $pid = (int) $purchase['purchaseID'];
                        $itemOrder = ($sortNama === 'za') ? 'DESC' : 'ASC';
                        $itemsQuery = mysqli_query($connect, "
                            SELECT m.Name, m.Image, pd.amount
                            FROM purchasedetail pd
                            JOIN meds m ON pd.medID = m.ID
                            WHERE pd.purchaseID = $pid
                            ORDER BY m.Name $itemOrder
                        ");
                      ?>
                      <tr>
                        <td><?= date('d M Y, H:i', strtotime($purchase['purchaseTime'])) ?></td>
                        <td><strong><?= htmlspecialchars($purchase['customerName']) ?></strong></td>
                        <td>
                          <div style="display: flex; flex-direction: column; gap: 8px;">
                            <?php while ($item = $itemsQuery->fetch_assoc()): ?>
                              <?php
                                $imgPath = (!empty($item['Image']) && file_exists("assets/product-image/" . $item['Image']))
                                  ? "assets/product-image/" . htmlspecialchars($item['Image'])
                                  : "assets/app-icon/appLogo.png";
                              ?>
                              <div class="td-product">
                                <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($item['Name']) ?>" class="td-thumb">
                                <span>
                                  <?= htmlspecialchars($item['Name']) ?>
                                  <small style="color: #94a3b8; font-weight: 400;">(x<?= (int) $item['amount'] ?>)</small>
                                </span>
                              </div>
                            <?php endwhile; ?>
                          </div>
                        </td>
                        <td><?= (int) $purchase['totalQty'] ?> pcs</td>
                        <td><strong>Rp <?= number_format($purchase['totalPurchase'], 0, ',', '.') ?></strong></td>
                        <td>
                          <a href="invoice.php?id=<?= $pid ?>" class="btn btn-outline btn-sm">Lihat Invoice</a>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="6" style="text-align: center; padding: 28px; color: #94a3b8;">
                        Belum ada histori transaksi pembelian.
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