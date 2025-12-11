<?php
include 'config.php';

$msg = "";

if(isset($_POST['login'])){
    $user = trim($_POST['username']);
    $pass = $_POST['password'];

    // 1. KEAMANAN: Gunakan Prepared Statement (Anti SQL Injection)
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($d = mysqli_fetch_assoc($result)){
        
        // 2. CEK PASSWORD: Cek hash modern dulu, kalau gagal coba cek MD5 (Legacy Support)
        $valid = false;
        
        if(password_verify($pass, $d['password'])){
            // Password sudah aman (Hash)
            $valid = true;
        } else if($d['password'] == md5($pass)){
            // Password masih MD5 (Jadul), kita update ke Hash sekarang biar aman
            $new_hash = password_hash($pass, PASSWORD_DEFAULT);
            mysqli_query($koneksi, "UPDATE users SET password='$new_hash' WHERE id='$d[id]'");
            $valid = true;
        }

        // 3. PROSES LOGIN
        if($valid){
            $_SESSION['status'] = "login";
            $_SESSION['user_id'] = $d['id'];
            $_SESSION['nama'] = $d['nama_lengkap'];
            $_SESSION['role'] = $d['role'];

            // Redirect sesuai Role
            if($d['role'] == 'admin'){
                header("Location: dashboard.php");
            } else {
                header("Location: transaksi.php");
            }
            exit;
        } else {
            $msg = "Password Salah!";
        }
    } else {
        $msg = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login - <?= $conf['app_name'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-orange-50 h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden flex flex-col">
        <div class="p-8 w-full">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-orange-100 text-orange-600 mb-3">
                    <i class="fa-solid fa-mug-hot text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">CafePOS Login</h2>
                <p class="text-gray-500 text-sm">Masuk untuk memulai shift kerja.</p>
            </div>
            
            <?php if(!empty($msg)) echo "<p class='bg-red-100 text-red-600 p-3 rounded text-center text-sm mb-4 border border-red-200'>$msg</p>"; ?>
            
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-gray-600 text-xs font-bold mb-1 uppercase">Username</label>
                    <input type="text" name="username" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition" placeholder="Masukkan username" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-600 text-xs font-bold mb-1 uppercase">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition" placeholder="Masukkan password" required>
                </div>
                
                <button type="submit" name="login" class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition shadow-lg shadow-orange-200">
                    MASUK SEKARANG
                </button>
            </form>
            
            <p class="mt-6 text-center text-xs text-gray-400">
                &copy; <?= date('Y') ?> <?= $conf['app_name'] ?> System
            </p>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>