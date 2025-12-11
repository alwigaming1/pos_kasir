<?php include 'config.php'; cek_login(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Riwayat Transaksi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <?php include 'sidebar.php'; ?>

    <div class="md:ml-64 p-4 md:p-8 transition-all duration-300 pt-20 md:pt-8">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Riwayat Transaksi</h1>
                <p class="text-slate-500 text-sm">Daftar 50 transaksi terakhir.</p>
            </div>
            <button onclick="window.location.reload()" class="bg-white border border-gray-200 text-slate-600 px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-50 transition shadow-sm">
                <i class="fa-solid fa-rotate mr-1"></i> Refresh
            </button>
        </div>
        
        <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 uppercase text-xs font-bold text-slate-500">
                    <tr>
                        <th class="p-4">No Struk</th>
                        <th class="p-4">Waktu</th>
                        <th class="p-4">Total</th>
                        <th class="p-4">Bayar</th>
                        <th class="p-4">Kembali</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php 
                    $q=mysqli_query($koneksi, "SELECT * FROM transaksi ORDER BY id DESC LIMIT 50"); 
                    while($r=mysqli_fetch_array($q)){ ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 font-mono font-bold text-orange-600"><?= $r['no_struk'] ?></td>
                        <td class="p-4 text-slate-500"><?= date('d/m/Y H:i', strtotime($r['tgl_transaksi'])) ?></td>
                        <td class="p-4 font-bold text-slate-800">Rp <?= number_format($r['total_bayar']) ?></td>
                        <td class="p-4 text-slate-500">Rp <?= number_format($r['bayar']) ?></td>
                        <td class="p-4 text-green-600 font-medium">Rp <?= number_format($r['kembali']) ?></td>
                        <td class="p-4 text-center">
                            <a href="struk.php?id=<?= $r['id'] ?>" target="_blank" class="inline-flex items-center gap-2 bg-slate-900 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-orange-600 transition">
                                <i class="fa-solid fa-print"></i> Struk
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="md:hidden space-y-3">
            <?php 
            // Reset Pointer Query untuk Mobile Loop
            mysqli_data_seek($q, 0);
            while($r=mysqli_fetch_array($q)){ ?>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col gap-3">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-xs font-bold bg-orange-50 text-orange-600 px-2 py-1 rounded-md font-mono"><?= $r['no_struk'] ?></span>
                        <p class="text-xs text-slate-400 mt-2"><i class="fa-regular fa-clock mr-1"></i> <?= date('d M Y, H:i', strtotime($r['tgl_transaksi'])) ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400">Total</p>
                        <p class="text-lg font-extrabold text-slate-800">Rp <?= number_format($r['total_bayar']) ?></p>
                    </div>
                </div>
                
                <div class="border-t border-dashed border-slate-200 my-1"></div>
                
                <div class="flex justify-between items-center">
                    <div class="text-xs text-slate-500">
                        Bayar: Rp <?= number_format($r['bayar']) ?> <span class="mx-1 text-slate-300">|</span> 
                        Kembali: <span class="text-green-600 font-bold">Rp <?= number_format($r['kembali']) ?></span>
                    </div>
                    <a href="struk.php?id=<?= $r['id'] ?>" target="_blank" class="bg-slate-900 text-white w-8 h-8 flex items-center justify-center rounded-full hover:bg-orange-600 transition shadow-lg shadow-slate-900/20">
                        <i class="fa-solid fa-print text-xs"></i>
                    </a>
                </div>
            </div>
            <?php } ?>
        </div>

    </div>
</body>
</html>