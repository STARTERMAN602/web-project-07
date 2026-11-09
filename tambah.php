<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Obat</title>
</head>
<body>
    <form action="tambahData.php" method="POST">
        Nama:
        <input type="text" name="nama">
        <br>
        Kategori: <br>
        <div class="pilihKategori">
            <input type="radio" name="kategori" value="Paracetamol"> Paracetamol 
            <input type="radio" name="kategori" value="Ibuprofen"> Ibuprofen 
            <input type="radio" name="kategori" value="Vitamin"> Vitamin 
            <input type="radio" name="kategori" value="Obat Maag"> Obat Maag 
        </div>
        <br>
        Tipe: <br>
        <div class="pilihTipe">
            <input type="radio" name="tipe" value="Tablet"> Tablet 
            <input type="radio" name="tipe" value="Kapsul"> Kapsul 
            <input type="radio" name="tipe" value="Sirup"> Sirup 
        </div>
        <br>
        Stok:
        <input type="number" name="stok">
        <br>
        Harga:
        <input type="number" name="harga">
        <br>

        <button type="submit">Tambahkan Obat</button>
    </form>

</body>
</html>