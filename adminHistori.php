<?php
session_start();
if (!isset($_SESSION["userID"])) {
  header("location: login.php?status=not_loged");
  exit;
}
include("koneksi.php");

$where = "";
if(isset($_GET["cari"])) {
  $cari = $_GET["cari"];
  $where = "where p.purchaseID in (select p2.purchaseID from purchase p2 join accounts a2 on p2.customerID = a2.ID join purchasedetail pd2 on p2.purchaseID = pd2.purchaseID join meds m2 ON pd2.medID = m2.ID where a2.username like '%$cari%')";
} else {
  $where = "where p.purchaseID in (select p2.purchaseID from purchase p2 join accounts a2 on p2.customerID = a2.ID join purchasedetail pd2 on p2.purchaseID = pd2.purchaseID join meds m2 ON pd2.medID = m2.ID)";
}

$query = mysqli_query($connect, "select p.purchaseID, p.purchaseTime, p.totalPurchase, a.username as customerName, sum(pd.amount) as kuanti from purchase p join accounts a on p.customerID = a.ID join purchasedetail pd on p.purchaseID = pd.purchaseID join meds m on pd.medID = m.ID $where group by p.purchaseID, p.purchaseTime, p.totalPurchase, a.username order by p.purchaseTime DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Purchase History - HealthyCare Admin</title>
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
        <a href="indexAdmin.php" class="nav-link">Manage Medicines</a>
        <a href="adminHistori.php" class="nav-link active">Purchase History</a>
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

          <input type="text" name="cari" class="search-input" placeholder="Search customer username..." value="<?= $cari ?? '' ?>" autocomplete="off">

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
            <span><?= $_SESSION['username'] ?></span>
          </a>
        </div>
      </header>

      <main class="page-body">
        <section id="histori-pembelian">
          <div class="section-header">
            <div>
              <h1 class="section-title">Customers Purchase History</h1>
              <p class="section-subtitle">All purchase history of customers.</p>
            </div>
          </div>

          <div class="table-card">
            <div class="table-wrap">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Purchase Date</th>
                    <th>Customer Username</th>
                    <th>Purchased meds</th>
                    <th>Quantity</th>
                    <th>Purchase Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  if ($query && $query->num_rows > 0){ 
                    while ($purchase = $query->fetch_assoc()){
                      $pid = $purchase['purchaseID'];
      
                      $itemsQuery = mysqli_query($connect, "select m.Name, m.Image, pd.amount from purchasedetail pd join meds m on pd.medID = m.ID where pd.purchaseID = $pid order by m.Name ASC");
                  ?>
                      <tr>
                        <td><?= date('d M Y, H:i', strtotime($purchase['purchaseTime'])) ?></td>
                        <td><strong><?= $purchase['customerName'] ?></strong></td>
                        <td>
                          <div style="display: flex; flex-direction: column; gap: 8px;">
                            <?php 
                            while ($item = $itemsQuery->fetch_assoc()){
                              $imgPath = (!empty($item['Image']) && file_exists("assets/product-image/" . $item['Image'])) ? "assets/product-image/" . $item['Image'] : "assets/app-icon/appLogo.png";
                            ?>
                              <div class="td-product">
                                <img src="<?= $imgPath ?>" alt="<?= $item['Name'] ?>" class="td-thumb">
                                <span>
                                  <?= $item['Name'] ?>
                                  <small style="color: #94a3b8; font-weight: 400;">(x<?= $item['amount'] ?>)</small>
                                </span>
                              </div>
                            <?php 
                            } 
                            ?>
                          </div>
                        </td>
                        <td><?= $purchase['kuanti'] ?> pcs</td>
                        <td><strong>Rp <?= number_format($purchase['totalPurchase'], 0, ',', '.') ?></strong></td>
                      </tr>
                    <?php 
                    }
                    ?>
                  <?php 
                  }else{
                  ?>
                    <tr>
                      <td colspan="6" style="text-align: center; padding: 28px; color: #94a3b8;">
                        No purchase history yet.
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