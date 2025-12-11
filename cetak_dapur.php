<?php
include 'config.php';
cek_login(); 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$trx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id='$id'"));

if(!$trx){ die("Data tidak ditemukan"); }
?>
<!DOCTYPE html>
<html>
<head>
    <title>DAPUR - #<?= $trx['no_struk'] ?></title>
    <style>
        body { font-family: 'Courier New', monospace; margin: 0; padding: 10px; background-color: #f0f0f0; }
        .wrapper { width: 58mm; margin: 0 auto; background: #fff; padding: 10px; padding-bottom: 20px; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .line { border-bottom: 2px dashed #000; margin: 10px 0; }
        
        /* Font Dapur Lebih Besar */
        .item-name { font-size: 14px; font-weight: bold; display: block; margin-top: 5px; }
        .item-qty { font-size: 16px; font-weight: 800; }
        
        .header-dapur { background: #000; color: #fff; padding: 5px; font-size: 16px; font-weight: bold; margin-bottom: 10px; }
        .tipe-order { border: 2px solid #000; padding: 5px; font-size: 14px; font-weight: bold; margin-top: 10px; }

        @media print { 
            body { background: #fff; } 
            .wrapper { width: 100%; box-shadow: none; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="wrapper">
        <div class="center header-dapur">ORDER DAPUR/BAR</div>
        
        <div class="bold">Ref: #<?= substr($trx['no_struk'], -6) ?></div>
        <div><?= date('d/m/y H:i', strtotime($trx['tgl_transaksi'])) ?></div>
        <div class="bold" style="margin-top: 5px;">A.n: <?= $trx['nama_pelanggan'] ?></div>
        
        <div class="center tipe-order">
            <?= strtoupper($trx['tipe_pesanan']) ?>
        </div>

        <div class="line"></div>

        <?php
        $items = mysqli_query($koneksi, "SELECT t.*, p.nama_produk, p.kategori 
                                         FROM transaksi_detail t 
                                         JOIN produk p ON t.id_produk = p.id 
                                         WHERE t.id_transaksi='$id'");
        while($i = mysqli_fetch_array($items)){
        ?>
        <div style="margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px;">
            <div style="display: flex; gap: 10px; align-items: start;">
                <div class="item-qty"><?= $i['qty'] ?>x</div>
                <div>
                    <span class="item-name"><?= $i['nama_produk'] ?></span>
                    <span style="font-size: 10px; color: #666;">[<?= $i['kategori'] ?>]</span>
                </div>
            </div>
        </div>
        <?php } ?>

        <div class="line"></div>
        <div class="center" style="font-size: 10px;">-- Pastikan pesanan sesuai --</div>
    </div>

</body>
</html>