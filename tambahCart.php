<?php
session_start();
include("koneksi.php");

$id  = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
// Jika ada input qty (dari modal), pakai angka itu. Jika tidak (dari tombol + di keranjang), default tambah 1
$qty = isset($_REQUEST['qty']) ? (int)$_REQUEST['qty'] : 1;

if ($id > 0 && $qty > 0) {
    // Cek sisa stok obat di database agar tidak melebihi stok
    $res = mysqli_query($connect, "SELECT Stock FROM meds WHERE ID = $id LIMIT 1");
    if ($med = mysqli_fetch_assoc($res)) {
        $stokTersedia = (int)$med['Stock'];
        $qtySekarang  = (int)($_SESSION['cart'][$id] ?? 0);
        $qtyBaru      = $qtySekarang + $qty;

        // Batasi agar tidak melebihi stok maksimal
        if ($qtyBaru > $stokTersedia) {
            $qtyBaru = $stokTersedia;
        }

        if ($qtyBaru > 0) {
            $_SESSION['cart'][$id] = $qtyBaru;
        }
    }
}

// Arahkan langsung ke keranjang setelah berhasil menambah
header("Location: cart.php");
exit;
?>