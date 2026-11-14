<?php
include("koneksi.php");

$id       = (int) $_POST["id"];
$nama     = $_POST["nama"];
$kategori = $_POST["kategori"];
$tipe     = $_POST["tipe"];
$stok     = (int) $_POST["stok"];
$harga    = (int) $_POST["harga"];
$imageName = !empty($_POST["old_image"]) ? $_POST["old_image"] : null;

// Jika admin upload gambar baru
if (!empty($_FILES["image"]["name"])) {
    $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $allowed = ["jpg", "jpeg", "png", "webp"];

    if (in_array($ext, $allowed)) {
        $imageName = time() . "_" . uniqid() . "." . $ext;
        move_uploaded_file($_FILES["image"]["tmp_name"], "assets/product-image/" . $imageName);
    }
}

$stmt = $connect->prepare("UPDATE meds SET Name = ?, Category = ?, Type = ?, Price = ?, Stock = ?, Image = ? WHERE ID = ?");
$stmt->bind_param("sssiisi", $nama, $kategori, $tipe, $harga, $stok, $imageName, $id);
$stmt->execute();

header("Location: indexAdmin.php");
exit;
?>