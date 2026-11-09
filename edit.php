<?php
include "koneksi.php";
$id = $_GET["id"];

$query = mysqli_query($connect, "Select * From meds where ID = $id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
</head>
<body>
    <?php
    while($data = $query->fetch_assoc()){
    ?>
    <form action="editData.php" method="POST">
        <input type="number" name="id" value="<?=$data["ID"]?>" hidden>
        Nama:
        <input type="text" name="nama" value="<?=$data["Name"]?>">
        <br>
        Kategori: <br>
        <div class="pilihKategori">
            <input type="radio" name="kategori" value="Paracetamol" <?=($data['Category'] == 'Paracetamol') ? 'checked' : '' ?>> Paracetamol 
            <input type="radio" name="kategori" value="Ibuprofen" <?=($data['Category'] == 'Ibuprofen') ? 'checked' : '' ?>> Ibuprofen 
            <input type="radio" name="kategori" value="Vitamin" <?=($data['Category'] == 'Vitamin') ? 'checked' : '' ?>> Vitamin
            <input type="radio" name="kategori" value="Obat Maag" <?=($data['Category'] == 'Obat Maag') ? 'checked' : '' ?>> Obat Maag
        </div>
        <br>
        Tipe: <br>
        <div class="pilihTipe">
            <input type="radio" name="tipe" value="Tablet" <?=($data['Type'] == 'Tablet') ? 'checked' : '' ?>> Tablet
            <input type="radio" name="tipe" value="Kapsul" <?=($data['Type'] == 'Kapsul') ? 'checked' : '' ?>> Kapsul
            <input type="radio" name="tipe" value="Sirup" <?=($data['Type'] == 'Sirup') ? 'checked' : '' ?>> Sirup
        </div>
        <br>
        Stok:
        <input type="number" name="stok" value="<?=$data["Stock"]?>">
        <br>
        Harga:
        <input type="number" name="harga" value="<?=$data["Price"]?>">
        <br>

        <button type="submit"> Edit Obat</button>
    </form>
    <?php
    }
    ?>
</body>
</html>