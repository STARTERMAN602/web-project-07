<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Akun - HealthyCare</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <main class="auth-wrapper">
    <a href="indexCustomer.php" class="brand-header">
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
              <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
            </div>
            <h1 class="profile-name">Nama Pengguna</h1>
            <span class="role-badge">Customer</span>
          </div>

          <div class="profile-info">
            <div class="info-row">
              <span class="info-label">Username</span>
              <span class="info-value">@username</span>
            </div>
            <div class="info-row">
              <span class="info-label">Email</span>
              <span class="info-value">user@email.com</span>
            </div>
            <div class="info-row">
              <span class="info-label">Role</span>
              <span class="info-value">Customer</span>
            </div>
            <div class="info-row">
              <span class="info-label">Status Akun</span>
              <span class="info-value" style="color: #34d399;">Aktif</span>
            </div>
          </div>

          <div class="btn-stack">
            <a href="indexCustomer.php" class="btn-primary">Kembali ke Beranda</a>
            <a href="logout.php" class="btn-danger-outline">Logout</a>
          </div>
        </div>
      </div>
    </div>
  </main>

</body>
</html>