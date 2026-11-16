<?php
include("koneksi.php");

$nama     = $_POST["nama"];
$kategori = $_POST["kategori"];
$tipe     = $_POST["tipe"];
$stok     = $_POST["stok"];
$harga    = $_POST["harga"];

$gambar = null;
if (!empty($_FILES["image"]["name"])) {
    $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $allowed = ["jpg", "jpeg", "png", "webp"];

    if (in_array($ext, $allowed)) {
        $gambar = time() . "_" . uniqid() . "." . $ext;
        move_uploaded_file($_FILES["image"]["tmp_name"], "assets/product-image/" . $gambar);
    }
}

$query = mysqli_query($connect, "insert into meds (Name, Category, Type, Price, Stock, Image, isDeleted) values ('$nama', '$kategori', '$tipe', $harga, $stok, '$gambar', false)");
header("Location: indexAdmin.php");
exit;
?>