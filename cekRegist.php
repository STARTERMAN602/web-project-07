<?php
include("koneksi.php");
$username = $_POST["username"];
$password = $_POST["password"];
$confirm = $_POST["confirm_password"];
$email = $_POST["email"];


$query = mysqli_query($connect, "select * from accounts where email = '$email' or username = '$username'");

$count = mysqli_num_rows($query);

if($count > 0) {
    header("location: regist.php?status=email_or_usn_exist");
} else {
    if($password !== $confirm) {
        header("location: regist.php?status=confirm_eror");
        exit();
    } else {
        mysqli_query($connect, "insert into accounts (username, email, password, role) values ('$username', '$email','$password', 'Admin')");
        header("location: login.php");
    }
}