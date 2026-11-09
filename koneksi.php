<?php
$connect = new mysqli("localhost", "root", "", "healthycare_db");

if($connect->connect_error) {
    die("Connection error: ". $connect->connect_error);
}