<?php
session_start();
if(!isset($_SESSION["status"])) {
    header("location: login.php?status=not_logedin");
}
$userID = $_SESSION["userID"];
include ("koneksi.php");
$query = mysqli_query($connect, "select * from meds");
$user = mysqli_query($connect, "select * from accounts where ID = $userID") ->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index admin</title>
</head>
<body>
    halo <?=$user["username"]?> <br>
    <button><a style="text-decoration: none; color: inherit;" href="logout.php">Logout</a></button>
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