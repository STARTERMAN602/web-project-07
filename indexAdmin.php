<?php
include ("koneksi.php");
$query = mysqli_query($connect, "select * from meds");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>index admin</title>
</head>
<body>
    <button> <a href="tambah.php">Tambah Obat </a></button> <br>
    <table border="1" id="medsList">
        <tr>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Tipe</th>
            <th>Stok</th>
            <th>Harga</th>
        </tr>
        <?php
        while($data = $query->fetch_assoc()) {
        ?>
        <tr>
            <td><?=$data["Name"]?></td>
            <td><?=$data["Category"]?></td>
            <td><?=$data["Type"]?></td>
            <td><?=$data["Stock"]?></td>
            <td><?=$data["Price"]?></td>
            <td><button><a href="edit.php?id=<?=$data["ID"]?>">Edit</a></button></td>
            <td><button><a href="hapus.php?id=<?=$data["ID"]?>">Hapus</a></button></td>
        </tr>
        <?php
        }
        ?>
    </table>
</body>
</html>