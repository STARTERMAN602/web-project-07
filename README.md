# 💊 HealthyCare - Web Apotek Digital

**HealthyCare** adalah aplikasi berbasis web untuk manajemen apotek digital yang dirancang dengan antarmuka modern (*Dark & Light Mode*). Aplikasi ini mendukung dua peran pengguna (Multi-Role), yaitu **Admin** dan **Customer**, lengkap dengan fitur keranjang belanja, *checkout*, nota *invoice*, manajemen stok obat (CRUD), hingga ekspor laporan ke Excel.

---

## ✨ Fitur Utama

### 🔐 1. Autentikasi & Akun
- **Register & Login:** Pendaftaran akun dengan pilihan role (Admin atau Customer) dan enkripsi *password* yang aman (`password_hash`).
- **Show/Hide Password:** Tombol lihat/sembunyikan password pada kolom input.
- **Profil & Edit Akun:** Halaman profil interaktif dengan ikon khusus sesuai role, dilengkapi fitur *Edit Akun* (username, email, dan password).
- **Dark & Light Mode:** Tombol pengubah tema terang/gelap yang otomatis tersimpan di memori browser (*LocalStorage*) di seluruh halaman.

### 👨‍💼 2. Dashboard Admin
- **Kelola Data Obat (CRUD):** Tambah, lihat, edit, dan hapus data obat lengkap dengan unggah gambar produk.
- **Peringatan Stok Menipis:** Indikator badge otomatis berubah warna jika stok obat tersisa $\le 5$.
- **Histori Pembelian Global:** Memantau seluruh transaksi dari semua pembeli, lengkap dengan fitur *sorting* (A-Z, tanggal) dan *filter* harga.
- **Ekspor Laporan Excel:** Mengunduh rekap data stok obat dan laporan transaksi ke format `.xls`.

### 🛒 3. Belanja & Customer
- **Katalog Obat Interaktif:** Menampilkan daftar obat dengan detail lengkap (kategori, tipe, harga, dan sisa stok).
- **Modal Detail Obat & Keranjang:** Menambah obat ke keranjang dengan pengaturan jumlah (*quantity*) yang dibatasi oleh sisa stok asli database.
- **Checkout & Transaksi Berhasil:** Proses *checkout* otomatis memotong stok obat di database dan mencatat transaksi ke tabel `purchase` & `purchasedetail`.
- **Nota Invoice & Cetak:** Menampilkan rincian struk pembelian dan tombol cetak nota.
- **Histori Pembelian Pribadi:** Customer dapat melihat riwayat belanja mereka sendiri dan mencetak ulang invoice kapan saja.

---

## 🛠️ Teknologi yang Digunakan
- **Frontend:** HTML5, Custom CSS3 (Modern Glassmorphism UI), JavaScript
- **Backend:** PHP (Native)
- **Database:** MySQL / MariaDB (`healthycare_db`)
- **Server Lokal:** Laragon / XAMPP

---

## 🚀 Cara Menjalankan Project

1. **Clone / Download** repository ini ke komputer kamu.
2. Pindahkan folder project ke direktori server lokal (Contoh: `C:\laragon\www\` atau `C:\xampp\htdocs\`).
3. Nyalakan **Apache** dan **MySQL** melalui Laragon / XAMPP Control Panel.
4. Buka **phpMyAdmin**, buat database baru bernama `healthycare_db`, lalu *import* file `healthycare_db.sql` yang ada di dalam repository ini.
5. Sesuaikan konfigurasi database jika diperlukan pada file `koneksi.php`.
6. Buka browser dan akses:
   ```text
   http://localhost/web-project-07/
   ```
