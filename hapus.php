<?php
include "koneksi.php";

$id = $_GET["id"];
$query = mysqli_query($connect, "delete from meds where ID = $id");
header("Location: indexAdmin.php");