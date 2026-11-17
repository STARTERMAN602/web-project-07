# HealthyCare - Web Apotek Digital

**HealthyCare** adalah aplikasi berbasis web untuk manajemen apotek digital yang dirancang dengan antarmuka modern (*Dark & Light Mode*). Aplikasi ini mendukung dua peran pengguna (Multi-Role), yaitu **Admin** dan **Customer**, lengkap dengan fitur keranjang belanja, *checkout*, manajemen stok obat (CRUD), hingga ekspor laporan ke Excel.

---

## Cara Menjalankan Project

1. **Clone / Download** repository ini ke komputer kamu.
2. Pindahkan folder project ke direktori server lokal (Contoh: `C:\laragon\www\` atau `C:\xampp\htdocs\`).
3. Nyalakan **Apache** dan **MySQL** melalui Laragon / XAMPP Control Panel.
4. Buka **phpMyAdmin**, buat database baru bernama `healthycare_db`, lalu *import* file `healthycare_db.sql` yang ada di dalam repository ini.
5. Sesuaikan konfigurasi database jika diperlukan pada file `koneksi.php`.
6. Buka browser dan akses:
   ```text
   http://localhost/web-project-07/login.php
   ```
