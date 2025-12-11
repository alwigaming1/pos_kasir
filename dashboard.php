<?php 
include 'config.php'; 
cek_login(); 
if($_SESSION['role'] != 'admin'){ header("Location: transaksi.php"); exit; }

// --- LOGIKA DATA REAL ---
$tgl_ini = date('Y-m-d');
$tgl_kemarin = date('Y-m-d', strtotime("-1 days"));

// 1. Data Hari Ini
$omset = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total_bayar) as val FROM transaksi WHERE DATE(tgl_transaksi) = '$tgl_ini'"));
$trx_count = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as val FROM transaksi WHERE DATE(tgl_transaksi) = '$tgl_ini'"));

// 2. Data Kemarin (Untuk Perbandingan/Indikator Naik Turun)
$omset_kemarin = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total_bayar) as val FROM transaksi WHERE DATE(tgl_transaksi) = '$tgl_kemarin'"));

// Hitung Persentase Kenaikan (Simulasi)
$omset_now = $omset['val'] ?? 0;
$omset_old = $omset_kemarin['val'] ?? 0;
$persen = 0;
$is_naik = true;

if($omset_old > 0){
    $diff = $omset_now - $omset_old;
    $persen = round(($diff / $omset_old) * 100);
} else if($omset_now > 0){
    $persen = 100; // Jika kemarin 0, hari ini ada omset, naik 100%
}
if($persen < 0) { $is_naik = false; $persen = abs($persen); }

// Data Lain
$stok_kritis = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as val FROM produk WHERE stok <= 5"));
$produk_count = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as val FROM produk"));

