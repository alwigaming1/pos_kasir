<?php include 'config.php'; cek_login(); if($_SESSION['role']!='admin'){header("Location:transaksi.php");} 

$tgl_mulai = isset($_GET['mulai']) ? $_GET['mulai'] : date('Y-m-d');
$tgl_akhir = isset($_GET['akhir']) ? $_GET['akhir'] : date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Laporan Penjualan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 text-slate-800">
    
    <?php include 'sidebar.php'; ?>

    <div class="md:ml-64 p-8">
        <h1 class="text-2xl font-bold mb-6 text-slate-800">Laporan Penjualan</h1>

        <div class="bg-white p-6 rounded-xl shadow-sm mb-6 border border-gray-100">
            <form class="flex flex-col md:flex-row gap-4 items-end">
                <div class="w-full md:w-auto">
                    <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Dari Tanggal</label>
                    <input type="date" name="mulai" value="<?= $tgl_mulai ?>" class="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-orange-500 outline-none transition">
                </div>
                <div class="w-full md:w-auto">
                    <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Sampai Tanggal</label>
                    <input type="date" name="akhir" value="<?= $tgl_akhir ?>" class="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-orange-500 outline-none transition">
                </div>
                
                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit" class="bg-orange-600 text-white px-6 py-2.5 rounded-lg font-bold hover:bg-orange-700 transition shadow-lg shadow-orange-200 flex items-center gap-2">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    
                    <a href="cetak.php?mulai=<?= $tgl_mulai ?>&akhir=<?= $tgl_akhir ?>" target="_blank" class="bg-slate-800 text-white px-6 py-2.5 rounded-lg font-bold hover:bg-slate-700 transition shadow-lg flex items-center gap-2">
                        <i class="fa-solid fa-print"></i> Cetak PDF
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-orange-50 text-orange-800 uppercase font-bold text-xs tracking-wider">
                    <tr>
                        <th class="p-4 border-b border-orange-100">No Struk</th>
                        <th class="p-4 border-b border-orange-100">Waktu</th>
                        <th class="p-4 border-b border-orange-100">Kasir</th>
                        <th class="p-4 border-b border-orange-100 text-right">Total Belanja</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php 
                    $grand_total = 0;
                    // Query data berdasarkan filter tanggal
                    $q = mysqli_query($koneksi, "SELECT t.*, u.nama_lengkap 
                                                 FROM transaksi t 
                                                 JOIN users u ON t.kasir_id = u.id 
                                                 WHERE t.tgl_transaksi BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_akhir 23:59:59' 
                                                 ORDER BY t.tgl_transaksi DESC");
                    
                    while($r = mysqli_fetch_array($q)){
                        $grand_total += $r['total_bayar'];
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 font-mono font-bold text-orange-600"><?= $r['no_struk'] ?></td>
                        <td class="p-4 text-gray-600"><?= date('d/m/Y H:i', strtotime($r['tgl_transaksi'])) ?></td>
                        <td class="p-4 font-medium"><?= $r['nama_lengkap'] ?></td>
                        <td class="p-4 text-right font-bold text-slate-700">Rp <?= number_format($r['total_bayar']) ?></td>
                    </tr>
                    <?php } ?>

                    <?php if(mysqli_num_rows($q) == 0): ?>
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-400 italic">
                            Tidak ada data transaksi pada periode ini.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot class="bg-gray-50 font-bold text-lg">
                    <tr>
                        <td colspan="3" class="p-4 text-right text-gray-600">TOTAL PENDAPATAN</td>
                        <td class="p-4 text-right text-orange-600">Rp <?= number_format($grand_total) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
</html>