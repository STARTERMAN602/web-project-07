<?php
session_start();
include("koneksi.php");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $loginInput = trim($_POST["username"] ?? "");
    $password   = $_POST["password"] ?? "";
    $confirm    = $_POST["confirm_password"] ?? "";

    if ($loginInput === "" || $password === "" || $confirm === "") {
        $error = "Semua kolom wajib diisi.";
    } elseif ($password !== $confirm) {
        $error = "Password baru dan konfirmasi password tidak cocok.";
    } else {
        // Cek apakah username atau email terdaftar di database
        $stmt = $connect->prepare("SELECT ID FROM accounts WHERE username = ? OR email = ? LIMIT 1");
        $stmt->bind_param("ss", $loginInput, $loginInput);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Hash password baru dan update ke database
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $update = $connect->prepare("UPDATE accounts SET password = ? WHERE username = ? OR email = ?");
            $update->bind_param("sss", $hashed, $loginInput, $loginInput);

            if ($update->execute()) {
                $success = "Password berhasil diubah! Silakan <a href='login.php' class='link-accent'>Login di sini</a>.";
            } else {
                $error = "Gagal memperbarui password. Coba lagi.";
            }
        } else {
            $error = "Username atau email tidak ditemukan di database.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - HealthyCare</title>
  <link rel="stylesheet" href="style.css">
  <script src="theme.js"></script>
</head>
<body>
  <!-- Tombol Ganti Tema Terang / Gelap -->
  <div style="position: absolute; top: 20px; right: 24px; z-index: 10;">
    <button type="button" class="theme-btn-top" id="btn-theme" title="Ganti Mode Terang / Gelap" style="width: 38px; height: 38px; border-radius: 50%; border: 1px solid var(--border); background: var(--bg-card); display: flex; align-items: center; justify-content: center; cursor: pointer;">
      <img src="assets/app-icon/sun.png" id="icon-theme" alt="Mode" style="width: 20px; height: 20px; object-fit: contain;">
    </button>
  </div>

  <main class="auth-wrapper">
    <a href="login.php" class="brand-header">
      <div class="brand-logo">
        <img src="assets/app-icon/appLogo.png" alt="app logo">
      </div>
      <span class="brand-title">HealthyCare</span>
    </a>

    <div class="card-area">
      <div class="bg-illustration"></div>

      <div class="auth-card">
        <div class="card-body">
          <h1 class="card-title">Reset Password</h1>

          <?php if ($error !== ""): ?>
            <div style="background: rgba(244, 63, 94, 0.18); border: 1px solid rgba(251, 113, 133, 0.4); color: #fda4af; padding: 10px 12px; border-radius: 6px; font-size: 13px; margin-bottom: 16px;">
              <?= htmlspecialchars($error) ?>
            </div>
          <?php endif; ?>

          <?php if ($success !== ""): ?>
            <div style="background: rgba(16, 185, 129, 0.18); border: 1px solid rgba(52, 211, 153, 0.4); color: #34d399; padding: 12px; border-radius: 6px; font-size: 13.5px; margin-bottom: 16px; text-align: center;">
              <?= $success ?>
            </div>
          <?php else: ?>

            <form action="" method="POST">
              <div class="form-group">
                <label for="username">Username or email</label>
                <input type="text" id="username" name="username" class="form-control" required autofocus>
              </div>

              <div class="form-group">
                <label for="password">New Password</label>
                <div class="password-wrapper">
                  <input type="password" id="password" name="password" class="form-control" required>
                  <button type="button" class="toggle-password" aria-label="Tampilkan password">
                    <svg class="icon-eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                      <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg class="icon-eye-off" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                      <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                      <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                      <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                  </button>
                </div>
              </div>

              <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <div class="password-wrapper">
                  <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                  <button type="button" class="toggle-password" aria-label="Tampilkan password">
                    <svg class="icon-eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                      <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg class="icon-eye-off" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                      <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                      <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                      <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                  </button>
                </div>
              </div>

              <div class="back-link-wrap" style="margin-top: 10px;">
                <a href="login.php" class="link-accent">&laquo; Back to Login</a>
              </div>

              <button type="submit" class="btn-primary" style="margin-top: 6px;">Reset Password</button>
            </form>

          <?php endif; ?>
        </div>
      </div>
    </div>
  </main>

  <script>
    document.querySelectorAll('.toggle-password').forEach(btn => {
      btn.addEventListener('click', () => {
        const input  = btn.previousElementSibling;
        const eye    = btn.querySelector('.icon-eye');
        const eyeOff = btn.querySelector('.icon-eye-off');
        const hidden = input.type === 'password';

        input.type           = hidden ? 'text' : 'password';
        eye.style.display    = hidden ? 'none' : 'block';
        eyeOff.style.display = hidden ? 'block' : 'none';
      });
    });
  </script>
</body>
</html>