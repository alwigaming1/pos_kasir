<?php include 'config.php'; cek_login(); if($_SESSION['role']!='admin'){header("Location:transaksi.php");} 

$tgl_mulai = isset($_GET['mulai']) ? $_GET['mulai'] : date('Y-m-d');
$tgl_akhir = isset($_GET['akhir']) ? $_GET['akhir'] : date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Laporan Penjualan</title><script src="https://cdn.tailwindcss.com"></script><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head>
<body class="bg-gray-100 text-slate-800">
    <?php include 'sidebar.php'; ?>
    <div class="md:ml-64 p-8">
        <h1 class="text-2xl font-bold mb-6">Laporan Penjualan</h1>

        <div class="bg-white p-4 rounded-xl shadow mb-6 flex flex-wrap gap-4 items-end">
            <form class="flex gap-4 items-end">
                <div><label class="block text-xs font-bold text-gray-500">Dari Tanggal</label><input type="date" name="mulai" value="<?= $tgl_mulai ?>" class="border p-2 rounded"></div>
                <div><label class="block text-xs font-bold text-gray-500">Sampai Tanggal</label><input type="date" name="akhir" value="<?= $tgl_akhir ?>" class="border p-2 rounded"></div>
                <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded font-bold hover:bg-orange-700">Filter</button>
                <button type="button" onclick="window.print()" class="bg-slate-800 text-white px-4 py-2 rounded font-bold hover:bg-slate-700"><i class="fa-solid fa-print"></i> Cetak</button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-orange-50 text-orange-800 uppercase font-bold">
                    <tr><th class="p-4">No Struk</th><th class="p-4">Tanggal</th><th class="p-4">Kasir</th><th class="p-4 text-right">Total Belanja</th></tr>
                </thead>
                <tbody class="divide-y">
                    <?php 
                    $grand_total = 0;
                    // Tambahkan 1 hari ke tgl_akhir agar query BETWEEN mencakup sampai jam 23:59
                    $q = mysqli_query($koneksi, "SELECT t.*, u.nama_lengkap FROM transaksi t JOIN users u ON t.kasir_id = u.id WHERE t.tgl_transaksi BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_akhir 23:59:59' ORDER BY t.id DESC");
                    while($r=mysqli_fetch_array($q)){
                        $grand_total += $r['total_bayar'];
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="p-4 font-mono"><?= $r['no_struk'] ?></td>
                        <td class="p-4"><?= $r['tgl_transaksi'] ?></td>
                        <td class="p-4"><?= $r['nama_lengkap'] ?></td>
                        <td class="p-4 text-right font-bold">Rp <?= number_format($r['total_bayar']) ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot class="bg-gray-100 font-bold text-lg">
                    <tr>
                        <td colspan="3" class="p-4 text-right">TOTAL PENDAPATAN</td>
                        <td class="p-4 text-right text-orange-600">Rp <?= number_format($grand_total) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
</html>