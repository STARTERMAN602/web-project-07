<?php
session_start();
if(!isset($_SESSION["userID"])) {
  header("location: login.php?status=not_loged");
  exit;
}
include("koneksi.php");

if(isset($_GET["cari"])) {
  $cari = $_GET['cari'];
  $query = mysqli_query($connect, "select * from meds where (Name like '%$cari%' or Category like '%$cari%') and isDeleted = 0");
} else {
  $query = mysqli_query($connect, "select * from meds where isDeleted = 0");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meds Management - HealthyCare Admin</title>
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
        <div style="display: flex; align-items: center; gap: 10px;">
          <button type="button" class="theme-btn-top" id="btn-theme" title="Ganti Mode Terang / Gelap">
            <img src="assets/app-icon/sun.png" id="icon-theme" alt="Mode">
          </button>
          <a href="user.php" class="user-pill">
            <div class="user-avatar-sm">
              <img src="assets/user-icon/admin.png" alt="Admin">
            </div>
            <span><?= $_SESSION["username"] ?></span>
          </a>
        </div>
      </header>

      <main class="page-body">
        <section id="kelola-obat">
          <div class="section-header">
            <div>
              <h1 class="section-title">Manage Medicines</h1>
              <p class="section-subtitle">Add, Update, and manage medicine's stocks.</p>
            </div>
            <div style="display: flex; gap: 8px;">
              <a href="export.php?" class="btn btn-outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                  <polyline points="7 10 12 15 17 10"/>
                  <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Export Excel
              </a>
              <a href="tambah.php" class="btn btn-emerald">+ Add medicine</a>
            </div>
          </div>

          <div class="table-card">
            <div class="table-wrap">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  if ($query && $query->num_rows > 0) { 
                    while ($data = $query->fetch_assoc()) {
                      $stockClass = ($data["Stock"] <= 5) ? (($data["Stock"]==0) ? "badge-danger" : "badge-warn") : "badge-emerald";
                      $imgPath = (!empty($data["Image"]) && file_exists("assets/product-image/" . $data["Image"])) ? "assets/product-image/" . $data["Image"] : "assets/app-icon/appLogo.png";
                  ?>
                      <tr>
                        <td>#<?= $data["ID"] ?></td>
                        <td>
                          <div class="td-product">
                            <img src="<?= $imgPath ?>" alt="<?= $data["Name"] ?>" class="td-thumb">
                            <span><?= $data["Name"] ?></span>
                          </div>
                        </td>
                        <td><span class="badge badge-slate"><?= $data["Category"] ?></span></td>
                        <td><?= $data["Type"] ?></td>
                        <td>Rp <?= number_format($data["Price"], 0, ',', '.') ?></td>
                        <td><span class="badge <?= $stockClass ?>"><?=$data["Stock"] ?></span></td>
                        <td>
                          <div class="td-actions">
                            <a href="edit.php?id=<?= $data["ID"] ?>" class="btn btn-outline btn-sm">Edit</a>
                            <a href="hapus.php?id=<?= $data["ID"] ?>" class="btn btn-danger btn-sm" data-nama="<?= $data["Name"] ?>" onclick="return confirm('Delete ' + this.dataset.nama + '?')">Delete</a>
                          </div>
                        </td>
                      </tr>
                    <?php 
                      }
                    } else { 
                    ?>
                    <tr>
                      <td colspan="7" style="text-align: center; padding: 28px; color: #94a3b8;">
                        Medicine not found.
                      </td>
                    </tr>
                  <?php 
                  } 
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>

<script>
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