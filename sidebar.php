<?php
// Ambil role dan nama halaman saat ini untuk penanda aktif
$role = $_SESSION['role'] ?? 'kasir'; 
$page = basename($_SERVER['PHP_SELF']);
?>

<button onclick="toggleSidebar()" class="md:hidden fixed top-4 right-4 z-50 bg-slate-900 text-white p-3 rounded-xl shadow-lg border border-slate-700 hover:bg-orange-600 transition active:scale-95">
    <i class="fa-solid fa-bars text-xl"></i>
</button>

<div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/60 z-40 hidden transition-opacity backdrop-blur-sm md:hidden"></div>

<aside id="sidebar" class="fixed top-0 left-0 z-50 h-screen w-64 bg-slate-900 text-white flex flex-col transition-transform duration-300 -translate-x-full md:translate-x-0 shadow-2xl border-r border-slate-800">
    
    <div class="h-20 flex items-center px-6 border-b border-slate-800 bg-slate-950 shrink-0">
        <div class="flex items-center gap-3 text-orange-500">
            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center text-white shadow-lg shadow-orange-900/50">
                <i class="fa-solid fa-mug-hot text-lg"></i>
            </div>
            <div>
                <h1 class="font-extrabold text-lg leading-none tracking-tight text-white">CafePOS<span class="text-orange-500">.</span></h1>
                <p class="text-[10px] font-medium text-slate-400 mt-1 uppercase tracking-wider">v2.0 Pro</p>
            </div>
        </div>
        <button onclick="toggleSidebar()" class="md:hidden ml-auto text-slate-400 hover:text-white">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 space-y-1 mt-4 custom-scrollbar pb-4">
        
        <?php if($role == 'admin'): ?>
            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 mt-2">Analytics</p>
            
            <a href="dashboard.php" class="<?= $page == 'dashboard.php' ? 'bg-orange-600 text-white shadow-lg shadow-orange-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?> flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm font-medium group">
                <i class="fa-solid fa-chart-pie w-5 text-center group-hover:scale-110 transition-transform"></i> Ringkasan
            </a>

            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 mt-6">Master Data</p>

            <a href="data_produk.php" class="<?= $page == 'data_produk.php' ? 'bg-orange-600 text-white shadow-lg shadow-orange-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?> flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm font-medium group">
                <i class="fa-solid fa-burger w-5 text-center group-hover:scale-110 transition-transform"></i> Menu & Stok
            </a>
            
            <a href="data_user.php" class="<?= $page == 'data_user.php' ? 'bg-orange-600 text-white shadow-lg shadow-orange-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?> flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm font-medium group">
                <i class="fa-solid fa-users-gear w-5 text-center group-hover:scale-110 transition-transform"></i> Kelola Staff
            </a>

            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 mt-6">Finance</p>

            <a href="riwayat.php" class="<?= $page == 'riwayat.php' ? 'bg-orange-600 text-white shadow-lg shadow-orange-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?> flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm font-medium group">
                <i class="fa-solid fa-clock-rotate-left w-5 text-center group-hover:scale-110 transition-transform"></i> Riwayat Transaksi
            </a>

            <a href="laporan.php" class="<?= $page == 'laporan.php' ? 'bg-orange-600 text-white shadow-lg shadow-orange-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?> flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm font-medium group">
                <i class="fa-solid fa-file-invoice-dollar w-5 text-center group-hover:scale-110 transition-transform"></i> Laporan Laba
            </a>

            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 mt-6">System</p>

            <a href="pengaturan.php" class="<?= $page == 'pengaturan.php' ? 'bg-orange-600 text-white shadow-lg shadow-orange-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?> flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm font-medium group">
                <i class="fa-solid fa-sliders w-5 text-center group-hover:scale-110 transition-transform"></i> Pengaturan Toko
            </a>

            <a href="backup.php" class="<?= $page == 'backup.php' ? 'bg-orange-600 text-white shadow-lg shadow-orange-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?> flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm font-medium group">
                <i class="fa-solid fa-database w-5 text-center group-hover:scale-110 transition-transform text-blue-500"></i> Backup Data
            </a>
        <?php endif; ?>

        <?php if($role == 'kasir'): ?>
            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 mt-2">Point of Sales</p>
            
            <a href="transaksi.php" class="<?= $page == 'transaksi.php' ? 'bg-orange-600 text-white shadow-lg shadow-orange-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?> flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm font-medium group">
                <i class="fa-solid fa-cash-register w-5 text-center group-hover:scale-110 transition-transform"></i> Mesin Kasir
            </a>

            <a href="riwayat.php" class="<?= $page == 'riwayat.php' ? 'bg-orange-600 text-white shadow-lg shadow-orange-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?> flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-sm font-medium group">
                <i class="fa-solid fa-list-check w-5 text-center group-hover:scale-110 transition-transform"></i> Transaksi Hari Ini
            </a>
        <?php endif; ?>

    </nav>

    <div class="bg-slate-950 border-t border-slate-800 shrink-0">
        
        <div class="p-4 pb-2">
            <div class="flex items-center gap-3 p-3 bg-slate-800/50 rounded-xl border border-slate-700/50 hover:bg-slate-800 transition cursor-default group">
                <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-slate-300 font-bold border-2 border-slate-600 group-hover:border-orange-500 transition">
                    <?= strtoupper(substr($_SESSION['nama'], 0, 1)) ?>
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate"><?= $_SESSION['nama'] ?></p>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider"><?= $role ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 pt-0">
            <a href="logout.php" onclick="return confirm('Keluar dari sistem?')" class="flex items-center justify-center gap-2 bg-slate-900 border border-slate-800 text-slate-400 hover:bg-red-600 hover:text-white hover:border-red-600 py-3 rounded-xl font-bold transition-all duration-200 text-sm group shadow-sm">
                <i class="fa-solid fa-power-off transition-transform group-hover:scale-110"></i> Log Out
            </a>
        </div>
    </div>

</aside>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        // Cek apakah sidebar sedang tersembunyi (translate-x-full)
        if (sidebar.classList.contains('-translate-x-full')) {
            // Jika tersembunyi -> Tampilkan
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        } else {
            // Jika tampil -> Sembunyikan
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    }
</script>