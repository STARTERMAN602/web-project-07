<?php
error_reporting(0);
ini_set('display_errors', 0);

if (ob_get_level()) {
    ob_end_clean();
}

session_start();
if (!isset($_SESSION["userID"])) {
  header("location: login.php?status=not_loged");
  exit;
}
include("koneksi.php");


// Set Header agar dibaca sebagai Microsoft Excel (.xls)
header("Content-Type: application/vnd.ms-excel; charset=utf-8");


// ==========================================
// 2. EXPORT DATA STOK OBAT
// ==========================================
$filename = "Laporan_Stok_Obat_HealthyCare_" . date('Y-m-d') . ".xls";
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

$query = mysqli_query($connect, "SELECT * FROM meds ORDER BY Name ASC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: Arial, sans-serif; }
    .judul { font-size: 16pt; font-weight: bold; color: #059669; margin-bottom: 4px; }
    .subjudul { font-size: 10pt; color: #555; margin-bottom: 15px; }
    table { border-collapse: collapse; width: 100%; }
    th { background-color: #059669; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #047857; padding: 8px 12px; }
    td { border: 1px solid #cbd5e1; padding: 6px 10px; font-size: 10pt; vertical-align: middle; }
    .center { text-align: center; }
    .right { text-align: right; }
  </style>
</head>
<body>
  <div class="judul">HEALTHYCARE - LAPORAN DATA STOK OBAT</div>
  <div class="subjudul">Dicetak pada: <?= date('d F Y, H:i') ?> WIB</div>

  <table>
    <thead>
      <tr>
        <th width="40">No</th>
        <th width="70">ID</th>
        <th width="180">Nama Obat</th>
        <th width="130">Kategori</th>
        <th width="100">Tipe</th>
        <th width="120">Harga</th>
        <th width="90">Stok</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($query && $query->num_rows > 0): ?>
        <?php $no = 1; while ($row = $query->fetch_assoc()): ?>
          <tr>
            <td class="center"><?= $no++ ?></td>
            <td class="center">#<?= $row['ID'] ?></td>
            <td><strong><?= htmlspecialchars($row['Name']) ?></strong></td>
            <td><?= htmlspecialchars($row['Category']) ?></td>
            <td class="center"><?= htmlspecialchars($row['Type']) ?></td>
            <td class="right">Rp <?= number_format($row['Price'], 0, ',', '.') ?></td>
            <td class="center"><?= (int) $row['Stock'] ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" class="center">Tidak ada data obat.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
<?php
exit;
?>