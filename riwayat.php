<?php include 'config.php'; cek_login(); ?>
<!DOCTYPE html>
<html lang="id">
<head><title>Riwayat Transaksi</title><script src="https://cdn.tailwindcss.com"></script><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head>
<body class="bg-gray-50 text-slate-800">
    <?php include 'sidebar.php'; ?>
    <div class="ml-64 p-8">
        <h1 class="text-2xl font-bold mb-6">Riwayat Transaksi</h1>
        
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-100 uppercase text-xs font-bold text-gray-600">
                    <tr><th class="p-4">No Struk</th><th class="p-4">Tanggal</th><th class="p-4">Total</th><th class="p-4">Bayar</th><th class="p-4">Kembali</th><th class="p-4">Aksi</th></tr>
                </thead>
                <tbody class="divide-y">
                    <?php 
                    $q=mysqli_query($koneksi, "SELECT * FROM transaksi ORDER BY id DESC LIMIT 50"); 
                    while($r=mysqli_fetch_array($q)){ ?>
                    <tr class="hover:bg-orange-50">
                        <td class="p-4 font-mono font-bold text-orange-600"><?= $r['no_struk'] ?></td>
                        <td class="p-4 text-gray-500"><?= $r['tgl_transaksi'] ?></td>
                        <td class="p-4 font-bold">Rp <?= number_format($r['total_bayar']) ?></td>
                        <td class="p-4">Rp <?= number_format($r['bayar']) ?></td>
                        <td class="p-4 text-green-600">Rp <?= number_format($r['kembali']) ?></td>
                        <td class="p-4">
                            <a href="struk.php?id=<?= $r['id'] ?>" target="_blank" class="bg-slate-800 text-white px-3 py-1 rounded text-xs hover:bg-slate-700"><i class="fa-solid fa-print"></i> Struk</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>