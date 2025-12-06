<?php 
include 'config.php'; 
cek_login(); 
if($_SESSION['role'] != 'admin'){ header("Location: transaksi.php"); exit; }

// --- LOGIKA STATISTIK ---
$tgl_ini = date('Y-m-d');
$bln_ini = date('Y-m');

// 1. Omset Hari Ini
$q1 = mysqli_query($koneksi, "SELECT SUM(total_bayar) as omset FROM transaksi WHERE DATE(tgl_transaksi) = '$tgl_ini'");
$d1 = mysqli_fetch_assoc($q1);
$omset_hari = $d1['omset'] ?? 0;

// 2. Transaksi Hari Ini
$q2 = mysqli_query($koneksi, "SELECT COUNT(*) as jumlah FROM transaksi WHERE DATE(tgl_transaksi) = '$tgl_ini'");
$d2 = mysqli_fetch_assoc($q2);
$trx_hari = $d2['jumlah'] ?? 0;

// 3. Menu Terlaris (Top 1)
$q3 = mysqli_query($koneksi, "SELECT produk.nama_produk, SUM(transaksi_detail.qty) as total_qty 
                               FROM transaksi_detail 
                               JOIN produk ON transaksi_detail.id_produk = produk.id 
                               GROUP BY produk.id ORDER BY total_qty DESC LIMIT 1");
$d3 = mysqli_fetch_assoc($q3);
$top_menu = $d3['nama_produk'] ?? '-';

// 4. Data Grafik (5 Hari Terakhir)
$label_chart = [];
$data_chart = [];
for($i=4; $i>=0; $i--){
    $tgl = date('Y-m-d', strtotime("-$i days"));
    $q_chart = mysqli_query($koneksi, "SELECT SUM(total_bayar) as total FROM transaksi WHERE DATE(tgl_transaksi) = '$tgl'");
    $d_chart = mysqli_fetch_assoc($q_chart);
    $label_chart[] = date('d M', strtotime($tgl));
    $data_chart[] = $d_chart['total'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Owner</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style> body { font-family: 'Segoe UI', sans-serif; } </style>
</head>
<body class="bg-gray-100 text-slate-800">

    <?php include 'sidebar.php'; ?>

    <div class="md:ml-64 p-8 transition-all duration-300">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Dashboard Kafe</h1>
            <p class="text-gray-500">Halo, <?= $_SESSION['nama'] ?>. Berikut performa bisnismu hari ini.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-orange-500 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Omset Hari Ini</p>
                    <h3 class="text-2xl font-bold text-slate-800">Rp <?= number_format($omset_hari) ?></h3>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 text-xl">
                    <i class="fa-solid fa-coins"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-blue-500 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Transaksi Hari Ini</p>
                    <h3 class="text-2xl font-bold text-slate-800"><?= $trx_hari ?> <span class="text-sm font-normal">Bon</span></h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xl">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Menu Terlaris</p>
                    <h3 class="text-xl font-bold text-slate-800 truncate w-40" title="<?= $top_menu ?>"><?= $top_menu ?></h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 text-xl">
                    <i class="fa-solid fa-thumbs-up"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm">
                <h3 class="font-bold text-gray-700 mb-4">Grafik Penjualan (5 Hari Terakhir)</h3>
                <canvas id="chartOmset"></canvas>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm overflow-hidden">
                <h3 class="font-bold text-gray-700 mb-4">Transaksi Baru</h3>
                <div class="space-y-4">
                    <?php 
                    $q_last = mysqli_query($koneksi, "SELECT * FROM transaksi ORDER BY id DESC LIMIT 5");
                    while($rl = mysqli_fetch_array($q_last)){
                    ?>
                    <div class="flex justify-between items-center border-b border-gray-100 pb-2 last:border-0">
                        <div>
                            <p class="font-bold text-sm text-orange-600"><?= $rl['no_struk'] ?></p>
                            <p class="text-xs text-gray-400"><?= date('H:i', strtotime($rl['tgl_transaksi'])) ?></p>
                        </div>
                        <span class="font-bold text-sm">Rp <?= number_format($rl['total_bayar']) ?></span>
                    </div>
                    <?php } ?>
                </div>
                <a href="riwayat.php" class="block text-center text-sm text-blue-500 mt-4 hover:underline">Lihat Semua</a>
            </div>
        </div>

    </div>

    <script>
        new Chart(document.getElementById('chartOmset'), {
            type: 'line',
            data: {
                labels: <?= json_encode($label_chart) ?>,
                datasets: [{
                    label: 'Omset (Rp)',
                    data: <?= json_encode($data_chart) ?>,
                    borderColor: '#f97316', // Orange
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</body>
</html>