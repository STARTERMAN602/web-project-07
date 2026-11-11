<?php
session_start();
include ("koneksi.php");
$query = mysqli_query($connect, "select * from meds");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index costumer</title>
</head>
<body>
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
            <td><button><a href="tambahCart.php?id=<?=$data["ID"]?>"> + </a></button></td>
            <td><button><a href="kurangCart.php?id=<?=$data["ID"]?>"> - </a></button></td>
        </tr>
    
        <?php
        }
        ?>
    </table>
    <?php
    if(isset($_SESSION["cart"])) {
        ?>
        <table>
        <?php
            foreach($_SESSION["cart"] as $id => $qty) {
                $query2 = mysqli_query($connect,"select * from meds where ID = $id");
                $obat = $query2->fetch_assoc();
                $total = $qty * $obat["Price"];
                ?>
                <tr>
                    <td><?=$obat["Name"]?></td>
                    <td><?=$obat["Price"]?></td>
                    <td><?=$qty?></td>
                    <td><?=$total?></td>
                </tr>
                <?php
            }
        ?>
        </table>
        <?php
    }
    ?>


</body>
</html>