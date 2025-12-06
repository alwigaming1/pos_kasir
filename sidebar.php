<?php
$role = $_SESSION['role'] ?? 'kasir'; 
?>
<aside class="w-64 bg-white h-screen fixed left-0 top-0 border-r border-gray-200 flex flex-col z-50">
    
    <div class="h-20 flex items-center px-8 border-b border-gray-100">
        <div class="flex items-center gap-3 text-orange-600">
            <i class="fa-solid fa-mug-hot text-3xl"></i>
            <div>
                <h1 class="font-bold text-lg leading-tight text-slate-800">CafePOS</h1>
                <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider"><?= $_SESSION['nama'] ?? 'User' ?></p>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
        
        <?php if($role == 'admin'): ?>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 mt-2 px-4">Dashboard</p>
            
            <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-orange-50 text-orange-600 font-bold' : 'text-gray-600 hover:bg-gray-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm">
                <i class="fa-solid fa-chart-pie w-5"></i> Ringkasan
            </a>

            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 mt-6 px-4">Master Data</p>

            <a href="data_produk.php" class="<?= basename($_SERVER['PHP_SELF']) == 'data_produk.php' ? 'bg-orange-50 text-orange-600 font-bold' : 'text-gray-600 hover:bg-gray-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm">
                <i class="fa-solid fa-burger w-5"></i> Data Menu & Stok
            </a>
            
            <a href="data_user.php" class="<?= basename($_SERVER['PHP_SELF']) == 'data_user.php' ? 'bg-orange-50 text-orange-600 font-bold' : 'text-gray-600 hover:bg-gray-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm">
                <i class="fa-solid fa-users-gear w-5"></i> Data Pengguna
            </a>

            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 mt-6 px-4">Keuangan</p>

            <a href="riwayat.php" class="<?= basename($_SERVER['PHP_SELF']) == 'riwayat.php' ? 'bg-orange-50 text-orange-600 font-bold' : 'text-gray-600 hover:bg-gray-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm">
                <i class="fa-solid fa-clock-rotate-left w-5"></i> Riwayat Transaksi
            </a>

            <a href="laporan.php" class="<?= basename($_SERVER['PHP_SELF']) == 'laporan.php' ? 'bg-orange-50 text-orange-600 font-bold' : 'text-gray-600 hover:bg-gray-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm">
                <i class="fa-solid fa-file-invoice-dollar w-5"></i> Laporan Penjualan
            </a>
        <?php endif; ?>

        <?php if($role == 'kasir'): ?>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 mt-2 px-4">Operasional</p>
            
            <a href="transaksi.php" class="<?= basename($_SERVER['PHP_SELF']) == 'transaksi.php' ? 'bg-orange-600 text-white shadow-lg shadow-orange-200 font-bold' : 'text-gray-600 hover:bg-gray-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm">
                <i class="fa-solid fa-cash-register w-5"></i> Mesin Kasir
            </a>

            <a href="riwayat.php" class="<?= basename($_SERVER['PHP_SELF']) == 'riwayat.php' ? 'bg-orange-50 text-orange-600 font-bold' : 'text-gray-600 hover:bg-gray-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm">
                <i class="fa-solid fa-list-check w-5"></i> Riwayat Hari Ini
            </a>
        <?php endif; ?>

    </nav>

    <div class="p-4 border-t border-gray-100">
        <a href="logout.php" onclick="return confirm('Keluar dari sistem?')" class="flex items-center justify-center gap-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white py-3 rounded-xl font-bold transition-all duration-200 text-sm group">
            <i class="fa-solid fa-power-off transition-transform group-hover:scale-110"></i> Logout
        </a>
    </div>
</aside>