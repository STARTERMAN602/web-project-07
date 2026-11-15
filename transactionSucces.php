<?php
session_start();
include("koneksi.php");

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$customerID = $_SESSION['id'] ?? null;
if (!$customerID) {
    $uName = mysqli_real_escape_string($connect, $_SESSION['username']);
    $qUser = mysqli_query($connect, "SELECT ID FROM accounts WHERE username = '$uName' LIMIT 1");
    if ($rUser = mysqli_fetch_assoc($qUser)) {
        $customerID = $rUser['ID'];
        $_SESSION['id'] = $customerID;
    }
}

$purchaseID = null;

// ==========================================
// PROSES SIMPAN TRANSAKSI DARI CHECKOUT CART
// ==========================================
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_SESSION["cart"])) {
    $itemsToBuy    = [];
    $totalPurchase = 0;

    foreach ($_SESSION["cart"] as $medId => $qty) {
        $medId = (int)$medId;
        $qty   = (int)$qty;
        if ($qty <= 0) continue;

        $qMed = mysqli_query($connect, "SELECT * FROM meds WHERE ID = $medId LIMIT 1");
        if ($med = mysqli_fetch_assoc($qMed)) {
            // Jika stok tidak cukup, batasi dengan stok yang ada
            $qtyBeli = min($qty, (int)$med['Stock']);
            if ($qtyBeli > 0) {
                $subtotal = $med['Price'] * $qtyBeli;
                $totalPurchase += $subtotal;
                $itemsToBuy[] = [
                    'id'    => $med['ID'],
                    'qty'   => $qtyBeli,
                    'price' => $med['Price']
                ];
            }
        }
    }

    if (!empty($itemsToBuy)) {
        // 1. Simpan ke tabel purchase
        $now = date('Y-m-d H:i:s');
        $stmtP = $connect->prepare("INSERT INTO purchase (customerID, purchaseTime, totalPurchase) VALUES (?, ?, ?)");
        $stmtP->bind_param("isi", $customerID, $now, $totalPurchase);
        $stmtP->execute();
        $purchaseID = $stmtP->insert_id;

        // 2. Simpan ke tabel purchasedetail & kurangi stok di meds
        $stmtDetail = $connect->prepare("INSERT INTO purchasedetail (purchaseID, medID, amount) VALUES (?, ?, ?)");
        $stmtStock  = $connect->prepare("UPDATE meds SET Stock = Stock - ? WHERE ID = ?");

        foreach ($itemsToBuy as $item) {
            // Insert detail
            $stmtDetail->bind_param("iii", $purchaseID, $item['id'], $item['qty']);
            $stmtDetail->execute();

            // Potong stok
            $stmtStock->bind_param("ii", $item['qty'], $item['id']);
            $stmtStock->execute();
        }

        // 3. Kosongkan keranjang belanja
        unset($_SESSION["cart"]);
    }
} elseif (isset($_GET['id'])) {
    $purchaseID = (int)$_GET['id'];
}

// Jika tidak ada ID transaksi, kembalikan ke beranda
if (!$purchaseID) {
    header("Location: indexCustomer.php");
    exit;
}

// Ambil data transaksi dari database
$qOrder = mysqli_query($connect, "
    SELECT p.*, a.username, SUM(pd.amount) as totalItem
    FROM purchase p
    JOIN accounts a ON p.customerID = a.ID
    JOIN purchasedetail pd ON p.purchaseID = pd.purchaseID
    WHERE p.purchaseID = $purchaseID
    GROUP BY p.purchaseID
    LIMIT 1
");
$order = mysqli_fetch_assoc($qOrder);

if (!$order) {
    header("Location: indexCustomer.php");
    exit;
}

$noPesanan = 'HC-' . date('Ymd', strtotime($order['purchaseTime'])) . '-' . str_pad($order['purchaseID'], 4, '0', STR_PAD_LEFT);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaksi Berhasil - HealthyCare</title>
  <link rel="stylesheet" href="dashboard.css">
  <script src="theme.js"></script>
</head>
<body>

  <div class="success-page">
    <div class="success-hero"></div>

    <div class="success-brand">
      <div class="brand-icon">
        <img src="assets/app-icon/appLogo.png" alt="Logo HealthyCare">
      </div>
      <span>HealthyCare</span>
    </div>

    <div class="success-card">
      <div class="success-check">
        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
      </div>

      <h1 class="success-title">Transaksi Berhasil</h1>

      <div class="success-rows">
        <div class="success-row">
          <span>Tanggal</span>
          <span><?= date('d M Y, H:i', strtotime($order['purchaseTime'])) ?> WIB</span>
        </div>
        <div class="success-row">
          <span>No. Pesanan</span>
          <span><?= $noPesanan ?></span>
        </div>
        <div class="success-row">
          <span>Nama Pembeli</span>
          <span><?= htmlspecialchars($order['username']) ?></span>
        </div>
        <div class="success-row">
          <span>Keterangan</span>
          <span>Pembelian Obat (<?= (int)$order['totalItem'] ?> Item)</span>
        </div>
        <div class="success-row">
          <span>Total Pembayaran</span>
          <span style="color: #059669; font-weight: 700;">Rp <?= number_format($order['totalPurchase'], 0, ',', '.') ?></span>
        </div>
      </div>

      <div class="success-btns">
        <a href="invoice.php?id=<?= $purchaseID ?>" class="btn btn-emerald" style="width: 100%; height: 42px;">
          Lihat Detail Invoice
        </a>
        <a href="indexCustomer.php" class="btn btn-outline" style="width: 100%; color: #334155; border-color: #cbd5e1;">
          Kembali ke Beranda
        </a>
      </div>
    </div>
  </div>

</body>
</html>