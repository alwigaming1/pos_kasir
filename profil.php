<?php
include 'config.php';
cek_login();

// Proses Ganti Password
if(isset($_POST['ganti_pass'])){
    // Validasi input
    if($_POST['pass_baru'] != $_POST['pass_konf']){
        echo "<script>alert('Konfirmasi password tidak cocok!');</script>";
    } else {
        $id_user = $_SESSION['user_id']; // Ambil ID dari session login
        $pass_hash = password_hash($_POST['pass_baru'], PASSWORD_DEFAULT); // Enkripsi Aman
        
        $update = mysqli_query($koneksi, "UPDATE users SET password='$pass_hash' WHERE id='$id_user'");
        
        if($update){
            echo "<script>alert('Password berhasil diubah! Silakan login ulang.'); window.location='logout.php';</script>";
        } else {
            echo "<script>alert('Gagal merubah password.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Profil Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">

    <?php include 'sidebar.php'; ?>

    <div class="ml-64 p-8">
        <h1 class="text-2xl font-bold mb-6">Pengaturan Akun</h1>

        <div class="bg-white p-8 rounded-xl shadow-sm max-w-lg">
            <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Ganti Password</h3>
            
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-sm mb-1">Password Baru</label>
                    <input type="password" name="pass_baru" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm mb-1">Konfirmasi Password</label>
                    <input type="password" name="pass_konf" class="w-full border rounded p-2" required>
                </div>
                <button type="submit" name="ganti_pass" class="bg-<?= $conf['color'] ?>-600 text-white px-6 py-2 rounded hover:bg-<?= $conf['color'] ?>-700 transition">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

</body>
</html>