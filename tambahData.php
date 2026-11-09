<?php
include("koneksi.php");

$nama = $_POST["nama"];
$kategori = $_POST["kategori"];
$tipe = $_POST["tipe"];
$stok = $_POST["stok"];
$harga = $_POST["harga"];

$query = mysqli_query($connect, "INSERT INTO meds (Name, Category, Type, Price, Stock) values ('$nama', '$kategori', '$tipe', $harga, $stok)");

header("Location: indexAdmin.php");