// Grafik 7 Hari
$chart_label = [];
$chart_data = [];
for($i=6; $i>=0; $i--){
    $t = date('Y-m-d', strtotime("-$i days"));
    $q = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total_bayar) as val FROM transaksi WHERE DATE(tgl_transaksi) = '$t'"));
    $chart_label[] = date('d/m', strtotime($t));
    $chart_data[] = $q['val'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="assets/favicon.png">
    <title>Dashboard Executive - <?= $conf['app_name'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <?php include 'sidebar.php'; ?>

    <div class="md:ml-64 p-6 md:p-8 transition-all duration-300 min-h-screen">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Selamat Pagi, Owner! 👋</h1>
                <p class="text-slate-500 text-sm mt-1">Berikut adalah performa bisnis Anda hari ini.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="window.location.reload()" class="bg-white border border-gray-200 text-slate-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-gray-50 transition shadow-sm">
                    <i class="fa-solid fa-rotate mr-2"></i> Refresh
                </button>
                <a href="laporan.php" class="bg-slate-900 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-slate-800 transition shadow-lg shadow-slate-900/20">
                    <i class="fa-solid fa-file-export mr-2"></i> Export Data
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-300">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-green-50 rounded-xl text-green-600">
                        <i class="fa-solid fa-wallet text-xl"></i>
                    </div>
                    <?php if($is_naik): ?>
                        <span class="flex items-center text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-full">
                            <i class="fa-solid fa-arrow-trend-up mr-1"></i> +<?= $persen ?>%
                        </span>
                    <?php else: ?>
                        <span class="flex items-center text-xs font-bold text-red-500 bg-red-50 px-2 py-1 rounded-full">
                            <i class="fa-solid fa-arrow-trend-down mr-1"></i> -<?= $persen ?>%
                        </span>
                    <?php endif; ?>
                </div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Pendapatan Bersih</p>
                <h3 class="text-2xl font-extrabold text-slate-800">Rp <?= number_format($omset_now) ?></h3>
                <p class="text-xs text-slate-400 mt-2">Vs kemarin Rp <?= number_format($omset_old) ?></p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-300">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                        <i class="fa-solid fa-receipt text-xl"></i>
                    </div>
                </div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Total Transaksi</p>
                <h3 class="text-2xl font-extrabold text-slate-800"><?= $trx_count['val'] ?? 0 ?> <span class="text-sm font-medium text-slate-400">Bon</span></h3>
                <p class="text-xs text-slate-400 mt-2">Data real-time hari ini</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-300">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-red-50 rounded-xl text-red-600">
                        <i class="fa-solid fa-box-open text-xl"></i>
                    </div>
                    <?php if($stok_kritis['val'] > 0): ?>
                    <span class="flex items-center text-xs font-bold text-white bg-red-500 px-2 py-1 rounded-full animate-pulse">
                        Darurat
                    </span>
                    <?php endif; ?>
                </div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Stok Menipis</p>
                <h3 class="text-2xl font-extrabold text-slate-800"><?= $stok_kritis['val'] ?? 0 ?> <span class="text-sm font-medium text-slate-400">Item</span></h3>
                <p class="text-xs text-slate-400 mt-2">Perlu restock segera</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition duration-300">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-orange-50 rounded-xl text-orange-600">
                        <i class="fa-solid fa-utensils text-xl"></i>
                    </div>
                </div>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Total Menu Aktif</p>
                <h3 class="text-2xl font-extrabold text-slate-800"><?= $produk_count['val'] ?? 0 ?> <span class="text-sm font-medium text-slate-400">Varian</span></h3>
                <p class="text-xs text-slate-400 mt-2">Siap dijual</p>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">Statistik Penjualan</h3>
                        <p class="text-sm text-slate-400">Grafik omset 7 hari terakhir</p>
                    </div>
                </div>
                <div class="relative h-80 w-full">
                    <canvas id="chartOmset"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-bell text-orange-500"></i> Peringatan Stok
                </h3>
                
                <div class="flex-1 overflow-y-auto space-y-3 custom-scrollbar pr-2" style="max-height: 300px;">
                    <?php 
                    $q_stok = mysqli_query($koneksi, "SELECT * FROM produk WHERE stok <= 5 ORDER BY stok ASC LIMIT 5");
                    if(mysqli_num_rows($q_stok) == 0): 
                    ?>
                        <div class="flex flex-col items-center justify-center h-full text-center py-8">
                            <div class="w-12 h-12 bg-green-50 text-green-500 rounded-full flex items-center justify-center mb-3">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-600">Semua Aman!</p>
                            <p class="text-xs text-slate-400">Tidak ada stok yang menipis.</p>
                        </div>
                    <?php else: ?>
                        <?php while($rs = mysqli_fetch_array($q_stok)){ ?>
                        <div class="flex items-center gap-4 p-3 rounded-xl border border-red-100 bg-red-50/50 hover:bg-red-50 transition">
                            <img src="<?= !empty($rs['gambar']) ? 'assets/'.$rs['gambar'] : 'https://placehold.co/100' ?>" class="w-10 h-10 rounded-lg object-cover bg-white">
                            <div class="flex-1">
                                <h4 class="font-bold text-sm text-slate-800 line-clamp-1"><?= $rs['nama_produk'] ?></h4>
                                <p class="text-xs text-red-500 font-bold">Sisa: <?= $rs['stok'] ?> Pcs</p>
                            </div>
                            <a href="data_produk.php" class="text-xs bg-white border border-gray-200 text-slate-600 px-3 py-1.5 rounded-lg hover:bg-slate-800 hover:text-white transition">Restock</a>
                        </div>
                        <?php } ?>
                    <?php endif; ?>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100">
                    <a href="data_produk.php" class="block w-full text-center text-sm font-bold text-slate-600 hover:text-orange-600 py-2 bg-gray-50 rounded-xl hover:bg-orange-50 transition">Lihat Semua Menu</a>
                </div>
            </div>

        </div>
    </div>

    <script>
        const ctx = document.getElementById('chartOmset').getContext('2d');
        
        // Buat Gradient Warna (Supaya terlihat mahal)
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(249, 115, 22, 0.5)'); // Orange Tebal
        gradient.addColorStop(1, 'rgba(249, 115, 22, 0.0)'); // Transparan

        new Chart(ctx, {
            type: 'line', // Ganti jadi Line chart curve
            data: {
                labels: <?= json_encode($chart_label) ?>,
                datasets: [{
                    label: 'Omset (Rp)',
                    data: <?= json_encode($chart_data) ?>,
                    borderColor: '#f97316', // Garis Orange
                    backgroundColor: gradient, // Isi Gradient
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#f97316',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true, // Isi area bawah grafik
                    tension: 0.4 // Buat garis melengkung (smooth)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [5, 5], color: '#f1f5f9' },
                        ticks: { font: { family: "'Plus Jakarta Sans', sans-serif", size: 10 } }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { font: { family: "'Plus Jakarta Sans', sans-serif", size: 10 } }
                    }
                },
                interaction: { mode: 'nearest', axis: 'x', intersect: false }
            }
        });
    </script>
</body>
</html>