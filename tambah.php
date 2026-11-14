<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Obat - HealthyCare Admin</title>
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
        <a href="admin-histori.php" class="nav-link">Histori Pembelian</a>

        <div class="nav-label">Akun</div>
        <a href="user.php" class="nav-link">Profil Admin</a>
        <a href="logout.php" class="nav-link logout">Logout</a>
      </nav>
    </aside>

    <!-- Konten Utama -->
    <div class="main-content">
      <header class="topbar">
        <a href="indexAdmin.php" class="btn btn-outline btn-sm">&larr; Kembali ke Daftar Obat</a>
        <a href="user.php" class="user-pill">
          <div class="user-avatar-sm">A</div>
          <span>Admin Apotek</span>
        </a>
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
                  <select id="kategori" name="kategori" class="form-control" required style="background-color: #192b26; color: #fff;">
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    <option value="Paracetamol">Paracetamol</option>
                    <option value="Ibuprofen">Ibuprofen</option>
                    <option value="Vitamin">Vitamin</option>
                    <option value="Obat Maag">Obat Maag</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="tipe">Tipe</label>
                  <select id="tipe" name="tipe" class="form-control" required style="background-color: #192b26; color: #fff;">
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
                <button type="submit" class="btn btn-emerald" style="flex: 1;">Tambahkan Obat</button>
              </div>
            </form>
          </div>
        </section>
      </main>
    </div>
  </div>

</body>
</html>