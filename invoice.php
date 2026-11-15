<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: indexCustomer.php");
    exit;
}

// 1. Ambil data kepala transaksi
$qPurchase = mysqli_query($connect, "
    SELECT p.*, a.username, a.email
    FROM purchase p
    JOIN accounts a ON p.customerID = a.ID
    WHERE p.purchaseID = $id
    LIMIT 1
");
$purchase = mysqli_fetch_assoc($qPurchase);

if (!$purchase) {
    header("Location: indexCustomer.php");
    exit;
}

// 2. Ambil rincian obat yang dibeli
$qItems = mysqli_query($connect, "
    SELECT pd.*, m.ID as medID, m.Name, m.Category, m.Type, m.Price, m.Image
    FROM purchasedetail pd
    JOIN meds m ON pd.medID = m.ID
    WHERE pd.purchaseID = $id
    ORDER BY m.Name ASC
");

$noPesanan = 'HC-' . date('Ymd', strtotime($purchase['purchaseTime'])) . '-' . str_pad($purchase['purchaseID'], 4, '0', STR_PAD_LEFT);
$userRole = $_SESSION['role'] ?? 'Customer';
$kembaliUrl = ($userRole === 'Admin') ? 'adminHistori.php' : 'customerHistori.php';

$firstMedId = null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice #<?= $noPesanan ?> - HealthyCare</title>
  <link rel="stylesheet" href="dashboard.css">
  <script src="theme.js"></script>
  <style>
    /* Sembunyikan tombol aksi saat dicetak ke PDF/kertas */
    @media print {
      .receipt-actions { display: none !important; }
      body { background: #ffffff !important; }
      .receipt-paper { box-shadow: none !important; padding: 0 !important; }
    }
  </style>
</head>
<body>

  <div class="receipt-wrapper">
    <div class="receipt-paper">
      <div class="receipt-head">
        <div class="receipt-brand">
          <img src="assets/app-icon/appLogo.png" alt="HealthyCare">
          <span>HealthyCare</span>
        </div>
        <div style="text-align: right;">
          <strong style="font-size: 16px;">Nota Pesanan</strong>
          <div style="font-size: 12px; color: #64748b;">Apotek Digital Terpercaya</div>
        </div>
      </div>

      <div class="receipt-meta-grid">
        <div class="receipt-meta-item">
          <span>Nama Pembeli</span>
          <strong><?= htmlspecialchars($purchase['username']) ?></strong>
        </div>
        <div class="receipt-meta-item">
          <span>No. Pesanan</span>
          <strong><?= $noPesanan ?></strong>
        </div>
        <div class="receipt-meta-item">
          <span>Tanggal Transaksi</span>
          <strong><?= date('d M Y, H:i', strtotime($purchase['purchaseTime'])) ?> WIB</strong>
        </div>
        <div class="receipt-meta-item">
          <span>Status Pembayaran</span>
          <strong style="color: #059669;">Lunas</strong>
        </div>
      </div>

      <table class="receipt-table">
        <thead>
          <tr>
            <th width="40">No.</th>
            <th>Obat &amp; Detail</th>
            <th width="120">Harga Satuan</th>
            <th width="70" class="text-center">Jumlah</th>
            <th width="130" class="text-right">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($item = mysqli_fetch_assoc($qItems)): ?>
            <?php
              if (!$firstMedId) {
                  $firstMedId = $item['medID'];
              }
              $imgPath = (!empty($item['Image']) && file_exists("assets/product-image/" . $item['Image']))
                ? "assets/product-image/" . htmlspecialchars($item['Image'])
                : "assets/app-icon/appLogo.png";
              $subtotalItem = $item['Price'] * $item['amount'];
            ?>
            <tr>
              <td><?= $no++ ?></td>
              <td>
                <div class="receipt-item-cell">
                  <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($item['Name']) ?>" class="receipt-item-thumb">
                  <div>
                    <strong><?= htmlspecialchars($item['Name']) ?></strong>
                    <div style="font-size: 12px; color: #64748b;">
                      <?= htmlspecialchars($item['Category']) ?> &bull; <?= htmlspecialchars($item['Type']) ?>
                    </div>
                  </div>
                </div>
              </td>
              <td>Rp <?= number_format($item['Price'], 0, ',', '.') ?></td>
              <td class="text-center"><?= (int)$item['amount'] ?></td>
              <td class="text-right"><strong>Rp <?= number_format($subtotalItem, 0, ',', '.') ?></strong></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <div class="receipt-totals">
        <div class="receipt-total-row grand">
          <span>Total Pembayaran</span>
          <span style="color: #059669;">Rp <?= number_format($purchase['totalPurchase'], 0, ',', '.') ?></span>
        </div>
      </div>

      <div class="receipt-actions">
        <a href="<?= $kembaliUrl ?>" class="btn btn-outline" style="color: #334155; border-color: #cbd5e1;">
          &laquo; Kembali
        </a>
        <div style="display: flex; gap: 8px;">
          <!-- Tombol Cetak Nota -->
          <button type="button" onclick="window.print()" class="btn btn-outline" style="color: #334155; border-color: #cbd5e1;">
            <img src="assets/app-icon/printer.png" alt="Print" style="width: 20px; height: 20px; object-fit: contain;">Cetak Nota
          </button>
          <?php if ($userRole !== 'Admin'): ?>
            <a href="indexCustomer.php<?= $firstMedId ? '#detail-obat-' . $firstMedId : '' ?>" class="btn btn-emerald">
              Beli Lagi
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

</body>
</html>