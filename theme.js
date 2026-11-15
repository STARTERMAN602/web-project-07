// 1. Eksekusi langsung saat halaman mulai dimuat agar tidak ada kedipan warna
(function () {
  const savedTheme = localStorage.getItem('hc_theme');
  if (savedTheme === 'light') {
    document.documentElement.classList.add('light-mode');
    document.body?.classList.add('light-mode');
  }
})();

// 2. Fungsi tombol saat diklik
document.addEventListener('DOMContentLoaded', function () {
  const btnTheme = document.getElementById('btn-theme');
  const iconTheme = document.getElementById('icon-theme');

  function updateIcon(isLight) {
    if (iconTheme) {
      iconTheme.src = isLight ? 'assets/app-icon/moon.png' : 'assets/app-icon/sun.png';
    }
  }

  // Set ikon sesuai tema yang aktif saat ini
  const isCurrentlyLight = document.documentElement.classList.contains('light-mode');
  if (isCurrentlyLight && document.body) {
    document.body.classList.add('light-mode');
  }
  updateIcon(isCurrentlyLight);

  // Event klik ganti tema
  if (btnTheme) {
    btnTheme.addEventListener('click', function () {
      const isLight = document.body.classList.toggle('light-mode');
      document.documentElement.classList.toggle('light-mode', isLight);
      
      // Simpan pilihan ke memori browser (berlaku untuk Admin & Customer)
      localStorage.setItem('hc_theme', isLight ? 'light' : 'dark');
      updateIcon(isLight);
    });
  }
});