<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>CafePOS - Modern Cashier</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-family: 'Space Grotesk', sans-serif; } </style>
</head>
<body class="bg-white text-slate-800">

    <nav class="container mx-auto px-6 py-8 flex justify-between items-center">
        <div class="flex items-center gap-2 font-bold text-2xl tracking-tighter">
            <div class="w-8 h-8 bg-orange-500 rounded-lg rotate-3"></div>
            CafePOS.
        </div>
        <div class="flex gap-4">
            <a href="login.php" class="font-bold text-slate-500 hover:text-orange-600 transition py-2">Masuk Staff</a>
            <a href="login.php" class="bg-slate-900 text-white px-6 py-2 rounded-full font-bold hover:bg-slate-800 transition shadow-lg hover:shadow-xl">
                Coba Demo
            </a>
        </div>
    </nav>

    <section class="container mx-auto px-6 py-12 lg:py-20">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            
            <div class="lg:w-1/2 space-y-8">
                <div class="inline-flex items-center gap-2 bg-orange-50 border border-orange-100 text-orange-600 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-widest">
                    <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                    Sistem Kasir v2.0
                </div>
                
                <h1 class="text-5xl lg:text-7xl font-bold leading-[1.1] tracking-tight">
                    Simpel. Cepat. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-500">Cuan Meningkat.</span>
                </h1>
                
                <p class="text-xl text-slate-500 leading-relaxed max-w-md">
                    Tinggalkan cara lama mencatat pesanan. Beralih ke sistem kasir digital yang dirancang khusus untuk kecepatan pelayanan kafe Anda.
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="login.php" class="bg-orange-500 text-white px-8 py-4 rounded-2xl font-bold text-lg text-center hover:bg-orange-600 transition shadow-xl shadow-orange-200">
                        Buka Kasir Sekarang
                    </a>
                    <div class="flex items-center gap-4 px-6 py-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="flex -space-x-2">
                            <img class="w-8 h-8 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=1">
                            <img class="w-8 h-8 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=2">
                            <img class="w-8 h-8 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=3">
                        </div>
                        <p class="text-xs font-bold text-slate-500">Digunakan 500+ Barista</p>
                    </div>
                </div>
            </div>

            <div class="lg:w-1/2 relative">
                <div class="absolute inset-0 bg-orange-400 rounded-full blur-[120px] opacity-20"></div>
                <div class="relative bg-slate-900 p-4 rounded-[2.5rem] shadow-2xl transform rotate-1 hover:rotate-0 transition duration-500 border-4 border-slate-800">
                    <img src="https://cdn.dribbble.com/users/62525/screenshots/16360424/media/3c2c47c7c34094a643c7263b86940a23.png?resize=1000x750&vertical=center" alt="App Interface" class="rounded-[2rem] w-full object-cover">
                    
                    <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl flex items-center gap-3 animate-bounce">
                        <div class="bg-green-100 p-3 rounded-xl text-green-600">
                            <i class="fa-solid fa-arrow-trend-up text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold">Total Omset</p>
                            <p class="font-bold text-slate-800">Rp 15.400.000</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="py-20 bg-slate-50">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition duration-300">
                    <i class="fa-solid fa-bolt text-4xl text-orange-500 mb-6"></i>
                    <h3 class="text-xl font-bold mb-3">Transaksi Kilat</h3>
                    <p class="text-slate-500 leading-relaxed">Antarmuka visual tanpa ribet. Tinggal klik gambar menu, pesanan langsung masuk keranjang.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition duration-300">
                    <i class="fa-solid fa-receipt text-4xl text-blue-500 mb-6"></i>
                    <h3 class="text-xl font-bold mb-3">Printer Thermal</h3>
                    <p class="text-slate-500 leading-relaxed">Kompatibel dengan berbagai printer bluetooth 58mm/80mm untuk cetak struk profesional.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-sm hover:shadow-xl transition duration-300">
                    <i class="fa-solid fa-chart-pie text-4xl text-purple-500 mb-6"></i>
                    <h3 class="text-xl font-bold mb-3">Laporan Otomatis</h3>
                    <p class="text-slate-500 leading-relaxed">Tidak perlu rekap manual. Sistem mencatat setiap rupiah yang masuk secara realtime.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-white py-10 text-center border-t border-gray-100">
        <p class="text-slate-400 text-sm">© 2024 CafePOS System. Built for Speed.</p>
    </footer>

</body>
</html>