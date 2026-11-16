<?php
session_start();
if (!isset($_SESSION["userID"])) {
  header("location: login.php?status=not_loged");
  exit;
}
include "koneksi.php";
$id = $_GET["id"];

$query = mysqli_query($connect, "select * from meds where ID = $id");
$data = $query->fetch_assoc();

$gambar = (!empty($data["Image"]) && file_exists("assets/product-image/" . $data["Image"])) ? "assets/product-image/" . $data["Image"] : "assets/app-icon/appLogo.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Medicine - HealthyCare Admin</title>
  <link rel="stylesheet" href="dashboard.css">
  <script src="theme.js"></script>
</head>
<body>

  <div class="app-layout">
    <aside class="sidebar">
      <a href="indexAdmin.php" class="sidebar-brand">
        <div class="brand-icon">
          <img src="assets/app-icon/appLogo.png" alt="Logo HealthyCare">
        </div>
        <span class="brand-name">HealthyCare</span>
      </a>

      <nav class="sidebar-nav">
        <div class="nav-label">Management</div>
        <a href="indexAdmin.php" class="nav-link active">Manage Medicines</a>
        <a href="adminHistori.php" class="nav-link">Purchase History</a>
      </nav>
    </aside>

    <div class="main-content">
      <header class="topbar">
        <div style="display: flex; align-items: center; gap: 10px;">
          <button type="button" class="theme-btn-top" id="btn-theme" title="Ganti Mode Terang / Gelap">
            <img src="assets/app-icon/sun.png" id="icon-theme" alt="Mode">
          </button>
          <a href="user.php" class="user-pill">
            <div class="user-avatar-sm">
              <img src="assets/user-icon/admin.png" alt="Admin">
            </div>
            <span><?= $_SESSION['username'] ?></span>
          </a>
        </div>
      </header>

      <main class="page-body">
        <section style="max-width: 620px; width: 100%;">
          <div class="section-header">
            <div>
              <h1 class="section-title">Edit Medicine's Data</h1>
              <p class="section-subtitle">Update information, stock, and image of medicine #<?= $data["ID"] ?>.</p>
            </div>
          </div>

          <div class="table-card" style="padding: 24px;">
            <form action="editData.php" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?= $data["ID"] ?>">
              <input type="hidden" name="old_image" value="<?= $data["Image"] ?? '' ?>">

              <div class="form-group">
                <label for="nama">Name</label>
                <input type="text" id="nama" name="nama" class="form-control" value="<?= $data["Name"] ?>" required>
              </div>

              <div class="form-grid">
                <div class="form-group">
                  <label for="kategori">Category</label>
                  <select id="kategori" name="kategori" class="form-control" required>
                    <option value="Paracetamol" <?= ($data['Category'] == 'Paracetamol') ? 'selected' : '' ?>>Paracetamol</option>
                    <option value="Ibuprofen" <?= ($data['Category'] == 'Ibuprofen') ? 'selected' : '' ?>>Ibuprofen</option>
                    <option value="Vitamins" <?= ($data['Category'] == 'Vitamins') ? 'selected' : '' ?>>Vitamins</option>
                    <option value="Stomach Medication" <?= ($data['Category'] == 'Stomach Medication') ? 'selected' : '' ?>>Stomach Medication</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="tipe">Type</label>
                  <select id="tipe" name="tipe" class="form-control" required>
                    <option value="Tablet" <?= ($data['Type'] == 'Tablet') ? 'selected' : '' ?>>Tablet</option>
                    <option value="Capsule" <?= ($data['Type'] == 'Capsule') ? 'selected' : '' ?>>Capsule</option>
                    <option value="Syrup" <?= ($data['Type'] == 'Syrup') ? 'selected' : '' ?>>Syrup</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="stok">Stock</label>
                  <input type="number" id="stok" name="stok" class="form-control" value="<?= $data["Stock"] ?>" min="0" required>
                </div>

                <div class="form-group">
                  <label for="harga">Price (Rp)</label>
                  <input type="number" id="harga" name="harga" class="form-control" value="<?= $data["Price"] ?>" min="0" required>
                </div>
              </div>

              <div class="form-group">
                <label>Curent medicine's image</label>
                <div style="display: flex; align-items: center; gap: 14px; margin-top: 4px;">
                  <img src="<?= $gambar ?>" alt="Preview" style="width: 64px; height: 64px; border-radius: 8px; background: #fff; object-fit: contain; padding: 4px;">
                  <div style="flex: 1;">
                    <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp" style="padding-top: 7px;"> <br>
                    <small style="color: #94a3b8; font-size: 12px;">Leave empty if you don't want to change it.</small>
                  </div>
                </div>
              </div>

              <div style="display: flex; gap: 10px; margin-top: 20px;">
                <a href="indexAdmin.php" class="btn btn-outline">Cancle</a>
                <button id="btn-simpan" type="submit" class="btn btn-emerald" style="flex: 1;">Save Changes</button>
              </div>
            </form>
          </div>
        </section>
      </main>
    </div>
  </div>

<script>
  const form = document.querySelector('form');
  const btn = document.getElementById('btn-simpan');
  btn.disabled = true; btn.style.opacity = '0.5'; btn.style.cursor = 'not-allowed';
  form.addEventListener('input', () => {
    btn.disabled = false; btn.style.opacity = '1'; btn.style.cursor = 'pointer';
  });
</script>

</body>
</html>