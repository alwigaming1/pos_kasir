<?php
$conf = [
    "app_name"  => "CafePOS System", 
    "app_ver"   => "V.1.0",
    "author"    => "SkripsiCode",
    "color"     => "orange", // TEMA ORANGE (Cocok untuk F&B)
    "db_host"   => "localhost",
    "db_user"   => "root",
    "db_pass"   => "",
    "db_name"   => "db_kasir"
];
$koneksi = mysqli_connect($conf['db_host'], $conf['db_user'], $conf['db_pass'], $conf['db_name']);
if (!$koneksi) { die("Gagal Konek Database: " . mysqli_connect_error()); }
if (session_status() == PHP_SESSION_NONE) { session_start(); }
function cek_login(){ if(empty($_SESSION['status'])){ header("location:login.php"); exit; } }
?>