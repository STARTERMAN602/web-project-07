<?php
session_start(); // <-- Tambahkan baris ini di paling atas
include("koneksi.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Obat - HealthyCare Admin</title>
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
              <h1 class="section-title">Tambah Obat Baru</h1>
              <p class="section-subtitle">Masukkan data obat baru ke katalog HealthyCare.</p>
            </div>
          </div>

          <div class="table-card" style="padding: 24px;">
            <form action="tambahData.php" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <label for="nama">Nama Obat</label>
                <input type="text" id="nama" name="nama" class="form-control" placeholder="Contoh: Paracetamol 500mg" required autofocus>
              </div>

              <div class="form-grid">
                <div class="form-group">
                  <label for="kategori">Kategori</label>
                  <select id="kategori" name="kategori" class="form-control" required>
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    <option value="Paracetamol">Paracetamol</option>
                    <option value="Ibuprofen">Ibuprofen</option>
                    <option value="Vitamin">Vitamin</option>
                    <option value="Obat Maag">Obat Maag</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="tipe">Tipe</label>
                  <select id="tipe" name="tipe" class="form-control" required>
                    <option value="" disabled selected>-- Pilih Tipe --</option>
                    <option value="Tablet">Tablet</option>
                    <option value="Kapsul">Kapsul</option>
                    <option value="Sirup">Sirup</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="stok">Stok</label>
                  <input type="number" id="stok" name="stok" class="form-control" placeholder="10" min="0" required>
                </div>

                <div class="form-group">
                  <label for="harga">Harga (Rp)</label>
                  <input type="number" id="harga" name="harga" class="form-control" placeholder="5000" min="0" required>
                </div>
              </div>

              <div class="form-group">
                <label for="image">Gambar Obat</label>
                <input type="file" id="image" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp" style="padding-top: 7px;">
                <small style="color: #94a3b8; font-size: 12px;">Format: JPG, PNG, atau WEBP (opsional).</small>
              </div>

              <div style="display: flex; gap: 10px; margin-top: 20px;">
                <a href="indexAdmin.php" class="btn btn-outline">Batal</a>
                <button id="btn-tambah" type="submit" class="btn btn-emerald" style="flex: 1;">Tambahkan Obat</button>
              </div>
            </form>
          </div>
        </section>
      </main>
    </div>
  </div>

<script>
  const form = document.querySelector('form');
  const btn = document.getElementById('btn-tambah');
  btn.disabled = true; btn.style.opacity = '0.5'; btn.style.cursor = 'not-allowed';
  form.addEventListener('input', () => {
    btn.disabled = false; btn.style.opacity = '1'; btn.style.cursor = 'pointer';
  });
</script>

</body>
</html>