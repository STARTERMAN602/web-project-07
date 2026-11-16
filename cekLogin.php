<?php
session_start();
include("koneksi.php");
$username = trim($_POST["username"]);
$password = $_POST["password"];

$query = mysqli_query($connect, "select * from accounts where (username = '$username' or email = '$username') and password = '$password'");

$count = mysqli_num_rows($query);

if($count == 0) {
    header("location: nouser.php");
} else {
    $data = $query->fetch_assoc();
    $_SESSION["userID"] = $data["ID"];
    $_SESSION["username"] = $data["username"];
    $_SESSION["email"] = $data["email"];
    $_SESSION["role"] = $data["role"];
    if($data["role"] == "Admin") {
        header("location: indexAdmin.php");
    } else {
        header("location: indexCustomer.php");
    }
}