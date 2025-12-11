<?php 
include 'config.php'; 
cek_login(); 

// CONFIG
$set_pajak = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT pajak_persen FROM pengaturan WHERE id=1"));
$pajak_persen = $set_pajak['pajak_persen'] ?? 0;

if(!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

// --- LOGIC PHP ---
if(isset($_GET['add'])){
    $id = (int)$_GET['add'];
    $cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT stok, nama_produk, harga, gambar, kategori FROM produk WHERE id='$id'"));
    if($cek && $cek['stok'] > 0){
        $found = false; $key=-1;
        foreach($_SESSION['cart'] as $k=>$v){ if($v['id']==$id){ $found=true; $key=$k; break; } }
        if($found && ($_SESSION['cart'][$key]['qty']+1) > $cek['stok']){
            $_SESSION['swal']=['type'=>'error','title'=>'Stok Habis','text'=>'Sisa: '.$cek['stok']];
        } else {
            if($found) $_SESSION['cart'][$key]['qty']++; 
            else $_SESSION['cart'][]=['id'=>$id,'nama'=>$cek['nama_produk'],'harga'=>$cek['harga'],'qty'=>1,'img'=>$cek['gambar'],'kategori'=>$cek['kategori']];
        }
    } else { $_SESSION['swal']=['type'=>'error','title'=>'Stok Kosong','text'=>'Habis']; }
    header("Location: transaksi.php"); exit;
}
if(isset($_GET['min'])){
    $id = (int)$_GET['min'];
    foreach($_SESSION['cart'] as $k=>$v){ if($v['id']==$id){ if($v['qty']>1) $_SESSION['cart'][$k]['qty']--; else unset($_SESSION['cart'][$k]); } }
    $_SESSION['cart']=array_values($_SESSION['cart']); header("Location: transaksi.php"); exit;
}
if(isset($_GET['reset'])){ $_SESSION['cart']=[]; header("Location: transaksi.php"); exit; }

if(isset($_POST['bayar'])){
    foreach($_SESSION['cart'] as $c){
        $s=mysqli_fetch_array(mysqli_query($koneksi,"SELECT stok FROM produk WHERE id='$c[id]'"));
        if($s['stok']<$c['qty']){ $_SESSION['swal']=['type'=>'error','title'=>'Gagal','text'=>'Stok berubah']; header("Location: transaksi.php"); exit; }
    }
    $sub=$_POST['total_kotor']; $disc=$_POST['diskon']; $uang=$_POST['uang']; $plg=$_POST['pelanggan']?:'Umum'; $tipe=$_POST['tipe_order']; $met=$_POST['metode_bayar'];
    $pot=($sub*$disc)/100; $pjk=(($sub-$pot)*$pajak_persen)/100; $tot=($sub-$pot)+$pjk;
    
    if($uang<floor($tot)){ $_SESSION['swal']=['type'=>'error','title'=>'Kurang','text'=>'Uang Kurang']; header("Location: transaksi.php"); exit; }
    
    $inv="INV-".date("ymdHis"); $tgl=date("Y-m-d H:i:s"); $kmb=$uang-$tot;
    mysqli_query($koneksi,"INSERT INTO transaksi VALUES(NULL,'$inv','$plg','$tipe','$tgl','$tot','$pot','$pjk','$uang','$kmb','$met','$_SESSION[user_id]')");
    $id=mysqli_insert_id($koneksi);
    foreach($_SESSION['cart'] as $c){
        mysqli_query($koneksi,"INSERT INTO transaksi_detail VALUES(NULL,'$id','$c[id]','$c[harga]','$c[qty]','".($c['harga']*$c['qty'])."')");
        mysqli_query($koneksi,"UPDATE produk SET stok=stok-$c[qty] WHERE id='$c[id]'");
    }
    $_SESSION['cart']=[]; header("Location: struk.php?id=$id"); exit;
}
$total_php=0; $qty_php=0; foreach($_SESSION['cart'] as $c){ $total_php+=($c['harga']*$c['qty']); $qty_php+=$c['qty']; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kasir - <?= $conf['app_name'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }
        .no-scroll::-webkit-scrollbar { display: none; }
        .glass-header { background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(10px); }
        .item-shadow { box-shadow: 0 2px 10px rgba(0,0,0,0.03); }
        .btn-press:active { transform: scale(0.95); transition: transform 0.1s; }
        
        /* Radio Custom Orange */
        .radio-box:checked + div { background-color: #ea580c; color: white; border-color: #ea580c; }
        .cat-active { background-color: #ea580c; color: white; border-color: #ea580c; box-shadow: 0 4px 12px rgba(234, 88, 12, 0.3); }
    </style>
</head>
<body class="bg-[#F8F9FA] h-screen w-full overflow-hidden flex flex-col lg:flex-row" 
      x-data="{ 
          mobileCart: false, sub: <?= $total_php ?>, disc: 0, tax_p: <?= $pajak_persen ?>, pay: '', met: 'Tunai',
          get pot() { return Math.round(this.sub * (this.disc / 100)); },
          get tax() { return Math.round((this.sub - this.pot) * (this.tax_p / 100)); },
          get tot() { return (this.sub - this.pot) + this.tax; },
          get kmb() { return (this.pay - this.tot) > 0 ? (this.pay - this.tot) : 0; }
      }">

    <audio id="beep" src="https://assets.mixkit.co/active_storage/sfx/2578/2578-preview.mp3"></audio>
    <audio id="ok" src="https://assets.mixkit.co/active_storage/sfx/1435/1435-preview.mp3"></audio>

    <div class="flex-1 flex flex-col h-full min-w-0 relative">
        
        <header class="h-16 glass-header border-b border-gray-100 px-4 md:px-6 flex justify-between items-center z-20 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-orange-200">
                    <i class="fa-solid fa-mug-hot"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-slate-800 leading-tight">CafePOS</h1>
                    <p class="text-[10px] font-bold text-orange-500 uppercase tracking-widest" id="clock">--:--</p>
                </div>
            </div>
            
            <div class="hidden md:block relative w-72">
                <i class="fa-solid fa-search absolute left-4 top-3 text-gray-400 text-sm"></i>
                <input type="text" id="search" onkeyup="filter()" placeholder="Cari menu..." class="w-full bg-gray-50 border-none rounded-full py-2.5 pl-10 pr-4 text-sm font-medium focus:ring-2 focus:ring-orange-500 transition">
            </div>

            <div class="flex gap-2">
                <?php if($_SESSION['role']=='admin'): ?>
                <a href="dashboard.php" class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center text-slate-500 hover:text-orange-600 transition shadow-sm"><i class="fa-solid fa-chart-pie"></i></a>
                <?php endif; ?>
                <a href="logout.php" onclick="return confirm('Logout?')" class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center text-red-500 hover:bg-red-50 transition shadow-sm"><i class="fa-solid fa-power-off"></i></a>
            </div>
        </header>

        <div class="bg-white px-4 md:px-6 py-3 shrink-0 border-b border-gray-100 z-10">
            <div class="flex gap-2 overflow-x-auto no-scroll pb-2">
                <button onclick="fCat('all')" class="cat-btn cat-active px-5 py-2 rounded-full text-xs font-bold transition btn-press border border-transparent">Semua</button>
                <button onclick="fCat('Makanan')" class="cat-btn px-5 py-2 bg-white text-slate-500 border border-gray-200 rounded-full text-xs font-bold hover:border-orange-500 hover:text-orange-600 transition btn-press">Makanan</button>
                <button onclick="fCat('Minuman')" class="cat-btn px-5 py-2 bg-white text-slate-500 border border-gray-200 rounded-full text-xs font-bold hover:border-orange-500 hover:text-orange-600 transition btn-press">Minuman</button>
                <button onclick="fCat('Snack')" class="cat-btn px-5 py-2 bg-white text-slate-500 border border-gray-200 rounded-full text-xs font-bold hover:border-orange-500 hover:text-orange-600 transition btn-press">Snack</button>
            </div>
            <div class="flex gap-2 overflow-x-auto no-scroll items-center mt-1">
                <i class="fa-solid fa-filter text-orange-400 text-xs mr-1"></i>
                <button onclick="fTag('kopi')" class="tag-btn px-3 py-1 bg-orange-50 text-orange-700 border border-orange-100 rounded-lg text-[10px] font-bold hover:bg-orange-600 hover:text-white transition btn-press">Kopi</button>
                <button onclick="fTag('susu')" class="tag-btn px-3 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded-lg text-[10px] font-bold hover:bg-blue-600 hover:text-white transition btn-press">Susu</button>
                <button onclick="fTag('coklat')" class="tag-btn px-3 py-1 bg-amber-50 text-amber-700 border border-amber-100 rounded-lg text-[10px] font-bold hover:bg-amber-600 hover:text-white transition btn-press">Coklat</button>
                <div class="w-px h-4 bg-gray-200 mx-1"></div>
                <button onclick="fTag('hot')" class="tag-btn px-3 py-1 bg-red-50 text-red-600 border border-red-100 rounded-lg text-[10px] font-bold hover:bg-red-500 hover:text-white transition btn-press"><i class="fa-solid fa-fire mr-1"></i>Hot</button>
                <button onclick="fTag('ice')" class="tag-btn px-3 py-1 bg-cyan-50 text-cyan-600 border border-cyan-100 rounded-lg text-[10px] font-bold hover:bg-cyan-500 hover:text-white transition btn-press"><i class="fa-regular fa-snowflake mr-1"></i>Ice</button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 md:p-6 pb-24 lg:pb-6 no-scroll">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-4">
                <?php 
                $menu = mysqli_query($koneksi, "SELECT * FROM produk WHERE stok > 0 ORDER BY nama_produk ASC"); 
                while($m = mysqli_fetch_array($menu)){ 
                    $img = !empty($m['gambar']) ? "assets/".$m['gambar'] : "https://placehold.co/300x300/f1f5f9/94a3b8?text=NO+IMG";
                ?>
                <div onclick="window.location='transaksi.php?add=<?= $m['id'] ?>'" 
                     class="item-card group bg-white rounded-2xl p-2 border border-transparent item-shadow cursor-pointer hover:border-orange-400 transition-all duration-200 relative flex flex-col h-full btn-press"
                     data-name="<?= strtolower($m['nama_produk']) ?>" 
                     data-cat="<?= $m['kategori'] ?>"
                     data-tags="<?= strtolower($m['tags']) ?>">
                    
                    <div class="aspect-square w-full rounded-xl overflow-hidden bg-gray-100 relative">
                        <img src="<?= $img ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/60 to-transparent">
                            <span class="text-white text-[10px] font-bold">Stok: <?= $m['stok'] ?></span>
                        </div>
                    </div>

                    <div class="pt-3 px-1 flex flex-col flex-1">
                        <h4 class="font-bold text-slate-800 text-xs md:text-sm leading-tight line-clamp-2 mb-1"><?= $m['nama_produk'] ?></h4>
                        <div class="mt-auto flex justify-between items-center">
                            <span class="font-extrabold text-orange-600 text-xs md:text-sm">Rp <?= number_format($m['harga']/1000,0) ?>K</span>
                            <div class="w-6 h-6 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition">
                                <i class="fa-solid fa-plus text-[10px]"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="fixed inset-0 z-50 lg:static lg:inset-auto lg:z-auto w-full lg:w-[360px] flex flex-col bg-white border-l border-gray-200 shadow-2xl lg:shadow-none" 
         :class="mobileCart ? 'flex' : 'hidden lg:flex'">
        
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm lg:hidden" @click="mobileCart = false"></div>

        <div class="relative w-full h-full bg-white flex flex-col z-10">
            
            <div class="h-14 px-5 flex items-center justify-between bg-orange-600 text-white shrink-0 shadow-md">
                <div class="flex items-center gap-3">
                    <button @click="mobileCart=false" class="lg:hidden text-white/80 hover:text-white"><i class="fa-solid fa-chevron-down"></i></button>
                    <span class="font-bold text-base flex items-center gap-2">
                        <i class="fa-solid fa-receipt opacity-80"></i> Pesanan <span class="bg-white text-orange-600 text-xs px-1.5 rounded-md font-extrabold"><?= count($_SESSION['cart']) ?></span>
                    </span>
                </div>
                <a href="transaksi.php?reset=true" onclick="return confirm('Hapus semua?')" class="text-xs font-bold text-white/80 hover:text-white hover:bg-orange-700 px-2 py-1 rounded transition">RESET</a>
            </div>

            <div class="flex-1 overflow-y-auto p-3 space-y-1 bg-slate-50/50">
                <?php if(empty($_SESSION['cart'])): ?>
                    <div class="flex flex-col items-center justify-center h-48 text-gray-300">
                        <i class="fa-solid fa-basket-shopping text-3xl mb-2 text-gray-200"></i>
                        <p class="text-xs">Keranjang kosong</p>
                    </div>
                <?php endif; ?>

                <?php foreach($_SESSION['cart'] as $c): ?>
                <div class="flex items-center gap-3 p-2 bg-white rounded-lg border border-gray-100 shadow-sm relative group">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden shrink-0">
                        <?php $imgCart = !empty($c['img']) ? "assets/".$c['img'] : "https://placehold.co/100"; ?>
                        <img src="<?= $imgCart ?>" class="w-full h-full object-cover">
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <h4 class="font-bold text-xs text-slate-700 truncate w-32"><?= $c['nama'] ?></h4>
                            <span class="font-bold text-xs text-slate-900">Rp <?= number_format($c['harga']*$c['qty']) ?></span>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-[10px] text-gray-400">@ <?= number_format($c['harga']) ?></p>
                            <div class="flex items-center bg-gray-50 rounded border border-gray-200 h-5">
                                <button onclick="window.location='transaksi.php?min=<?= $c['id'] ?>'" class="w-5 h-full flex items-center justify-center text-gray-400 hover:text-red-500 rounded-l hover:bg-red-50"><i class="fa-solid fa-minus text-[8px]"></i></button>
                                <span class="text-[10px] font-bold w-5 text-center text-slate-700"><?= $c['qty'] ?></span>
                                <button onclick="window.location='transaksi.php?add=<?= $c['id'] ?>'" class="w-5 h-full flex items-center justify-center text-gray-400 hover:text-green-500 rounded-r hover:bg-green-50"><i class="fa-solid fa-plus text-[8px]"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="bg-white border-t border-gray-200 shadow-[0_-5px_30px_rgba(0,0,0,0.05)] shrink-0 z-20">
                <form method="POST" class="p-4 pt-3">
                    <input type="hidden" name="total_kotor" :value="sub">
                    
                    <div class="flex gap-2 mb-2">
                        <div class="relative flex-1">
                            <i class="fa-solid fa-user absolute left-2.5 top-2.5 text-gray-400 text-xs"></i>
                            <input type="text" name="pelanggan" class="w-full pl-7 pr-2 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold focus:ring-1 focus:ring-orange-500 outline-none" placeholder="Pelanggan">
                        </div>
                        <div class="flex bg-gray-100 p-0.5 rounded-lg w-32 shrink-0">
                            <label class="flex-1 cursor-pointer relative text-center"><input type="radio" name="tipe_order" value="Dine In" class="hidden radio-box" checked><div class="w-full h-full flex items-center justify-center rounded-md text-[9px] font-bold text-gray-500 transition border border-transparent">Dine In</div></label>
                            <label class="flex-1 cursor-pointer relative text-center"><input type="radio" name="tipe_order" value="Take Away" class="hidden radio-box"><div class="w-full h-full flex items-center justify-center rounded-md text-[9px] font-bold text-gray-500 transition border border-transparent">Take Away</div></label>
                        </div>
                    </div>

                    <div class="text-[11px] space-y-1 mb-3 pb-3 border-b border-dashed border-gray-200">
                        <div class="flex justify-between text-gray-500"><span>Subtotal</span> <span x-text="new Intl.NumberFormat('id-ID').format(sub)"></span></div>
                        <div class="flex justify-between text-gray-500" x-show="tax_p > 0"><span>Pajak (<?= $pajak_persen ?>%)</span> <span x-text="'+ ' + new Intl.NumberFormat('id-ID').format(tax)"></span></div>
                        <div class="flex justify-between items-center text-orange-600">
                            <span>Diskon (%)</span>
                            <input type="number" name="diskon" x-model="disc" class="w-8 text-right bg-transparent border-b border-orange-200 focus:border-orange-500 outline-none font-bold p-0 text-[11px]" placeholder="0">
                        </div>
                        <div class="flex justify-between text-base font-extrabold text-slate-800 pt-1"><span>Total</span> <span x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(tot)"></span></div>
                    </div>

                    <div class="flex gap-2 mb-2">
                        <label class="flex-1 cursor-pointer"><input type="radio" name="metode_bayar" value="Tunai" class="hidden radio-box" checked @click="met='Tunai'; pay=''"><div class="w-full py-2 flex items-center justify-center rounded-lg border border-gray-200 text-[10px] font-bold text-gray-600 transition hover:bg-gray-50">Tunai</div></label>
                        <label class="flex-1 cursor-pointer"><input type="radio" name="metode_bayar" value="QRIS" class="hidden radio-box" @click="met='QRIS'; pay=tot"><div class="w-full py-2 flex items-center justify-center rounded-lg border border-gray-200 text-[10px] font-bold text-gray-600 transition hover:bg-gray-50">QRIS</div></label>
                    </div>

                    <div x-show="met === 'Tunai'" class="relative mb-3">
                        <span class="absolute left-3 top-2.5 text-gray-400 text-xs font-bold">Rp</span>
                        <input type="number" name="uang" x-model="pay" class="w-full pl-9 pr-3 py-2 bg-white border border-gray-300 rounded-lg text-sm font-bold text-slate-800 focus:ring-2 focus:ring-orange-500 outline-none" placeholder="Nominal..">
                        
                        <div class="flex gap-1 mt-1 justify-between">
                            <button type="button" @click="pay=tot" class="flex-1 py-1 rounded bg-gray-100 text-[9px] font-bold hover:bg-orange-100 hover:text-orange-600 transition">PAS</button>
                            <button type="button" @click="pay=20000" class="flex-1 py-1 rounded bg-gray-100 text-[9px] font-bold hover:bg-orange-100 hover:text-orange-600 transition">20k</button>
                            <button type="button" @click="pay=50000" class="flex-1 py-1 rounded bg-gray-100 text-[9px] font-bold hover:bg-orange-100 hover:text-orange-600 transition">50k</button>
                            <button type="button" @click="pay=100000" class="flex-1 py-1 rounded bg-gray-100 text-[9px] font-bold hover:bg-orange-100 hover:text-orange-600 transition">100k</button>
                        </div>
                    </div>

                    <div class="text-center mb-3" x-show="met==='Tunai' && pay > tot">
                        <span class="text-xs font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full border border-green-100">Kembali: <span x-text="'Rp '+new Intl.NumberFormat('id-ID').format(kmb)"></span></span>
                    </div>

                    <button type="submit" name="bayar" 
                            :disabled="sub==0 || (met==='Tunai' && pay < tot)"
                            class="w-full bg-slate-900 text-white font-bold py-3.5 rounded-xl hover:bg-orange-600 disabled:bg-gray-300 disabled:cursor-not-allowed transition shadow-lg flex justify-center items-center gap-2 text-sm btn-press">
                        <i class="fa-solid fa-print"></i> <span>BAYAR SEKARANG</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="lg:hidden fixed bottom-4 left-4 right-4 z-40">
        <button @click="mobileCart=true" class="w-full bg-slate-900 text-white p-3.5 rounded-xl shadow-xl flex justify-between items-center animate-bounce-slow btn-press">
            <div class="flex items-center gap-2">
                <div class="bg-orange-500 w-6 h-6 rounded-full flex items-center justify-center font-bold text-[10px]"><?= $qty_php ?></div>
                <span class="font-bold text-xs">Keranjang</span>
            </div>
            <span class="font-extrabold text-sm">Rp <?= number_format($total_php/1000, 0) ?>K</span>
        </button>
    </div>

    <script>
        const beep = document.getElementById('beep');
        document.querySelectorAll('.item-card').forEach(i => i.addEventListener('click', () => { beep.currentTime=0; beep.play(); }));
        function fCat(cat) {
            document.querySelectorAll('.cat-btn').forEach(b => {
                if(b.innerText===cat || (cat==='all' && b.innerText==='Semua')) b.className="cat-btn px-5 py-2 bg-orange-600 text-white rounded-full text-xs font-bold shadow-lg shadow-orange-200 transition btn-press";
                else b.className="cat-btn px-5 py-2 bg-white text-slate-500 border border-gray-200 rounded-full text-xs font-bold hover:border-orange-500 hover:text-orange-600 transition btn-press";
            });
            document.querySelectorAll('.item-card').forEach(c => c.style.display = (cat==='all' || c.dataset.cat===cat)?'flex':'none');
        }
        function fTag(tag) {
            document.querySelectorAll('.tag-btn').forEach(b => {
                if(b.innerText.toLowerCase().includes(tag)) b.classList.add('bg-orange-600','text-white'); else b.classList.remove('bg-orange-600','text-white');
            });
            document.querySelectorAll('.item-card').forEach(c => c.style.display = (c.dataset.tags.includes(tag))?'flex':'none');
        }
        function filter() {
            let val = document.getElementById('search').value.toLowerCase();
            document.querySelectorAll('.item-card').forEach(c => c.style.display = c.dataset.name.includes(val)?'flex':'none');
        }
        function time() { document.getElementById('clock').innerText = new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'})+" WIB"; }
        setInterval(time,1000); time();
        <?php if(isset($_SESSION['swal'])): ?>
            Swal.fire({icon:'<?= $_SESSION['swal']['type'] ?>',title:'<?= $_SESSION['swal']['title'] ?>',text:'<?= $_SESSION['swal']['text'] ?>',timer:1500,showConfirmButton:false});
            <?php unset($_SESSION['swal']); ?>
        <?php endif; ?>
    </script>
</body>
</html>