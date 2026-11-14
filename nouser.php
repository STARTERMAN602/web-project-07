<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Akses Ditolak - HealthyCare</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <main class="auth-wrapper">
    <a href="login.php" class="brand-header">
      <div class="brand-logo">
        <img src="assets/app-icon/appLogo.png" alt="app logo">
      </div>
      <span class="brand-title">HealthyCare</span>
    </a>

    <div class="card-area">
      <div class="auth-card card-danger">
        <div class="card-body">
          <div class="status-icon danger">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <line x1="15" y1="9" x2="9" y2="15"/>
              <line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
          </div>

          <h1 class="card-title">Akun Tidak Ditemukan</h1>
          <p class="card-desc">
            Username atau password tidak sesuai, atau Anda belum login. Silakan masuk terlebih dahulu untuk mengakses halaman ini.
          </p>

          <div class="btn-stack">
            <a href="login.php" class="btn-primary">Coba Login Lagi</a>
            <a href="regist.php" class="btn-outline">Daftar Akun Baru</a>
          </div>
        </div>
      </div>
    </div>
  </main>

</body>
</html>