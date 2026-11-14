<?php
include("koneksi.php");

// Fitur pencarian obat (opsional, otomatis jalan saat ketik di search bar)
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';
if ($cari !== '') {
    $safe = mysqli_real_escape_string($connect, $cari);
    $query = mysqli_query($connect, "SELECT * FROM meds WHERE Name LIKE '%$safe%' OR Category LIKE '%$safe%'");
} else {
    $query = mysqli_query($connect, "SELECT * FROM meds");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Data Obat - HealthyCare Admin</title>
  <link rel="stylesheet" href="dashboard.css">
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
        <a href="indexAdmin.php" class="nav-link active">Kelola Data Obat</a>
        <a href="adminHistori.php" class="nav-link">Histori Pembelian</a>

        <div class="nav-label">Akun</div>
        <a href="user.php" class="nav-link">Profil Admin</a>
        <a href="logout.php" class="nav-link logout">Logout</a>
      </nav>
    </aside>

    <!-- Konten Utama -->
    <div class="main-content">
      <header class="topbar">
        <form class="search-box" method="GET" action="indexAdmin.php">
          <input type="text" name="cari" class="search-input" placeholder="Cari nama obat atau kategori..." value="<?= htmlspecialchars($cari) ?>">
        </form>
        <a href="user.php" class="user-pill">
          <div class="user-avatar-sm">A</div>
          <span>Admin Apotek</span>
        </a>
      </header>

      <main class="page-body">
        <section id="kelola-obat">
          <div class="section-header">
            <div>
              <h1 class="section-title">Kelola Data Obat</h1>
              <p class="section-subtitle">Tambah, perbarui, dan kelola stok obat HealthyCare.</p>
            </div>
            <!-- Tetap terhubung ke tambah.php milikmu -->
            <a href="tambah.php" class="btn btn-emerald">+ Tambah Obat</a>
          </div>

          <div class="table-card">
            <div class="table-wrap">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nama Obat</th>
                    <th>Kategori</th>
                    <th>Tipe</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($query && $query->num_rows > 0): ?>
                    <?php while ($data = $query->fetch_assoc()): ?>
                      <?php
                        $stockClass = ($data["Stock"] <= 5) ? "badge-warn" : "badge-emerald";
                        $imgPath = (!empty($data["Image"]) && file_exists("assets/product-image/" . $data["Image"]))
                          ? "assets/product-image/" . htmlspecialchars($data["Image"])
                          : "assets/app-icon/appLogo.png";
                      ?>
                      <tr>
                        <td>#<?= htmlspecialchars($data["ID"]) ?></td>
                        <td>
                          <div class="td-product">
                            <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($data["Name"]) ?>" class="td-thumb">
                            <span><?= htmlspecialchars($data["Name"]) ?></span>
                          </div>
                        </td>
                        <td><span class="badge badge-slate"><?= htmlspecialchars($data["Category"]) ?></span></td>
                        <td><?= htmlspecialchars($data["Type"]) ?></td>
                        <td>Rp <?= number_format($data["Price"], 0, ',', '.') ?></td>
                        <td><span class="badge <?= $stockClass ?>"><?= htmlspecialchars($data["Stock"]) ?></span></td>
                        <td>
                          <div class="td-actions">
                            <a href="edit.php?id=<?= urlencode($data["ID"]) ?>" class="btn btn-outline btn-sm">Edit</a>
                            <a href="hapus.php?id=<?= urlencode($data["ID"]) ?>" class="btn btn-danger btn-sm" data-nama="<?= htmlspecialchars($data["Name"]) ?>" onclick="return confirm('Yakin ingin menghapus ' + this.dataset.nama + '?')">Hapus</a>
                          </div>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="7" style="text-align: center; padding: 28px; color: #94a3b8;">
                        Data obat tidak ditemukan.
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

</body>
</html>