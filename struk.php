<?php
include 'config.php';
cek_login(); 
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id == 0){ die("ID Error"); }

$trx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT t.*, u.nama_lengkap as kasir FROM transaksi t LEFT JOIN users u ON t.kasir_id = u.id WHERE t.id='$id'"));
if(!$trx){ die("Data tidak ditemukan"); }

$q_toko = mysqli_query($koneksi, "SELECT * FROM pengaturan WHERE id=1");
$toko = (mysqli_num_rows($q_toko) > 0) ? mysqli_fetch_assoc($q_toko) : ['nama_toko'=>'CafePOS', 'alamat_toko'=>'-', 'telp_toko'=>'-', 'footer_struk'=>'Terima Kasih'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Struk #<?= $trx['no_struk'] ?></title>
    <style>
        body { font-family: 'Courier New', monospace; font-size: 12px; margin: 0; padding: 10px; background-color: #f0f0f0; }
        .wrapper { width: 58mm; margin: 0 auto; background: #fff; padding: 10px; box-shadow: 0 0 5px rgba(0,0,0,0.1); margin-bottom: 20px;}
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .line { border-bottom: 1px dashed #000; margin: 8px 0; }
        .flex { display: flex; justify-content: space-between; margin-bottom: 3px;}
        
        /* UPDATE: TOMBOL RAPI & SIMETRIS */
        .action-area { 
            display: grid; 
            grid-template-columns: 1fr; 
            gap: 8px; 
            max-width: 300px; 
            margin: 0 auto; 
        }
        .btn { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 8px;
            padding: 12px; 
            border: none; 
            cursor: pointer; 
            border-radius: 8px; 
            font-family: sans-serif; 
            font-weight: bold; 
            font-size: 13px; 
            text-decoration: none; 
            transition: opacity 0.2s;
        }
        .btn:hover { opacity: 0.9; }
        .btn-print { background: #1e293b; color: #fff; } /* Dark Slate */
        .btn-dapur { background: #ea580c; color: #fff; } /* Orange */
        .btn-back { background: #fff; border: 1px solid #cbd5e1; color: #475569; } /* White/Gray */

        @media print { body { background: #fff; padding: 0; } .wrapper { width: 100%; box-shadow: none; padding: 0; margin: 0; } .no-print { display: none; } }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body onload="window.print()">
    <div class="wrapper">
        <div class="center">
            <h3 style="margin:5px 0; font-size: 14px;"><?= strtoupper($toko['nama_toko']) ?></h3>
            <p style="margin:0; font-size: 10px;"><?= $toko['alamat_toko'] ?></p>
            <p style="margin:0; font-size: 10px;">Telp: <?= $toko['telp_toko'] ?></p>
        </div>
        <div class="line"></div>
        <div class="flex"><span>No: <?= $trx['no_struk'] ?></span></div>
        <div class="flex"><span>Tgl: <?= date('d/m/y H:i', strtotime($trx['tgl_transaksi'])) ?></span></div>
        <div class="flex"><span>Kasir: <?= $trx['kasir'] ?></span></div>
        <div class="flex"><span>Kpd: <?= $trx['nama_pelanggan'] ?></span></div>
        <div class="center bold" style="margin-top:5px; font-size:12px; border:1px solid #000; padding:2px;"><?= strtoupper($trx['tipe_pesanan']) ?> (<?= strtoupper($trx['metode_pembayaran']) ?>)</div>
        <div class="line"></div>

        <?php
        $items = mysqli_query($koneksi, "SELECT t.*, p.nama_produk FROM transaksi_detail t JOIN produk p ON t.id_produk = p.id WHERE t.id_transaksi='$id'");
        while($i = mysqli_fetch_array($items)){
        ?>
        <div style="margin-bottom: 4px;">
            <div><?= $i['nama_produk'] ?></div>
            <div class="flex"><span><?= $i['qty'] ?> x <?= number_format($i['harga_saat_ini']) ?></span> <span><?= number_format($i['subtotal']) ?></span></div>
        </div>
        <?php } ?>

        <div class="line"></div>

        <?php $subtotal_murni = $trx['total_bayar'] + $trx['diskon'] - $trx['pajak']; ?>
        <div class="flex"><span>SUBTOTAL</span> <span><?= number_format($subtotal_murni) ?></span></div>
        
        <?php if($trx['diskon'] > 0): ?>
        <div class="flex"><span>DISKON</span> <span>-<?= number_format($trx['diskon']) ?></span></div>
        <?php endif; ?>

        <?php if($trx['pajak'] > 0): ?>
        <div class="flex"><span>PPN</span> <span>+<?= number_format($trx['pajak']) ?></span></div>
        <?php endif; ?>
        
        <div class="flex bold" style="font-size: 14px; margin-top: 5px;"><span>TOTAL</span> <span>Rp <?= number_format($trx['total_bayar']) ?></span></div>
        
        <div class="line"></div>
        <div class="flex"><span>BAYAR</span> <span><?= number_format($trx['bayar']) ?></span></div>
        <div class="flex"><span>KEMBALI</span> <span><?= number_format($trx['kembali']) ?></span></div>
        <div class="line"></div>
        
        <div class="center" style="margin-top: 10px;">
            <p style="margin-bottom: 5px;"><?= $toko['footer_struk'] ?></p>
            <p style="font-size: 10px;">-- Terima Kasih --</p>
        </div>
    </div>

    <div class="wrapper no-print" style="background: transparent; box-shadow: none; padding-top: 0;">
        <div class="action-area">
            <button class="btn btn-print" onclick="window.print()">
                <i class="fa-solid fa-print"></i> Cetak Struk Pelanggan
            </button>
            
            <a href="cetak_dapur.php?id=<?= $id ?>" target="_blank" class="btn btn-dapur">
                <i class="fa-solid fa-utensils"></i> Cetak Struk Dapur
            </a>
            
            <a href="transaksi.php" class="btn btn-back">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Kasir
            </a>
        </div>
    </div>
</body>
</html>