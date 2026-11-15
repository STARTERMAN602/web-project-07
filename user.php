<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['id'] ?? null;
$error = "";
$success = "";

// Ambil ID dari database jika belum ada di session
if (!$userId) {
    $uName = mysqli_real_escape_string($connect, $_SESSION['username']);
    $qUser = mysqli_query($connect, "SELECT ID FROM accounts WHERE username = '$uName' LIMIT 1");
    if ($rUser = mysqli_fetch_assoc($qUser)) {
        $userId = $rUser['ID'];
        $_SESSION['id'] = $userId;
    }
}

// PROSES UPDATE AKUN
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_akun"])) {
    $newUsername = trim($_POST["username"] ?? "");
    $newEmail    = trim($_POST["email"] ?? "");
    $newPassword = $_POST["password"] ?? "";

    if ($newUsername === "" || $newEmail === "") {
        $error = "Username dan Email tidak boleh kosong.";
    } else {
        // Cek duplikasi username/email selain milik akun ini
        $cek = $connect->prepare("SELECT ID FROM accounts WHERE (username = ? OR email = ?) AND ID != ? LIMIT 1");
        $cek->bind_param("ssi", $newUsername, $newEmail, $userId);
        $cek->execute();
        if ($cek->get_result()->num_rows > 0) {
            $error = "Username atau email sudah digunakan oleh akun lain.";
        } else {
            if (!empty($newPassword)) {
                $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $connect->prepare("UPDATE accounts SET username = ?, email = ?, password = ? WHERE ID = ?");
                $stmt->bind_param("sssi", $newUsername, $newEmail, $hash, $userId);
            } else {
                $stmt = $connect->prepare("UPDATE accounts SET username = ?, email = ? WHERE ID = ?");
                $stmt->bind_param("ssi", $newUsername, $newEmail, $userId);
            }

            if ($stmt->execute()) {
                $_SESSION['username'] = $newUsername;
                $_SESSION['email']    = $newEmail;
                $success = "Data akun berhasil diperbarui!";
            } else {
                $error = "Gagal memperbarui akun.";
            }
        }
    }
}

// Ambil data terbaru dari tabel accounts
$stmt = $connect->prepare("SELECT username, email, role FROM accounts WHERE ID = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$account = $stmt->get_result()->fetch_assoc();

$username = $account['username'] ?? $_SESSION['username'];
$email    = $account['email'] ?? ($_SESSION['email'] ?? '-');
$role     = $account['role'] ?? ($_SESSION['role'] ?? 'Customer');

$isAdmin    = (strtolower($role) === 'admin');
$roleLabel  = $isAdmin ? 'Admin' : 'Customer';
$avatarIcon = $isAdmin ? 'assets/user-icon/admin.png' : 'assets/user-icon/customer.png';
$berandaUrl = $isAdmin ? 'indexAdmin.php' : 'indexCustomer.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Akun - HealthyCare</title>
  <link rel="stylesheet" href="style.css">
  <script src="theme.js"></script>
</head>
<body>

  <!-- Tombol Ganti Tema Terang/Gelap di Pojok Kanan Atas -->
  <div style="position: absolute; top: 20px; right: 24px; z-index: 10;">
    <button type="button" class="theme-btn-top" id="btn-theme" title="Ganti Mode Terang / Gelap" style="width: 38px; height: 38px; border-radius: 50%; border: 1px solid var(--border); background: var(--bg-card); display: flex; align-items: center; justify-content: center; cursor: pointer;">
      <img src="assets/app-icon/sun.png" id="icon-theme" alt="Mode" style="width: 20px; height: 20px; object-fit: contain;">
    </button>
  </div>

  <main class="auth-wrapper">
    <a href="<?= $berandaUrl ?>" class="brand-header">
      <div class="brand-logo">
        <img src="assets/app-icon/appLogo.png" alt="app logo">
      </div>
      <span class="brand-title">HealthyCare</span>
    </a>

    <div class="card-area">
      <div class="auth-card">
        <div class="card-body">
          <div class="profile-header">
            <div class="profile-avatar">
              <img src="<?= $avatarIcon ?>" alt="Avatar <?= $roleLabel ?>">
            </div>
            <h1 class="profile-name"><?= htmlspecialchars($username) ?></h1>
            <span class="role-badge"><?= $roleLabel ?></span>
          </div>

          <?php if ($success !== ""): ?>
            <div style="background: rgba(16, 185, 129, 0.18); border: 1px solid rgba(52, 211, 153, 0.4); color: #34d399; padding: 10px; border-radius: 6px; font-size: 13px; margin-bottom: 14px; text-align: center;">
              <?= htmlspecialchars($success) ?>
            </div>
          <?php endif; ?>

          <?php if ($error !== ""): ?>
            <div style="background: rgba(244, 63, 94, 0.18); border: 1px solid rgba(251, 113, 133, 0.4); color: #fda4af; padding: 10px; border-radius: 6px; font-size: 13px; margin-bottom: 14px; text-align: center;">
              <?= htmlspecialchars($error) ?>
            </div>
          <?php endif; ?>

          <div class="profile-info">
            <div class="info-row">
              <span class="info-label">Username</span>
              <span class="info-value">@<?= htmlspecialchars($username) ?></span>
            </div>
            <div class="info-row">
              <span class="info-label">Email</span>
              <span class="info-value"><?= htmlspecialchars($email) ?></span>
            </div>
            <div class="info-row">
              <span class="info-label">Role</span>
              <span class="info-value"><?= $roleLabel ?></span>
            </div>
            <div class="info-row">
              <span class="info-label">Status Akun</span>
              <span class="info-value" style="color: #34d399;">Aktif</span>
            </div>
          </div>

          <div class="btn-stack">
            <!-- Tombol Utama: Kembali ke Beranda -->
            <a href="<?= $berandaUrl ?>" class="btn-primary">Kembali ke Beranda</a>

            <!-- Tombol Edit Akun dan Logout Bersebelahan -->
            <div style="display: flex; gap: 10px;">
              <a href="#modal-edit-akun" class="btn-outline" style="flex: 1; border-color: rgba(52, 211, 153, 0.45); color: #34d399;">Edit Akun</a>
              <a href="logout.php" class="btn-danger-outline" style="flex: 1;">Logout</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- MODAL EDIT AKUN -->
  <div id="modal-edit-akun" class="modal-overlay">
    <div class="modal-box">
      <div class="modal-header">
        <h3 style="font-size: 18px; color: var(--text-main);">Edit Akun</h3>
        <a href="#" class="modal-close" title="Tutup">&times;</a>
      </div>

      <form action="user.php" method="POST">
        <input type="hidden" name="update_akun" value="1">

        <div class="form-group" style="margin-bottom: 14px;">
          <label style="font-size: 13px; color: var(--text-label); margin-bottom: 6px; display: block;">Username</label>
          <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($username) ?>" required>
        </div>

        <div class="form-group" style="margin-bottom: 14px;">
          <label style="font-size: 13px; color: var(--text-label); margin-bottom: 6px; display: block;">Email</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
          <label style="font-size: 13px; color: var(--text-label); margin-bottom: 6px; display: block;">Password Baru (Opsional)</label>
          <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin ganti">
        </div>

        <div style="display: flex; gap: 10px;">
          <a href="#" class="btn-outline" style="flex: 1; height: 38px;">Batal</a>
          <button type="submit" class="btn-primary" style="flex: 1; height: 38px;">Simpan</button>
        </div>
      </form>
    </div>
  </div>

</body>
</html>