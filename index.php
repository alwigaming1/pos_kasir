<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title><?= $conf['app_name'] ?> - Solusi Kasir Pintar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; scroll-behavior: smooth; } 
        .blob { position: absolute; filter: blur(60px); z-index: -1; opacity: 0.5; }
        .text-gradient { background: linear-gradient(to right, #ea580c, #dc2626); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="bg-white text-slate-800 overflow-x-hidden">

    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 transition-all">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2 font-extrabold text-2xl tracking-tight text-slate-900">
                <div class="w-8 h-8 bg-orange-600 rounded-lg flex items-center justify-center text-white text-sm shadow-lg shadow-orange-200">
                    <i class="fa-solid fa-mug-hot"></i>
                </div>
                <?= $conf['app_name'] ?>.
            </div>
            <div class="hidden md:flex gap-8 text-sm font-bold text-slate-500">
                <a href="#fitur" class="hover:text-orange-600 transition">Fitur Unggulan</a>
                <a href="#harga" class="hover:text-orange-600 transition">Paket Harga</a>
                <a href="#testimoni" class="hover:text-orange-600 transition">Kata Mereka</a>
            </div>
            <a href="login.php" class="group bg-slate-900 text-white px-6 py-2.5 rounded-full font-bold text-sm hover:bg-orange-600 transition shadow-lg hover:shadow-orange-200 flex items-center gap-2">
                Login App <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
            </a>
        </div>
    </nav>

    <section class="pt-32 pb-20 px-6 relative overflow-hidden">
        <div class="blob bg-orange-200 w-96 h-96 rounded-full top-0 -left-20 animate-pulse"></div>
        <div class="blob bg-blue-200 w-80 h-80 rounded-full bottom-0 -right-20"></div>

        <div class="container mx-auto text-center max-w-5xl relative z-10">
            <div class="inline-flex items-center gap-2 bg-orange-50 border border-orange-100 text-orange-600 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-8 animate-bounce">
                <span class="w-2 h-2 rounded-full bg-orange-600"></span> Live Demo v2.0
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold leading-tight mb-8 text-slate-900">
                Kelola Bisnis Kafe Jadi <br>
                <span class="text-gradient">Lebih Cepat & Untung.</span>
            </h1>
            
            <p class="text-xl text-slate-500 mb-10 leading-relaxed max-w-2xl mx-auto">
                Tinggalkan cara lama mencatat pesanan. Gunakan sistem POS modern yang mencatat stok, transaksi, dan laporan keuangan secara otomatis.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="login.php" class="bg-orange-600 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:bg-orange-700 transition shadow-xl shadow-orange-200 hover:-translate-y-1">
                    Coba Gratis Sekarang
                </a>
                <a href="#fitur" class="bg-white text-slate-700 border border-gray-200 px-8 py-4 rounded-2xl font-bold text-lg hover:bg-gray-50 transition hover:-translate-y-1">
                    Pelajari Fitur
                </a>
            </div>
            
            
        </div>
    </section>

    <section id="fitur" class="py-24 bg-slate-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16 max-w-2xl mx-auto">
                <h2 class="text-3xl font-bold mb-4 text-slate-900">Kenapa Memilih Kami?</h2>
                <p class="text-slate-500 text-lg">Didesain khusus untuk kecepatan barista dan kasir, tanpa fitur ribet yang membingungkan.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition group">
                    <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 text-2xl mb-6 group-hover:rotate-6 transition">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Order Kilat</h3>
                    <p class="text-slate-500 leading-relaxed">Antarmuka layar sentuh yang responsif. Input pesanan pelanggan hanya dalam 3 detik.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition group">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 text-2xl mb-6 group-hover:rotate-6 transition">
                        <i class="fa-solid fa-print"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Support Printer</h3>
                    <p class="text-slate-500 leading-relaxed">Kompatibel dengan semua printer thermal Bluetooth/USB (58mm & 80mm) tanpa driver.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition group">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center text-green-600 text-2xl mb-6 group-hover:rotate-6 transition">
                        <i class="fa-solid fa-money-bill-trend-up"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Anti Rugi</h3>
                    <p class="text-slate-500 leading-relaxed">Sistem otomatis memotong stok bahan dan menghitung laporan laba rugi harian Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="harga" class="py-24 bg-white relative">
        <div class="container mx-auto px-6 max-w-5xl">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold mb-4 text-slate-900">Investasi Cerdas</h2>
                <p class="text-slate-500 text-lg">Mulai gratis, upgrade kapan saja saat bisnis Anda tumbuh.</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div class="p-10 border border-gray-200 rounded-[2.5rem] hover:border-orange-200 transition">
                    <h3 class="font-bold text-xl text-slate-500 mb-2">Starter UMKM</h3>
                    <p class="text-5xl font-extrabold mb-6 text-slate-800">Rp 0</p>
                    <ul class="space-y-4 mb-10 text-slate-600 font-medium">
                        <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-green-500"></i> Maksimal 30 Produk</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-green-500"></i> Laporan Transaksi Harian</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-green-500"></i> 1 User Kasir</li>
                    </ul>
                    <a href="login.php" class="block w-full py-4 bg-gray-100 text-slate-700 font-bold text-center rounded-2xl hover:bg-gray-200 transition">Mulai Gratis</a>
                </div>

                <div class="p-10 bg-slate-900 text-white rounded-[2.5rem] shadow-2xl relative overflow-hidden transform md:scale-105">
                    <div class="absolute top-0 right-0 bg-gradient-to-r from-orange-500 to-red-500 text-xs font-bold px-4 py-1.5 rounded-bl-2xl">PALING LARIS</div>
                    <h3 class="font-bold text-xl text-orange-400 mb-2">Cafe Owner</h3>
                    <p class="text-5xl font-extrabold mb-6">150rb <span class="text-lg font-medium text-gray-400">/bln</span></p>
                    <ul class="space-y-4 mb-10 text-gray-300 font-medium">
                        <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-orange-500"></i> <strong>Unlimited</strong> Produk & Transaksi</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-orange-500"></i> Export Laporan Excel/PDF</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-orange-500"></i> Manajemen Stok Bahan</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-orange-500"></i> Prioritas Support 24 Jam</li>
                    </ul>
                    <a href="login.php" class="block w-full py-4 bg-gradient-to-r from-orange-600 to-red-600 text-white font-bold text-center rounded-2xl hover:opacity-90 transition shadow-lg shadow-orange-900/50">
                        Ambil Promo Ini
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-50 border-t border-gray-200 pt-16 pb-10">
        <div class="container mx-auto px-6 text-center">
            <h2 class="font-extrabold text-2xl text-slate-900 mb-6"><?= $conf['app_name'] ?>.</h2>
            <div class="flex justify-center gap-8 mb-8">
                <a href="#" class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-slate-400 hover:text-orange-600 hover:shadow-lg transition"><i class="fa-brands fa-instagram text-xl"></i></a>
                <a href="#" class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-slate-400 hover:text-orange-600 hover:shadow-lg transition"><i class="fa-brands fa-whatsapp text-xl"></i></a>
                <a href="#" class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-slate-400 hover:text-orange-600 hover:shadow-lg transition"><i class="fa-brands fa-facebook text-xl"></i></a>
            </div>
            <p class="text-slate-400 text-sm font-medium">© <?= date('Y') ?> All Rights Reserved. Built for Business.</p>
        </div>
    </footer>

</body>
</html>