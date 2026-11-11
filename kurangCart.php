<?php
session_start();
include("koneksi.php");
$id = $_GET["id"];

$query = mysqli_query($connect, "select * from meds where ID = $id");

$data = $query->fetch_assoc();

if(isset($_SESSION["cart"]["$id"])) {
    if($_SESSION["cart"]["$id"] - 1 == 0) {
        unset($_SESSION["cart"]["$id"]);
    } else {
        $_SESSION["cart"]["$id"] = $_SESSION["cart"]["$id"] - 1;
    }
}

header("location: indexCustomer.php");