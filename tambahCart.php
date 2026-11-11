<?php
session_start();
include("koneksi.php");
$id = $_GET["id"];

$query = mysqli_query($connect, "select * from meds where ID = $id");

$data = $query->fetch_assoc();

if(!isset($_SESSION["cart"])){
    $_SESSION["cart"] = [];
}

$curQty = $_SESSION["cart"]["$id"] ?? 0;

if($curQty + 1 < $data["Stock"]) {
    $_SESSION["cart"]["$id"] = $curQty + 1;
}

header("location: indexCustomer.php");