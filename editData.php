<?php
include("koneksi.php");
$id = $_POST["id"];
$nama = $_POST["nama"];
$kategori = $_POST["kategori"];
$tipe = $_POST["tipe"];
$stok = $_POST["stok"];
$harga = $_POST["harga"];

$query = mysqli_query($connect, "update meds set Name = '$nama', Category = '$kategori', Type = '$tipe', Stock = $stok, Price = $harga where ID = $id");

header("Location: indexAdmin.php");