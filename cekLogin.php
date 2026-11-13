<?php
session_start();
include("koneksi.php");
$username = $_POST["username"];
$password = $_POST["password"];

$query = mysqli_query($connect, "select * from accounts where (username = '$username' or email = '$username') and password = '$password'");

$count = mysqli_num_rows($query);

if($count == 0) {
    header("location: login.php?status=wrong");
} else {
    $data = $query->fetch_assoc();
    $_SESSION["userID"] = $data["ID"];
    $_SESSION["status"] = "login";
    header("location: indexAdmin.php");
}