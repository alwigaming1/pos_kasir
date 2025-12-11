<?php 
include 'config.php'; 
cek_login(); 

// Ambil filter tanggal dari URL (dikirim dari laporan.php)
// Jika tidak ada, default ke hari ini
$tgl_mulai = isset($_GET['mulai']) ? $_GET['mulai'] : date('Y-m-d');
$tgl_akhir = isset($_GET['akhir']) ? $_GET['akhir'] : date('Y-m-d');

// Format tanggal untuk judul (Indonesia)
$label_mulai = date('d-m-Y', strtotime($tgl_mulai));
$label_akhir = date('d-m-Y', strtotime($tgl_akhir));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; padding: 20px; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #eee; text-align: center; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        /* Hilangkan tombol saat diprint */
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>LAPORAN PENJUALAN</h1>
        <p><?= $conf['app_name'] ?></p>
        <p>Periode: <?= $label_mulai ?> s/d <?= $label_akhir ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>No Struk</th>
                <th>Waktu Transaksi</th>
                <th>Kasir</th>
                <th>Total Belanja</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $grand_total = 0;
            
            // Query ambil data sesuai tanggal
            $q = mysqli_query($koneksi, "SELECT t.*, u.nama_lengkap 
                                         FROM transaksi t 
                                         JOIN users u ON t.kasir_id = u.id 
                                         WHERE t.tgl_transaksi BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_akhir 23:59:59' 
                                         ORDER BY t.tgl_transaksi ASC");
            
            while($r = mysqli_fetch_array($q)){
                $grand_total += $r['total_bayar'];
            ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= $r['no_struk'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($r['tgl_transaksi'])) ?></td>
                <td><?= $r['nama_lengkap'] ?></td>
                <td class="text-right">Rp <?= number_format($r['total_bayar']) ?></td>
            </tr>
            <?php } ?>
            
            <?php if(mysqli_num_rows($q) == 0): ?>
            <tr>
                <td colspan="5" class="text-center" style="padding: 20px;">Tidak ada transaksi pada periode ini.</td>
            </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right" style="font-weight:bold; background:#eee;">TOTAL PENDAPATAN</td>
                <td class="text-right" style="font-weight:bold; background:#eee;">Rp <?= number_format($grand_total) ?></td>
            </tr>
        </tfoot>
    </table>

    <div style="float: right; margin-top: 30px; text-align: center; width: 200px;">
        <p>Kota, <?= date('d-m-Y') ?></p>
        <br><br><br>
        <p>_______________________</p>
        <p>Manager / Admin</p>
    </div>

</body>
</html>