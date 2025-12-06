<?php include 'config.php'; cek_login(); if($_SESSION['role']!='admin'){header("Location:transaksi.php");} 

// PROSES TAMBAH / EDIT
if(isset($_POST['simpan'])){
    $kode = $_POST['kode']; $nama = $_POST['nama']; $kategori = $_POST['kategori'];
    $harga = $_POST['harga']; $stok = $_POST['stok'];
    
    // Upload Gambar
    if(!empty($_FILES['foto']['name'])){
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $img = "menu_".time().".".$ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], "assets/".$img);
    } else { 
        $img = $_POST['gambar_lama']; 
    }

    if($_POST['id'] != ""){
        // Query UPDATE
        mysqli_query($koneksi, "UPDATE produk SET kode_produk='$kode', nama_produk='$nama', kategori='$kategori', harga='$harga', stok='$stok', gambar='$img' WHERE id='$_POST[id]'");
    } else {
        // Query INSERT
        mysqli_query($koneksi, "INSERT INTO produk VALUES (NULL, '$kode', '$nama', '$kategori', '$harga', '$stok', '$img')");
    }
    header("Location: data_produk.php");
}

// PROSES HAPUS
if(isset($_GET['hapus'])){
    mysqli_query($koneksi, "DELETE FROM produk WHERE id='$_GET[hapus]'");
    header("Location: data_produk.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Data Menu</title><script src="https://cdn.tailwindcss.com"></script><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head>
<body class="bg-gray-50 text-slate-800">
    <?php include 'sidebar.php'; ?>
    <div class="ml-64 p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Daftar Menu</h1>
            <button onclick="bukaModal()" class="bg-orange-600 text-white px-4 py-2 rounded-lg font-bold shadow hover:bg-orange-700 transition">
                <i class="fa-solid fa-plus"></i> Tambah Menu
            </button>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-orange-50 text-orange-700 uppercase font-bold">
                    <tr><th class="p-4">Produk</th><th class="p-4">Kategori</th><th class="p-4">Harga</th><th class="p-4">Stok</th><th class="p-4 text-center">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $q=mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id DESC"); while($r=mysqli_fetch_array($q)){ 
                        $gbr = !empty($r['gambar']) ? "assets/".$r['gambar'] : "https://placehold.co/50"; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="p-4 flex items-center gap-3">
                            <img src="<?= $gbr ?>" class="w-10 h-10 rounded object-cover bg-gray-200">
                            <div><div class="font-bold"><?= $r['nama_produk'] ?></div><div class="text-xs text-gray-400"><?= $r['kode_produk'] ?></div></div>
                        </td>
                        <td class="p-4"><span class="bg-gray-100 px-2 py-1 rounded text-xs border"><?= $r['kategori'] ?></span></td>
                        <td class="p-4 font-medium">Rp <?= number_format($r['harga']) ?></td>
                        <td class="p-4 font-bold <?= $r['stok']<10?'text-red-500':'text-green-600' ?>"><?= $r['stok'] ?></td>
                        <td class="p-4 text-center">
                            <button onclick="editProduk('<?= $r['id'] ?>','<?= $r['kode_produk'] ?>','<?= $r['nama_produk'] ?>','<?= $r['kategori'] ?>','<?= $r['harga'] ?>','<?= $r['stok'] ?>','<?= $r['gambar'] ?>')" class="text-blue-500 hover:text-blue-700 mx-1"><i class="fa-solid fa-pen-to-square"></i></button>
                            <a href="data_produk.php?hapus=<?= $r['id'] ?>" onclick="return confirm('Hapus?')" class="text-red-500 hover:text-red-700 mx-1"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalForm" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-lg shadow-2xl">
            <h3 class="font-bold text-lg mb-4 text-slate-800" id="modalTitle">Form Menu</h3>
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="id" id="id_produk">
                <input type="hidden" name="gambar_lama" id="gambar_lama">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500">Kode</label>
                        <input type="text" name="kode" id="kode" class="w-full border p-2 rounded focus:ring-2 focus:ring-orange-500 outline-none" required>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500">Kategori</label>
                        <select name="kategori" id="kategori" class="w-full border p-2 rounded focus:ring-2 focus:ring-orange-500 outline-none">
                            <option>Makanan</option><option>Minuman</option><option>Snack</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="text-xs font-bold text-gray-500">Nama Produk</label>
                    <input type="text" name="nama" id="nama" class="w-full border p-2 rounded focus:ring-2 focus:ring-orange-500 outline-none" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500">Harga (Rp)</label>
                        <input type="number" name="harga" id="harga" class="w-full border p-2 rounded focus:ring-2 focus:ring-orange-500 outline-none" required>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500">Stok Awal</label>
                        <input type="number" name="stok" id="stok" class="w-full border p-2 rounded focus:ring-2 focus:ring-orange-500 outline-none" required>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-500">Foto Menu (Opsional)</label>
                    <input type="file" name="foto" class="w-full text-xs border p-2 rounded bg-gray-50">
                    <p class="text-[10px] text-gray-400 mt-1" id="info_gbr"></p>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t mt-2">
                    <button type="button" onclick="document.getElementById('modalForm').classList.add('hidden')" class="bg-gray-200 px-4 py-2 rounded text-sm font-bold text-gray-600 hover:bg-gray-300">Batal</button>
                    <button type="submit" name="simpan" class="bg-orange-600 text-white px-6 py-2 rounded text-sm font-bold hover:bg-orange-700 shadow">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function bukaModal(){
            document.getElementById('modalForm').classList.remove('hidden');
            document.getElementById('modalTitle').innerText = "Tambah Menu Baru";
            // Reset Form
            document.getElementById('id_produk').value = "";
            document.getElementById('kode').value = "";
            document.getElementById('nama').value = "";
            document.getElementById('harga').value = "";
            document.getElementById('stok').value = "";
            document.getElementById('gambar_lama').value = "";
            document.getElementById('info_gbr').innerText = "";
        }

        function editProduk(id, kode, nama, kategori, harga, stok, gambar){
            document.getElementById('modalForm').classList.remove('hidden');
            document.getElementById('modalTitle').innerText = "Edit Menu";
            
            // Isi Form dengan Data Lama
            document.getElementById('id_produk').value = id;
            document.getElementById('kode').value = kode;
            document.getElementById('nama').value = nama;
            document.getElementById('kategori').value = kategori;
            document.getElementById('harga').value = harga;
            document.getElementById('stok').value = stok;
            document.getElementById('gambar_lama').value = gambar;
            
            if(gambar){
                document.getElementById('info_gbr').innerText = "Gambar lama tersimpan. Upload baru untuk mengganti.";
            }
        }
    </script>
</body>
</html>