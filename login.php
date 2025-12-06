<?php
include 'config.php';

if(isset($_POST['login'])){
    $user = $_POST['username'];
    $pass = md5($_POST['password']);

    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$user' AND password='$pass'");
    if(mysqli_num_rows($cek) > 0){
        $d = mysqli_fetch_array($cek);
        
        // Simpan Data Penting ke Session
        $_SESSION['status'] = "login";
        $_SESSION['user_id'] = $d['id'];
        $_SESSION['nama'] = $d['nama_lengkap'];
        $_SESSION['role'] = $d['role']; // PENTING: Simpan role (admin/kasir)

        // LOGIKA PEMISAH
        if($d['role'] == 'admin'){
            header("Location: dashboard.php"); // Admin ke Dashboard
        } else {
            header("Location: transaksi.php"); // Kasir langsung ke Mesin Kasir
        }
    } else {
        $msg = "Username atau Password Salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login - <?= $conf['app_name'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-orange-50 h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden flex flex-col md:flex-row">
        <div class="p-8 w-full">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-orange-100 text-orange-600 mb-3">
                    <i class="fa-solid fa-mug-hot text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">CafePOS Login</h2>
                <p class="text-gray-500 text-sm">Masuk untuk memulai shift kerja.</p>
            </div>
            
            <?php if(isset($msg)) echo "<p class='bg-red-100 text-red-600 p-3 rounded text-center text-sm mb-4'>$msg</p>"; ?>
            
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-gray-600 text-xs font-bold mb-1 uppercase">Username</label>
                    <input type="text" name="username" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-600 text-xs font-bold mb-1 uppercase">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition" required>
                </div>
                
                <button type="submit" name="login" class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition shadow-lg shadow-orange-200">
                    MASUK
                </button>
            </form>
            
            <div class="mt-6 text-center text-xs text-gray-400">
                <p>Admin: admin | admin</p>
                <p>Kasir: kasir | kasir</p>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>