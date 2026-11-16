<?php
include "koneksi.php";

$id = $_GET["id"];
$query = mysqli_query($connect, "update meds set isDeleted = 1 where ID = $id");
header("Location: indexAdmin.php");