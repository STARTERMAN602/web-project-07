<?php
session_start();
include "koneksi.php";
$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

$query = mysqli_query($connect, "SELECT * FROM meds WHERE ID = $id");
$data = $query ? $query->fetch_assoc() : null;

if (!$data) {
    header("Location: indexAdmin.php");
    exit;
}

$imgPath = (!empty($data["Image"]) && file_exists("assets/product-image/" . $data["Image"]))
    ? "assets/product-image/" . htmlspecialchars($data["Image"])
    : "assets/app-icon/appLogo.png";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Obat - HealthyCare Admin</title>
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
        <a href="indexAdmin.php" class="nav-link active">Kelola Data Obat</a>
        <a href="adminHistori.php" class="nav-link">Histori Pembelian</a>
      </nav>
    </aside>

    <!-- Konten Utama -->
    <div class="main-content">
      <header class="topbar">
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
        <section style="max-width: 620px; width: 100%;">
          <div class="section-header">
            <div>
              <h1 class="section-title">Edit Data Obat</h1>
              <p class="section-subtitle">Perbarui informasi, stok, atau gambar obat #<?= htmlspecialchars($data["ID"]) ?>.</p>
            </div>
          </div>

          <div class="table-card" style="padding: 24px;">
            <form action="editData.php" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?= htmlspecialchars($data["ID"]) ?>">
              <input type="hidden" name="old_image" value="<?= htmlspecialchars($data["Image"] ?? '') ?>">

              <div class="form-group">
                <label for="nama">Nama Obat</label>
                <input type="text" id="nama" name="nama" class="form-control" value="<?= htmlspecialchars($data["Name"]) ?>" required>
              </div>

              <div class="form-grid">
                <div class="form-group">
                  <label for="kategori">Kategori</label>
                  <select id="kategori" name="kategori" class="form-control" required>
                    <option value="Paracetamol" <?= ($data['Category'] == 'Paracetamol') ? 'selected' : '' ?>>Paracetamol</option>
                    <option value="Ibuprofen" <?= ($data['Category'] == 'Ibuprofen') ? 'selected' : '' ?>>Ibuprofen</option>
                    <option value="Vitamin" <?= ($data['Category'] == 'Vitamin') ? 'selected' : '' ?>>Vitamin</option>
                    <option value="Obat Maag" <?= ($data['Category'] == 'Obat Maag') ? 'selected' : '' ?>>Obat Maag</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="tipe">Tipe</label>
                  <select id="tipe" name="tipe" class="form-control" required>
                    <option value="Tablet" <?= ($data['Type'] == 'Tablet') ? 'selected' : '' ?>>Tablet</option>
                    <option value="Kapsul" <?= ($data['Type'] == 'Kapsul') ? 'selected' : '' ?>>Kapsul</option>
                    <option value="Sirup" <?= ($data['Type'] == 'Sirup') ? 'selected' : '' ?>>Sirup</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="stok">Stok</label>
                  <input type="number" id="stok" name="stok" class="form-control" value="<?= htmlspecialchars($data["Stock"]) ?>" min="0" required>
                </div>

                <div class="form-group">
                  <label for="harga">Harga (Rp)</label>
                  <input type="number" id="harga" name="harga" class="form-control" value="<?= htmlspecialchars($data["Price"]) ?>" min="0" required>
                </div>
              </div>

              <div class="form-group">
                <label>Gambar Obat Saat Ini</label>
                <div style="display: flex; align-items: center; gap: 14px; margin-top: 4px;">
                  <img src="<?= $imgPath ?>" alt="Preview" style="width: 64px; height: 64px; border-radius: 8px; background: #fff; object-fit: contain; padding: 4px;">
                  <div style="flex: 1;">
                    <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp" style="padding-top: 7px;">
                    <small style="color: #94a3b8; font-size: 12px;">Kosongkan jika tidak ingin mengganti gambar.</small>
                  </div>
                </div>
              </div>

              <div style="display: flex; gap: 10px; margin-top: 20px;">
                <a href="indexAdmin.php" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-emerald" style="flex: 1;">Simpan Perubahan</button>
              </div>
            </form>
          </div>
        </section>
      </main>
    </div>
  </div>

</body>
</html>