<?php
include("koneksi.php");

$nama     = $_POST["nama"];
$kategori = $_POST["kategori"];
$tipe     = $_POST["tipe"];
$stok     = (int) $_POST["stok"];
$harga    = (int) $_POST["harga"];

// Proses upload gambar (jika admin memilih file gambar)
$imageName = null;
if (!empty($_FILES["image"]["name"])) {
    $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $allowed = ["jpg", "jpeg", "png", "webp"];

    if (in_array($ext, $allowed)) {
        $imageName = time() . "_" . uniqid() . "." . $ext;
        move_uploaded_file($_FILES["image"]["tmp_name"], "assets/product-image/" . $imageName);
    }
}

$stmt = $connect->prepare("INSERT INTO meds (Name, Category, Type, Price, Stock, Image) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssiis", $nama, $kategori, $tipe, $harga, $stok, $imageName);
$stmt->execute();

header("Location: indexAdmin.php");
exit;
?>