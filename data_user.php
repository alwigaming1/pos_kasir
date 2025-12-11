<?php include 'config.php'; cek_login(); if($_SESSION['role']!='admin'){header("Location:transaksi.php");} 

// Logic Simpan & Hapus (Tetap)
if(isset($_POST['simpan'])){
    $nama = $_POST['nama']; $user = $_POST['user']; $role = $_POST['role'];
    $pass_query = "";
    if(!empty($_POST['pass'])){ $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT); $pass_query = ", password='$pass'"; }

    if($_POST['id'] != ""){
        mysqli_query($koneksi, "UPDATE users SET nama_lengkap='$nama', username='$user', role='$role' $pass_query WHERE id='$_POST[id]'");
    } else {
        if(empty($_POST['pass'])){ $pass=password_hash("12345", PASSWORD_DEFAULT); } 
        else { $pass=password_hash($_POST['pass'], PASSWORD_DEFAULT); }
        mysqli_query($koneksi, "INSERT INTO users VALUES (NULL, '$nama', '$user', '$pass', '$role')");
    }
    header("Location: data_user.php");
}
if(isset($_GET['hapus'])){ mysqli_query($koneksi, "DELETE FROM users WHERE id='$_GET[hapus]'"); header("Location: data_user.php"); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Manajemen User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800">
    
    <?php include 'sidebar.php'; ?>
    
    <div class="md:ml-64 p-4 md:p-8 pt-20 md:pt-8 transition-all duration-300">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Manajemen Staff</h1>
                <p class="text-slate-500 text-sm">Atur akun kasir dan admin.</p>
            </div>
            <button onclick="bukaModal()" class="w-full md:w-auto bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-900/20 hover:bg-blue-700 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-user-plus"></i> Tambah User
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-blue-50 text-blue-700 text-xs uppercase font-bold">
                        <tr><th class="p-4">Nama Lengkap</th><th class="p-4">Username</th><th class="p-4">Role</th><th class="p-4 text-center">Aksi</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <?php $q=mysqli_query($koneksi, "SELECT * FROM users"); while($r=mysqli_fetch_array($q)){ ?>
                        <tr class="hover:bg-blue-50/30 transition">
                            <td class="p-4 font-bold text-slate-700"><?= $r['nama_lengkap'] ?></td>
                            <td class="p-4 text-slate-500 font-mono"><?= $r['username'] ?></td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider <?= $r['role']=='admin'?'bg-purple-100 text-purple-700':'bg-green-100 text-green-700' ?>">
                                    <?= $r['role'] ?>
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <button onclick="editUser('<?= $r['id'] ?>','<?= $r['nama_lengkap'] ?>','<?= $r['username'] ?>','<?= $r['role'] ?>')" class="text-blue-500 hover:text-blue-700 mx-1 p-2 hover:bg-blue-50 rounded-lg"><i class="fa-solid fa-pen-to-square"></i></button>
                                <?php if($r['username'] != 'admin'): ?>
                                <a href="data_user.php?hapus=<?= $r['id'] ?>" onclick="return confirm('Hapus user ini?')" class="text-red-500 hover:text-red-700 mx-1 p-2 hover:bg-red-50 rounded-lg"><i class="fa-solid fa-trash"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalUser" class="fixed inset-0 bg-black/60 hidden flex items-center justify-center p-4 z-[60] backdrop-blur-sm">
        <div class="bg-white p-6 md:p-8 rounded-2xl w-full max-w-md shadow-2xl relative">
            <button onclick="document.getElementById('modalUser').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-800"><i class="fa-solid fa-xmark text-xl"></i></button>
            <h3 class="font-bold text-xl mb-6 text-slate-800" id="modalTitle">Form User</h3>
            
            <form method="POST" class="space-y-4">
                <input type="hidden" name="id" id="id_user">
                <div><label class="text-xs font-bold text-slate-500 uppercase">Nama Lengkap</label><input type="text" name="nama" id="nama" class="w-full border border-slate-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none mt-1" required></div>
                <div><label class="text-xs font-bold text-slate-500 uppercase">Username</label><input type="text" name="user" id="user" class="w-full border border-slate-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none mt-1" required></div>
                <div><label class="text-xs font-bold text-slate-500 uppercase">Password</label><input type="password" name="pass" class="w-full border border-slate-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none mt-1" placeholder="Kosongkan jika tidak diganti"></div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Role</label>
                    <select name="role" id="role" class="w-full border border-slate-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none mt-1">
                        <option value="kasir">Kasir</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 mt-2">
                    <button type="button" onclick="document.getElementById('modalUser').classList.add('hidden')" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition">Batal</button>
                    <button type="submit" name="simpan" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function bukaModal(){
            document.getElementById('modalUser').classList.remove('hidden');
            document.getElementById('modalTitle').innerText = "Tambah User Baru";
            document.getElementById('id_user').value = ""; document.getElementById('nama').value = ""; document.getElementById('user').value = "";
        }
        function editUser(id, nama, user, role){
            document.getElementById('modalUser').classList.remove('hidden');
            document.getElementById('modalTitle').innerText = "Edit Data User";
            document.getElementById('id_user').value = id; document.getElementById('nama').value = nama; document.getElementById('user').value = user; document.getElementById('role').value = role;
        }
    </script>
</body>
</html>