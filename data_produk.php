<?php include 'config.php'; cek_login(); if($_SESSION['role']!='admin'){header("Location:transaksi.php");} 

// PROSES SIMPAN
if(isset($_POST['simpan'])){
    $kode = $_POST['kode']; 
    $nama = $_POST['nama']; 
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga']; 
    $stok = $_POST['stok'];
    // Ambil Input Tags (ubah jadi huruf kecil semua biar seragam)
    $tags = strtolower($_POST['tags']); 

    if(!empty($_FILES['foto']['name'])){
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $img = "menu_".time().".".$ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], "assets/".$img);
    } else { $img = $_POST['gambar_lama']; }

    if($_POST['id'] != ""){
        mysqli_query($koneksi, "UPDATE produk SET kode_produk='$kode', nama_produk='$nama', kategori='$kategori', tags='$tags', harga='$harga', stok='$stok', gambar='$img' WHERE id='$_POST[id]'");
    } else {
        mysqli_query($koneksi, "INSERT INTO produk VALUES (NULL, '$kode', '$nama', '$kategori', '$harga', '$stok', '$img', '$tags')");
    }
    header("Location: data_produk.php");
}

if(isset($_GET['hapus'])){
    mysqli_query($koneksi, "DELETE FROM produk WHERE id='$_GET[hapus]'");
    header("Location: data_produk.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Data Menu</title>
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
                <h1 class="text-2xl font-bold text-slate-800">Daftar Menu</h1>
                <p class="text-slate-500 text-sm">Kelola makanan, minuman, dan label filter.</p>
            </div>
            <button onclick="bukaModal()" class="w-full md:w-auto bg-orange-600 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg hover:bg-orange-700 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Menu
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs">
                        <tr>
                            <th class="p-4">Produk</th>
                            <th class="p-4">Kategori & Tags</th>
                            <th class="p-4">Harga</th>
                            <th class="p-4">Stok</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $q=mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id DESC"); while($r=mysqli_fetch_array($q)){ 
                            $gbr = !empty($r['gambar']) ? "assets/".$r['gambar'] : "https://placehold.co/100x100/f1f5f9/94a3b8?text=IMG"; ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <img src="<?= $gbr ?>" class="w-10 h-10 rounded-lg object-cover border border-slate-200">
                                    <div>
                                        <div class="font-bold text-slate-800"><?= $r['nama_produk'] ?></div>
                                        <div class="text-xs text-slate-400 font-mono"><?= $r['kode_produk'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex flex-col gap-1">
                                    <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs border font-bold w-fit"><?= $r['kategori'] ?></span>
                                    <?php if(!empty($r['tags'])): ?>
                                    <div class="flex gap-1 flex-wrap">
                                        <?php foreach(explode(',', $r['tags']) as $tag): ?>
                                            <span class="text-[10px] bg-orange-50 text-orange-600 px-1.5 py-0.5 rounded border border-orange-100">#<?= trim($tag) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="p-4 font-bold text-slate-700">Rp <?= number_format($r['harga']) ?></td>
                            <td class="p-4"><span class="<?= $r['stok']<=5 ? 'text-red-600 bg-red-50' : 'text-green-600 bg-green-50' ?> px-2 py-1 rounded font-bold text-xs"><?= $r['stok'] ?></span></td>
                            <td class="p-4 text-center">
                                <button onclick="editProduk('<?= $r['id'] ?>','<?= $r['kode_produk'] ?>','<?= $r['nama_produk'] ?>','<?= $r['kategori'] ?>','<?= $r['harga'] ?>','<?= $r['stok'] ?>','<?= $r['gambar'] ?>', '<?= $r['tags'] ?>')" class="text-blue-500 hover:text-blue-700 mx-1"><i class="fa-solid fa-pen-to-square"></i></button>
                                <a href="data_produk.php?hapus=<?= $r['id'] ?>" onclick="return confirm('Hapus?')" class="text-red-500 hover:text-red-700 mx-1"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalForm" class="fixed inset-0 bg-black/60 hidden flex items-center justify-center p-4 z-[60] backdrop-blur-sm">
        <div class="bg-white p-6 rounded-2xl w-full max-w-lg shadow-2xl relative animate-bounce-slow">
            <button onclick="document.getElementById('modalForm').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-800"><i class="fa-solid fa-xmark text-xl"></i></button>
            <h3 class="font-bold text-xl mb-6 text-slate-800" id="modalTitle">Form Menu</h3>
            
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="id" id="id_produk">
                <input type="hidden" name="gambar_lama" id="gambar_lama">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase">Kode</label>
                        <input type="text" name="kode" id="kode" class="w-full border p-2.5 rounded-lg focus:ring-2 focus:ring-orange-500 outline-none mt-1" required>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase">Kategori Utama</label>
                        <select name="kategori" id="kategori" class="w-full border p-2.5 rounded-lg focus:ring-2 focus:ring-orange-500 outline-none mt-1">
                            <option>Makanan</option><option>Minuman</option><option>Snack</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Nama Produk</label>
                    <input type="text" name="nama" id="nama" class="w-full border p-2.5 rounded-lg focus:ring-2 focus:ring-orange-500 outline-none mt-1" required>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase flex justify-between">
                        <span>Label / Tags (Filter Tambahan)</span>
                        <span class="text-gray-400 font-normal normal-case">Pisahkan dengan koma (,)</span>
                    </label>
                    <input type="text" name="tags" id="tags" class="w-full border p-2.5 rounded-lg focus:ring-2 focus:ring-orange-500 outline-none mt-1 placeholder-slate-300" placeholder="Contoh: Kopi, Dingin, Best Seller">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase">Harga</label>
                        <input type="number" name="harga" id="harga" class="w-full border p-2.5 rounded-lg focus:ring-2 focus:ring-orange-500 outline-none mt-1" required>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase">Stok</label>
                        <input type="number" name="stok" id="stok" class="w-full border p-2.5 rounded-lg focus:ring-2 focus:ring-orange-500 outline-none mt-1" required>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Foto</label>
                    <input type="file" name="foto" class="w-full text-xs border p-2 rounded-lg bg-slate-50 mt-1">
                    <p class="text-[10px] text-slate-400 mt-1" id="info_gbr"></p>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t mt-2">
                    <button type="button" onclick="document.getElementById('modalForm').classList.add('hidden')" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 hover:bg-slate-100">Batal</button>
                    <button type="submit" name="simpan" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-orange-600 transition shadow-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function bukaModal(){
            document.getElementById('modalForm').classList.remove('hidden');
            document.getElementById('modalTitle').innerText = "Tambah Menu";
            document.getElementById('id_produk').value = ""; document.getElementById('kode').value = "";
            document.getElementById('nama').value = ""; document.getElementById('harga').value = "";
            document.getElementById('stok').value = ""; document.getElementById('tags').value = "";
            document.getElementById('gambar_lama').value = ""; document.getElementById('info_gbr').innerText = "";
        }
        function editProduk(id, kode, nama, kategori, harga, stok, gambar, tags){
            document.getElementById('modalForm').classList.remove('hidden');
            document.getElementById('modalTitle').innerText = "Edit Menu";
            document.getElementById('id_produk').value = id; document.getElementById('kode').value = kode;
            document.getElementById('nama').value = nama; document.getElementById('kategori').value = kategori;
            document.getElementById('harga').value = harga; document.getElementById('stok').value = stok;
            document.getElementById('tags').value = tags; // Isi Tags
            document.getElementById('gambar_lama').value = gambar;
            if(gambar) document.getElementById('info_gbr').innerText = "Gambar lama tersimpan.";
        }
    </script>
</body>
</html>