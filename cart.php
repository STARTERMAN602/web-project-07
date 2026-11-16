<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION["userID"])) {
  header("location: login.php?status=not_loged");
  exit;
}

if (isset($_GET['hapus'])) {
  $hapusId = $_GET['hapus'];
  unset($_SESSION['cart'][$hapusId]);
  header("Location: cart.php");
  exit;
}

$cartItems  = [];
$grandTotal = 0;
$totalQty   = 0;

if (!empty($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $medId => $qty) {
    $medId = (int)$medId;
    $qty   = (int)$qty;

    if ($qty <= 0) {
      unset($_SESSION['cart'][$medId]);
      continue;
    }

    $res = mysqli_query($connect, "select * from meds where ID = $medId limit 1");
    if ($med = mysqli_fetch_assoc($res)) {
      
      if ($qty > $med['Stock']) {
        $qty = (int)$med['Stock'];
        $_SESSION['cart'][$medId] = $qty;
      }

      $subtotal    = $med['Price'] * $qty;
      $grandTotal += $subtotal;
      $totalQty   += $qty;

      $cartItems[] = [
        'id'       => $med['ID'],
        'name'     => $med['Name'],
        'category' => $med['Category'],
        'type'     => $med['Type'],
        'price'    => $med['Price'],
        'stock'    => $med['Stock'],
        'image'    => $med['Image'],
        'qty'      => $qty,
        'subtotal' => $subtotal
      ];
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart - HealthyCare</title>
  <link rel="stylesheet" href="dashboard.css">
  <script src="theme.js"></script>
</head>
<body>

  <div class="page-container">
    <div class="page-top-bar" style="justify-content: flex-end;">
      <a href="indexCustomer.php" class="btn btn-outline btn-sm">+ Add other medicine</a>
    </div>

    <?php if (!empty($cartItems)): ?>
      <form action="transactionSucces.php" method="POST">
        <div class="cart-list">
          <?php foreach ($cartItems as $item): ?>
            <?php
              $imgPath = (!empty($item['image']) && file_exists("assets/product-image/" . $item['image'])) ? "assets/product-image/" . $item['image'] : "assets/app-icon/appLogo.png";

              $bisaTambah = ($item['qty'] < $item['stock']);
            ?>
            <div class="cart-item">
              <input type="checkbox" name="selected_items[]" value="<?= $item['id'] ?>" class="cart-check item-checkbox" checked>
              <img src="<?= $imgPath ?>" alt="<?= $item['name'] ?>" class="cart-thumb">

              <div class="cart-info">
                <div class="cart-title"><?= $item['name'] ?></div>
                <span class="cart-variant">
                  <?= $item['category'] ?> &bull; <?= $item['type'] ?>
                </span>

                <div class="cart-price-row">
                  <span class="cart-price">Rp <?= number_format($item['price'], 0, ',', '.') ?></span>

                  <div class="cart-actions">
                    <div class="qty-control">
                      <a href="kurangCart.php?id=<?= $item['id'] ?>" class="qty-btn" style="text-decoration:none; display:flex; align-items:center; justify-content:center;">-</a>
                      
                      <span class="qty-input" style="display:flex; align-items:center; justify-content:center;">
                        <?= $item['qty'] ?>
                      </span>

                      <?php if ($bisaTambah): ?>
                        <a href="tambahCart.php?id=<?= $item['id'] ?>" class="qty-btn" style="text-decoration:none; display:flex; align-items:center; justify-content:center;">+</a>
                      <?php else: ?>
                        <span class="qty-btn" style="opacity:0.3; cursor:not-allowed; display:flex; align-items:center; justify-content:center;" title="Stok maksimal tercapai">+</span>
                      <?php endif; ?>
                    </div>

                    <a href="cart.php?hapus=<?= $item['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete <?= $item['name'] ?> from cart?')">
                      Delete
                    </a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Bar Checkout di Bawah -->
        <div class="cart-summary-bar">
          <div class="cart-summary-inner">
            <div class="summary-left" style="display: flex; align-items: center; gap: 16px;">
              <label style="cursor: pointer; display: flex; align-items: center; gap: 8px;"></label>
                <input type="checkbox" id="select-all" class="cart-check" checked>
                <span style="font-size: 14px;">Check all</span>
              </label>
              <span style="color: var(--text-muted); font-size: 13.5px;"></span>
                Total Items: <strong><?= $totalQty ?> pcs</strong>
              </span>
            </div>

            <div class="summary-right">
              <div class="total-box">
                <div class="total-label">Total Purchase</div>
                <div class="total-amount">Rp <?= number_format($grandTotal, 0, ',', '.') ?></div>
              </div>
              <button type="submit" class="btn btn-emerald" style="height: 42px; padding: 0 24px;">
                Checkout (<?= $totalQty ?>)
              </button>
            </div>
          </div>
        </div>
      </form>

    <?php else: ?>
      <!-- Tampilan Jika Keranjang Kosong -->
      <div class="table-card" style="padding: 50px 20px; text-align: center;">
        <!-- Menggunakan cartLogo.png -->
        <img src="assets/app-icon/cartLogo.png" alt="Keranjang Kosong" style="width: 85px; height: 85px; object-fit: contain; margin-bottom: 16px; display: inline-block;">
  
        <h2 style="font-size: 19px; margin-bottom: 8px;">Cart is still empty</h2>
        <p style="color: #94a3b8; font-size: 14px; margin-bottom: 22px;">
          Choose medicines from our catalog.
        </p>
        <a href="indexCustomer.php" class="btn btn-emerald">
          Look catalog
        </a>
      </div>
    <?php endif; ?>
  </div>

<script>
  const selectAll = document.getElementById('select-all');
  const itemCheckboxes = document.querySelectorAll('.item-checkbox');

  if (selectAll) {
    selectAll.addEventListener('change', function() {
      itemCheckboxes.forEach(cb => {
        cb.checked = this.checked;
      });
    });
  }
</script>

</body>
</html>