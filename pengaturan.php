<?php 
include 'config.php'; 
cek_login(); 
if($_SESSION['role']!='admin'){header("Location:transaksi.php");} 

// PROSES SIMPAN
if(isset($_POST['simpan'])){
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_toko']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat_toko']);
    $telp   = mysqli_real_escape_string($koneksi, $_POST['telp_toko']);
    $footer = mysqli_real_escape_string($koneksi, $_POST['footer_struk']);
    $pajak  = (int)$_POST['pajak_persen'];

    $update = mysqli_query($koneksi, "UPDATE pengaturan SET nama_toko='$nama', alamat_toko='$alamat', telp_toko='$telp', footer_struk='$footer', pajak_persen='$pajak', updated_at=NOW() WHERE id=1");

    if($update){
        echo "<script>
            alert('Pengaturan Berhasil Disimpan!');
            window.location='pengaturan.php';
        </script>";
    }
}

$d = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pengaturan WHERE id=1"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Pengaturan Toko</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-[#F8F9FA] text-slate-800">
    
    <?php include 'sidebar.php'; ?>
    
    <div class="md:ml-64 p-6 md:p-10 pt-24 md:pt-10 transition-all duration-300">
        
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Identitas Bisnis</h1>
            <p class="text-slate-500 mt-2">Atur informasi yang akan tampil di struk belanja pelanggan.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-8 py-4 border-b border-slate-200 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600"><i class="fa-solid fa-pen-nib"></i></div>
                        <h3 class="font-bold text-slate-800">Edit Informasi</h3>
                    </div>
                    
                    <form method="POST" class="p-8 space-y-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Toko / Brand</label>
                                <div class="relative">
                                    <i class="fa-solid fa-store absolute left-4 top-3.5 text-slate-400"></i>
                                    <input type="text" name="nama_toko" value="<?= $d['nama_toko'] ?>" class="w-full pl-10 pr-4 py-3 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition font-bold text-slate-800" placeholder="Nama Kafe Anda">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nomor Telepon</label>
                                <div class="relative">
                                    <i class="fa-solid fa-phone absolute left-4 top-3.5 text-slate-400"></i>
                                    <input type="text" name="telp_toko" value="<?= $d['telp_toko'] ?>" class="w-full pl-10 pr-4 py-3 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition" placeholder="08xx-xxxx-xxxx">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Alamat Lengkap</label>
                            <div class="relative">
                                <i class="fa-solid fa-map-location-dot absolute left-4 top-3.5 text-slate-400"></i>
                                <textarea name="alamat_toko" rows="3" class="w-full pl-10 pr-4 py-3 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition resize-none" placeholder="Alamat lengkap toko..."><?= $d['alamat_toko'] ?></textarea>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Pajak / PPN (%)</label>
                                <div class="relative">
                                    <input type="number" name="pajak_persen" value="<?= $d['pajak_persen'] ?>" class="w-full pl-4 pr-10 py-3 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none font-mono font-bold" min="0" max="100">
                                    <span class="absolute right-4 top-3.5 text-slate-400 font-bold">%</span>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1">Isi 0 jika tidak ada pajak.</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Footer Struk (Pesan Bawah)</label>
                                <div class="relative">
                                    <i class="fa-solid fa-message absolute left-4 top-3.5 text-slate-400"></i>
                                    <input type="text" name="footer_struk" value="<?= $d['footer_struk'] ?>" class="w-full pl-10 pr-4 py-3 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none transition" placeholder="Terima kasih...">
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" name="simpan" class="bg-slate-900 text-white px-8 py-3.5 rounded-xl font-bold hover:bg-orange-600 transition shadow-xl shadow-slate-200 flex items-center gap-2 group">
                                <i class="fa-solid fa-floppy-disk group-hover:animate-bounce"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-4">Live Preview Struk</h3>
                    
                    <div class="bg-white p-6 rounded-sm shadow-lg border-t-8 border-slate-800 relative mx-auto" style="max-width: 320px; min-height: 400px;">
                        
                        <div class="absolute -bottom-2 left-0 w-full h-4 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMCAxMCIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+PHBhdGggZD0iTTAgMTBMNSAwIDEwIDEwIDE1IDAgMjAgMTBWMHoiIGZpbGw9IiNmZmYiLz48L3N2Zz4=')] bg-repeat-x bg-bottom"></div>

                        <div class="text-center font-mono text-slate-800 text-xs leading-relaxed space-y-1">
                            <div class="font-extrabold text-base mb-1 text-black"><?= strtoupper($d['nama_toko']) ?></div>
                            <div class="text-[10px] text-slate-500 px-4"><?= $d['alamat_toko'] ?></div>
                            <div class="text-[10px] border-b border-dashed border-slate-300 pb-2 mb-2">Telp: <?= $d['telp_toko'] ?></div>

                            <div class="flex justify-between"><span>Kopi Susu</span> <span>18.000</span></div>
                            <div class="flex justify-between"><span>Roti Bakar</span> <span>15.000</span></div>
                            
                            <div class="border-t border-dashed border-slate-300 my-2"></div>
                            
                            <div class="flex justify-between"><span>Subtotal</span> <span>33.000</span></div>
                            <?php if($d['pajak_persen'] > 0): ?>
                            <div class="flex justify-between text-slate-500"><span>PPN (<?= $d['pajak_persen'] ?>%)</span> <span>+3.300</span></div>
                            <?php endif; ?>
                            
                            <div class="flex justify-between font-bold text-sm mt-1"><span>TOTAL</span> <span>36.300</span></div>
                            
                            <div class="border-t border-dashed border-slate-300 my-2 pt-2"></div>
                            
                            <div class="text-slate-600 italic">"<?= $d['footer_struk'] ?>"</div>
                            <div class="mt-4"><i class="fa-solid fa-barcode text-4xl text-slate-200"></i></div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-100 flex gap-3 items-start">
                        <i class="fa-solid fa-circle-info text-blue-600 mt-0.5"></i>
                        <p class="text-xs text-blue-700 leading-relaxed">
                            <strong>Tips:</strong> Pastikan nama toko tidak terlalu panjang agar muat di kertas struk ukuran 58mm.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>