<?php include 'config.php'; cek_login(); if($_SESSION['role']!='admin'){header("Location:transaksi.php");} 

// TAMBAH / EDIT USER
if(isset($_POST['simpan'])){
    $nama = $_POST['nama'];
    $user = $_POST['user'];
    $role = $_POST['role'];
    
    // Jika password diisi, enkripsi MD5. Jika kosong, pakai pass lama (khusus edit).
    $pass_query = "";
    if(!empty($_POST['pass'])){
        $pass = md5($_POST['pass']);
        $pass_query = ", password='$pass'";
    }

    if($_POST['id'] != ""){
        // Edit
        $q = "UPDATE users SET nama_lengkap='$nama', username='$user', role='$role' $pass_query WHERE id='$_POST[id]'";
    } else {
        // Tambah (Wajib ada password)
        if(empty($_POST['pass'])){ $pass=md5("12345"); } // Default pass
        else { $pass=md5($_POST['pass']); }
        
        $q = "INSERT INTO users VALUES (NULL, '$nama', '$user', '$pass', '$role')";
    }
    mysqli_query($koneksi, $q);
    header("Location: data_user.php");
}

// HAPUS USER
if(isset($_GET['hapus'])){
    mysqli_query($koneksi, "DELETE FROM users WHERE id='$_GET[hapus]'");
    header("Location: data_user.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Data User</title><script src="https://cdn.tailwindcss.com"></script><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head>
<body class="bg-gray-100 text-slate-800">
    <?php include 'sidebar.php'; ?>
    <div class="md:ml-64 p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Manajemen Staff</h1>
            <button onclick="bukaModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold shadow hover:bg-blue-700"><i class="fa-solid fa-user-plus"></i> Tambah User</button>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-blue-50 text-blue-700 text-sm uppercase">
                    <tr><th class="p-4">Nama Lengkap</th><th class="p-4">Username</th><th class="p-4">Role</th><th class="p-4 text-center">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    <?php $q=mysqli_query($koneksi, "SELECT * FROM users"); while($r=mysqli_fetch_array($q)){ ?>
                    <tr class="hover:bg-gray-50">
                        <td class="p-4 font-bold"><?= $r['nama_lengkap'] ?></td>
                        <td class="p-4"><?= $r['username'] ?></td>
                        <td class="p-4"><span class="px-2 py-1 rounded text-xs uppercase font-bold <?= $r['role']=='admin'?'bg-purple-100 text-purple-600':'bg-green-100 text-green-600' ?>"><?= $r['role'] ?></span></td>
                        <td class="p-4 text-center">
                            <button onclick="editUser('<?= $r['id'] ?>','<?= $r['nama_lengkap'] ?>','<?= $r['username'] ?>','<?= $r['role'] ?>')" class="text-blue-500 hover:text-blue-700 mx-1"><i class="fa-solid fa-edit"></i></button>
                            <?php if($r['username'] != 'admin'): ?>
                            <a href="data_user.php?hapus=<?= $r['id'] ?>" onclick="return confirm('Hapus user ini?')" class="text-red-500 hover:text-red-700 mx-1"><i class="fa-solid fa-trash"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalUser" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-md shadow-2xl">
            <h3 class="font-bold text-lg mb-4 text-gray-800" id="modalTitle">Tambah User</h3>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="id" id="id_user">
                <div><label class="text-xs font-bold text-gray-500">Nama Lengkap</label><input type="text" name="nama" id="nama" class="w-full border p-2 rounded" required></div>
                <div><label class="text-xs font-bold text-gray-500">Username</label><input type="text" name="user" id="user" class="w-full border p-2 rounded" required></div>
                <div><label class="text-xs font-bold text-gray-500">Password</label><input type="password" name="pass" class="w-full border p-2 rounded" placeholder="Kosongkan jika tidak diganti"></div>
                <div>
                    <label class="text-xs font-bold text-gray-500">Role</label>
                    <select name="role" id="role" class="w-full border p-2 rounded">
                        <option value="kasir">Kasir</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('modalUser').classList.add('hidden')" class="bg-gray-200 px-4 py-2 rounded font-bold text-gray-600">Batal</button>
                    <button type="submit" name="simpan" class="bg-blue-600 text-white px-4 py-2 rounded font-bold hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function bukaModal(){
            document.getElementById('modalUser').classList.remove('hidden');
            document.getElementById('modalTitle').innerText = "Tambah User";
            document.getElementById('id_user').value = "";
            document.getElementById('nama').value = "";
            document.getElementById('user').value = "";
        }
        function editUser(id, nama, user, role){
            document.getElementById('modalUser').classList.remove('hidden');
            document.getElementById('modalTitle').innerText = "Edit User";
            document.getElementById('id_user').value = id;
            document.getElementById('nama').value = nama;
            document.getElementById('user').value = user;
            document.getElementById('role').value = role;
        }
    </script>
</body>
</html>