<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION["userID"])) {
  header("location: login.php?status=not_loged");
  exit;
}

$customerID = $_SESSION["userID"];

$purchaseID = null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_SESSION["cart"])) {
    $itemsToBuy    = [];
    $totalPurchase = 0;

    foreach ($_SESSION["cart"] as $medId => $qty) {
        $medId = (int)$medId;
        $qty   = (int)$qty;
        if ($qty <= 0) continue;

        $qMed = mysqli_query($connect, "select * from meds where ID = $medId limit 1");
        if ($med = mysqli_fetch_assoc($qMed)) {
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
        $now = date('Y-m-d H:i:s');
        $stmtP = $connect->prepare("INSERT INTO purchase (customerID, purchaseTime, totalPurchase) VALUES (?, ?, ?)");
        $stmtP->bind_param("isi", $customerID, $now, $totalPurchase);
        $stmtP->execute();
        $purchaseID = $stmtP->insert_id;

        $stmtDetail = $connect->prepare("INSERT INTO purchasedetail (purchaseID, medID, amount) VALUES (?, ?, ?)");
        $stmtStock  = $connect->prepare("UPDATE meds SET Stock = Stock - ? WHERE ID = ?");

        foreach ($itemsToBuy as $item) {
            $stmtDetail->bind_param("iii", $purchaseID, $item['id'], $item['qty']);
            $stmtDetail->execute();

            $stmtStock->bind_param("ii", $item['qty'], $item['id']);
            $stmtStock->execute();
        }

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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaction Succes - HealthyCare</title>
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

      <h1 class="success-title">Transaction Succes</h1>

      <div class="success-rows">
        <div class="success-row">
          <span>Date</span>
          <span><?= date('d M Y, H:i', strtotime($order['purchaseTime'])) ?> WIB</span>
        </div>
        <div class="success-row">
          <span>Transaction Number</span>
          <span><?= $noPesanan ?></span>
        </div>
        <div class="success-row">
          <span>Customer Name</span>
          <span><?= $order['username'] ?></span>
        </div>
        <div class="success-row">
          <span>Description</span>
          <span>Medicines purchased (<?= (int)$order['totalItem'] ?> Items)</span>
        </div>
        <div class="success-row">
          <span>Total Purchase</span>
          <span style="color: #059669; font-weight: 700;">Rp <?= number_format($order['totalPurchase'], 0, ',', '.') ?></span>
        </div>
      </div>

      <div class="success-btns">
        <a href="indexCustomer.php" class="btn btn-outline" style="width: 100%; color: #334155; border-color: #cbd5e1;">
          Back to dashboard
        </a>
      </div>
    </div>
  </div>

</body>
</html>