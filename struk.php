<?php
include 'config.php';
$id = $_GET['id'];
$trx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id='$id'"));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Struk - <?= $trx['no_struk'] ?></title>
    <style>
        body { font-family: 'Courier New', monospace; font-size: 12px; width: 300px; margin: 0 auto; padding: 10px; }
        .center { text-align: center; }
        .line { border-bottom: 1px dashed #000; margin: 5px 0; }
        .flex { display: flex; justify-content: space-between; }
        .bold { font-weight: bold; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">

    <div class="center">
        <h2 style="margin:0">CAFE SKRIPSI</h2>
        <p style="margin:0">Jl. Teknologi No. 45, Jakarta</p>
    </div>
    
    <div class="line"></div>
    <div class="flex"><span>No: <?= $trx['no_struk'] ?></span> <span><?= date('d/m/y H:i', strtotime($trx['tgl_transaksi'])) ?></span></div>
    <div class="line"></div>

    <?php
    $items = mysqli_query($koneksi, "SELECT * FROM transaksi_detail JOIN produk ON transaksi_detail.id_produk = produk.id WHERE id_transaksi='$id'");
    while($i = mysqli_fetch_array($items)){
    ?>
    <div><?= $i['nama_produk'] ?></div>
    <div class="flex">
        <span><?= $i['qty'] ?> x <?= number_format($i['harga_saat_ini']) ?></span>
        <span><?= number_format($i['subtotal']) ?></span>
    </div>
    <?php } ?>

    <div class="line"></div>
    <div class="line"></div>
<div class="flex"><span>SUBTOTAL</span> <span>Rp <?= number_format($trx['total_bayar'] + $trx['diskon']) ?></span></div>

<?php if($trx['diskon'] > 0): ?>
<div class="flex"><span>DISKON</span> <span>- Rp <?= number_format($trx['diskon']) ?></span></div>
<?php endif; ?>

<div class="flex bold"><span>TOTAL BAYAR</span> <span>Rp <?= number_format($trx['total_bayar']) ?></span></div>
    <div class="flex"><span>TUNAI</span> <span>Rp <?= number_format($trx['bayar']) ?></span></div>
    <div class="flex"><span>KEMBALI</span> <span>Rp <?= number_format($trx['kembali']) ?></span></div>
    <div class="line"></div>
    
    <div class="center">
        <p>Terima Kasih atas Kunjungan Anda<br>Password Wifi: kopienak123</p>
    </div>

    <button class="no-print" onclick="window.location='transaksi.php'" style="width:100%; padding:10px; margin-top:20px; cursor:pointer;">Kembali ke Kasir</button>

</body>
</html>