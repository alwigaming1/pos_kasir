<?php
// Koneksi Database
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "db_kasir";

$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$koneksi) { die("Gagal Konek Database: " . mysqli_connect_error()); }

// --- BAGIAN BARU: AMBIL PENGATURAN TOKO DARI DB ---
$q_toko = mysqli_query($koneksi, "SELECT * FROM pengaturan WHERE id=1");

// Jika tabel pengaturan belum diisi/kosong (jaga-jaga), pakai default
if(mysqli_num_rows($q_toko) > 0){
    $d_toko = mysqli_fetch_assoc($q_toko);
    $nama_app = $d_toko['nama_toko'];
    $footer_struk = $d_toko['footer_struk'];
} else {
    $nama_app = "CafePOS System";
    $footer_struk = "Terima Kasih";
}

$conf = [
    "app_name"  => $nama_app, 
    "footer"    => $footer_struk,
    "app_ver"   => "V.2.0 Pro",
    "color"     => "orange" 
];

// Setting Waktu & Error
date_default_timezone_set("Asia/Jakarta");
if (session_status() == PHP_SESSION_NONE) { session_start(); }
error_reporting(0); // Matikan error untuk user (Hidupkan saat debugging)

function cek_login(){ 
    if(empty($_SESSION['status'])){ 
        header("location:login.php"); exit; 
    } 
}
?>