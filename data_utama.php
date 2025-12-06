<?php 
include 'config.php'; 
cek_login(); 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Data Utama - <?= $conf['app_name'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-[#F3F4F6] text-slate-800">

    <?php include 'sidebar.php'; ?>

    <div class="ml-64 p-8 min-h-screen">
        
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Kelola Data Utama</h1>
                <p class="text-gray-500 text-sm">Manajemen data master aplikasi.</p>
            </div>
            
            <a href="#" class="bg-<?= $conf['color'] ?>-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-<?= $conf['color'] ?>-700 transition shadow-lg flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Data
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 bg-<?= $conf['color'] ?>-50 border-b border-<?= $conf['color'] ?>-100 flex justify-between items-center">
                <form class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-3 text-gray-400"></i>
                    <input type="text" placeholder="Cari data..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-<?= $conf['color'] ?>-200 text-sm">
                </form>
                <div class="flex gap-2">
                    <button class="bg-white border text-gray-600 px-3 py-1 rounded hover:bg-gray-50"><i class="fa-solid fa-print"></i> Print</button>
                    <button class="bg-white border text-gray-600 px-3 py-1 rounded hover:bg-gray-50"><i class="fa-solid fa-file-excel"></i> Export</button>
                </div>
            </div>

            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-bold">
                    <tr>
                        <th class="p-4 border-b">No</th>
                        <th class="p-4 border-b">Kolom 1</th>
                        <th class="p-4 border-b">Kolom 2</th>
                        <th class="p-4 border-b">Status</th>
                        <th class="p-4 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600">
                    <tr class="hover:bg-<?= $conf['color'] ?>-50/30 transition border-b">
                        <td class="p-4 font-bold">1</td>
                        <td class="p-4">Contoh Data A</td>
                        <td class="p-4">Deskripsi data disini...</td>
                        <td class="p-4">
                            <span class="bg-green-100 text-green-600 py-1 px-3 rounded-full text-xs font-bold">Aktif</span>
                        </td>
                        <td class="p-4 text-center">
                            <a href="#" class="text-blue-500 hover:text-blue-700 mx-1"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="#" class="text-red-500 hover:text-red-700 mx-1"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <div class="p-4 border-t flex justify-end">
                <div class="flex gap-1">
                    <button class="px-3 py-1 border rounded hover:bg-gray-50 text-sm">Prev</button>
                    <button class="px-3 py-1 bg-<?= $conf['color'] ?>-600 text-white rounded text-sm font-bold">1</button>
                    <button class="px-3 py-1 border rounded hover:bg-gray-50 text-sm">2</button>
                    <button class="px-3 py-1 border rounded hover:bg-gray-50 text-sm">Next</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>