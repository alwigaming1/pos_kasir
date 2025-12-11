<?php
// Aktifkan Error Reporting (Opsional, matikan saat produksi)
error_reporting(E_ALL);
ini_set('display_errors', 0);

include 'config.php';
cek_login();

// Validasi Admin
if($_SESSION['role'] != 'admin'){ header("Location: transaksi.php"); exit; }

// --- 1. LOGIK BACKUP (Hanya jalan jika tombol ditekan) ---
if(isset($_POST['backup_now'])){
    
    // Config DB untuk fungsi backup
    $host = $db_host; // Dari config.php (pastikan variabel $db_host, $db_user dll tersedia)
    $user = $db_user;
    $pass = $db_pass;
    $name = $db_name;
    
    $mysqli = new mysqli($host, $user, $pass, $name);
    $mysqli->select_db($name);
    $mysqli->query("SET NAMES 'utf8'");

    $queryTables = $mysqli->query('SHOW TABLES');
    while($row = $queryTables->fetch_row()){ $target_tables[] = $row[0]; }
    
    $content = "-- BACKUP SYSTEM: ".$conf['app_name']."\n";
    $content .= "-- GENERATED: ".date("d-m-Y H:i:s")."\n";
    $content .= "-- BY ADMIN: ".$_SESSION['nama']."\n\n";

    foreach($target_tables as $table){
        $result = $mysqli->query('SELECT * FROM '.$table);
        $fields_amount = $result->field_count;
        $rows_num = $mysqli->affected_rows;
        $res = $mysqli->query('SHOW CREATE TABLE '.$table);
        $TableMLine = $res->fetch_row();
        $content .= "\n\n".$TableMLine[1].";\n\n";

        for ($i = 0, $st_counter = 0; $i < $fields_amount; $i++, $st_counter=0) {
            while($row = $result->fetch_row()){
                if ($st_counter%100 == 0 || $st_counter == 0 ) { $content .= "\nINSERT INTO ".$table." VALUES"; }
                $content .= "\n(";
                for($j=0; $j<$fields_amount; $j++){
                    $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) );
                    if (isset($row[$j])) { $content .= '"'.$row[$j].'"' ; } else { $content .= '""'; }
                    if ($j<($fields_amount-1)) { $content.= ','; }
                }
                $content .=")";
                if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) { $content .= ";"; } else { $content .= ","; }
                $st_counter=$st_counter+1;
            }
        } $content .="\n\n\n";
    }
    
    $backup_name = "backup_".date("Ymd_His").".sql";
    ob_clean(); flush();
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary"); 
    header("Content-disposition: attachment; filename=\"".$backup_name."\""); 
    echo $content; exit;
}

// --- 2. LOGIK STATISTIK DB (Untuk Tampilan) ---
// Hitung Ukuran Database
$size = 0;
$res = mysqli_query($koneksi, "SHOW TABLE STATUS");
$table_count = 0;
while($row = mysqli_fetch_assoc($res)) {
    $size += $row["Data_length"] + $row["Index_length"];
    $table_count++;
}
// Konversi ke KB/MB
$size_text = ($size > 1024 * 1024) ? round($size / 1024 / 1024, 2) . " MB" : round($size / 1024, 2) . " KB";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Backup Data - <?= $conf['app_name'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800">
    
    <?php include 'sidebar.php'; ?>
    
    <div class="md:ml-64 p-4 md:p-8 pt-20 md:pt-8 transition-all duration-300">
        
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Backup & Maintenance</h1>
            <p class="text-slate-500 text-sm">Amankan data penjualan Anda secara berkala.</p>
        </div>

        <div class="max-w-4xl mx-auto">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl">
                        <i class="fa-solid fa-database"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase">Total Size</p>
                        <p class="text-xl font-extrabold text-slate-800"><?= $size_text ?></p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl">
                        <i class="fa-solid fa-table"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase">Total Tabel</p>
                        <p class="text-xl font-extrabold text-slate-800"><?= $table_count ?> <span class="text-sm font-medium text-slate-400">Tabel</span></p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-xl">
                        <i class="fa-solid fa-server"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase">Status Server</p>
                        <p class="text-xl font-extrabold text-green-600">Online</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100">
                <div class="p-8 text-center md:text-left flex flex-col md:flex-row items-center justify-between gap-8">
                    
                    <div class="max-w-lg">
                        <div class="inline-flex items-center gap-2 bg-orange-50 text-orange-600 px-3 py-1 rounded-full text-xs font-bold mb-4 border border-orange-100">
                            <i class="fa-solid fa-shield-halved"></i> Fitur Keamanan
                        </div>
                        <h2 class="text-2xl font-bold text-slate-800 mb-2">Download Backup Data (.SQL)</h2>
                        <p class="text-slate-500 leading-relaxed text-sm">
                            Sistem akan mengunduh seluruh data transaksi, produk, dan user dalam satu file SQL. 
                            Simpan file ini di tempat aman (Google Drive/Harddisk Eksternal) untuk memulihkan data jika terjadi kerusakan perangkat.
                        </p>
                    </div>

                    <div class="flex flex-col items-center gap-2">
                        <form method="POST">
                            <button type="submit" name="backup_now" class="bg-slate-900 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:bg-orange-600 transition shadow-xl shadow-slate-900/20 flex items-center gap-3 group">
                                <i class="fa-solid fa-cloud-arrow-down text-2xl group-hover:animate-bounce"></i>
                                <span>Download Backup</span>
                            </button>
                        </form>
                        <p class="text-[10px] text-slate-400 font-medium">Format file: .sql (MySQL)</p>
                    </div>

                </div>
                
                <div class="bg-slate-50 p-4 text-center text-xs text-slate-400 border-t border-slate-100">
                    <i class="fa-solid fa-circle-info mr-1"></i> Disarankan melakukan backup setiap hari setelah toko tutup.
                </div>
            </div>

        </div>
    </div>

</body>
</html>