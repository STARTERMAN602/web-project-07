<?php
include "koneksi.php";

$id = (int) ($_GET["id"] ?? 0);
try {
    mysqli_query($connect, "DELETE FROM meds WHERE ID = $id");
    header("Location: indexAdmin.php");
    exit;
} catch (mysqli_sql_exception $e) {
    echo "<script>alert('Obat tidak bisa dihapus karena sudah memiliki riwayat transaksi!'); window.location.href='indexAdmin.php';</script>";
}
?>