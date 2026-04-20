<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminPanel;
use App\Http\Controllers\WisataController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\EwaletController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\KategoriAssetController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\UserAssetController;
use App\Http\Controllers\DataAssetController;
use App\Http\Controllers\DetailTransaksiController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;


Route::get('/',[AuthenticatedSessionController::class,'create'])->middleware('guest');
//user management
Route::group(['middleware' =>['role:kasir|admin|manager|keuangan|operator']], function() {
   Route::get('dashboard',[DashboardController::class,'index'])->name('dashboard');
   Route::get('/transaksi/nota', [TransaksiController::class, 'nota'])->name('transaksi.nota');
   Route::get('/transaksi/nota/{id}', [TransaksiController::class, 'notab'])->name('transaksi.notab');
   Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
   Route::get('/profile/info', [ProfileController::class, 'info'])->name('profile.info');
   Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
   Route::get('/transaksiadmin/{id}/data', [DetailTransaksiController::class, 'detail'])->name('detailtransaksi.data');
   Route::get('/shift/data', [ShiftController::Class,'datahistori'])->name('shift.data');
   Route::get('/shiftdetail/{id}', [ShiftController::Class,'shiftdetail'])->name('shiftdetail');
   Route::get('/shiftnota', [ShiftController::Class,'shiftnota'])->name('shiftnota');
   Route::get('/historinota/{id}', [ShiftController::Class,'historinota'])->name('historinota');
   Route::resource('/transaksidetail', DetailTransaksiController::class);
});
Route::group(['middleware' =>['role:admin|manager|keuangan']], function() {
   Route::get('user',[AdminPanel::class,'index'])->name('user');
   Route::post('user.store',[AdminPanel::class,'store'])->name('user.store');
   Route::get('user.info',[AdminPanel::class,'info'])->name('user.info');
   Route::post('user.edit',[AdminPanel::class,'edit'])->name('user.edit');
   Route::post('user.hapus', [AdminPanel::Class,'hapus'])->name('user.hapus');
   Route::get('userrol',[AdminPanel::class,'userrol'])->name('userrol');
   Route::get('permission',[AdminPanel::class,'permission'])->name('permission');
   Route::resource('/transaksiadmin', TransaksiController::class);
   Route::get('/transaksiadmin/data', [TransaksiController::class, 'data'])->name('transaksiadmin.data');
   Route::get('/transaksitunai/data', [TransaksiController::class, 'tunai'])->name('transaksitunai.data');
   Route::get('/transaksibank/data', [TransaksiController::class, 'bank'])->name('transaksibank.data');
   Route::get('/transaksiewalet/data', [TransaksiController::class, 'ewalet'])->name('transaksiewalet.data');
   Route::get('/transaksiadmin/edit', [DetailTransaksiController::class, 'edits'])->name('transaksiadmin.edit');
   Route::post('/transaksiadmin/simpan', [TransaksiController::class, 'storeadmin'])->name('transaksiadmin.simpan');
   Route::get('/transaksiadmin/detail/{id}', [TransaksiController::class, 'detail'])->name('page.transaksidetail');
   Route::get('shift.index', [ShiftController::Class,'index'])->name('shift.index');
   Route::resource('/shift', ShiftController::class);
   Route::get('/shift/data/admin', [ShiftController::Class,'datahistoriadmin'])->name('shiftadmin.data');
   Route::get('transaksi.count', [TransaksiController::Class,'count'])->name('transaksi.count');
   Route::get('transaksi.pendapatan', [TransaksiController::Class,'pendapatan'])->name('transaksi.pendapatan');
   Route::get('/transaksidetail/loadformadmin/{diskon}/{total}', [DetailTransaksiController::class, 'loadFormadmin'])->name('transaksidetail.load_formadmin');
   Route::get('/penjualan/jenis_pembayaran', [PenjualanController::Class,'jenis_pembayaran'])->name('penjualan.jenis_pembayaran');
   Route::get('/penjualan/jenis_pembayaran/data', [PenjualanController::Class,'jenis_pembayarandata'])->name('penjualan.jenispembayarandata');
   Route::get('penjualan/count', [PenjualanController::Class,'count'])->name('penjualan.count');
   Route::get('kategori/count', [PenjualanController::Class,'kategori_count'])->name('kategori.count');
   Route::get('items/count', [PenjualanController::Class,'items_count'])->name('items.count');
   Route::get('penjualan/pendapatan', [PenjualanController::Class,'pendapatan'])->name('penjualan.pendapatan');
   Route::get('penjualan/trasaksi/tunai', [PenjualanController::Class,'transaksi_tunai'])->name('penjualan.transaksi_tunai');
   Route::get('penjualan/trasaksi/qris', [PenjualanController::Class,'transaksi_qris'])->name('penjualan.transaksi_qris');
   Route::get('penjualan/trasaksi/bank', [PenjualanController::Class,'transaksi_bank'])->name('penjualan.transaksi_bank');
   Route::get('penjualan/trasaksi/ewalet', [PenjualanController::Class,'transaksi_ewalet'])->name('penjualan.transaksi_ewalet');
   Route::get('penjualan/pendapatan/tunai', [PenjualanController::Class,'pendapatan_tunai'])->name('penjualan.pendapatan_tunai');
   Route::get('penjualan/pendapatan/qris', [PenjualanController::Class,'pendapatan_qris'])->name('penjualan.pendapatan_qris');
   Route::get('penjualan/pendapatan/bank', [PenjualanController::Class,'pendapatan_bank'])->name('penjualan.pendapatan_bank');
   Route::get('penjualan/pendapatan/ewalet', [PenjualanController::Class,'pendapatan_ewalet'])->name('penjualan.pendapatan_ewalet');
   Route::get('penjualan/jenis_pembayaran/tunai', [PenjualanController::Class,'tunai'])->name('penjualan.tunai');
   Route::get('penjualan/jenis_pembayaran/qris', [PenjualanController::Class,'qris'])->name('penjualan.qris');
   Route::get('penjualan/jenis_pembayaran/transfer_bank', [PenjualanController::Class,'bank'])->name('penjualan.bank');
   Route::get('penjualan/jenis_pembayaran/transfer_ewalet', [PenjualanController::Class,'ewalet'])->name('penjualan.ewalet');
   Route::get('penjualan/item', [PenjualanController::Class,'item'])->name('penjualan.item');
   Route::get('penjualan/item/data', [PenjualanController::Class,'data_item'])->name('penjualan.dataitem');
   Route::get('penjualan/kategori', [PenjualanController::Class,'kategori'])->name('penjualan.kategori');
   Route::get('penjualan/kategori/data', [PenjualanController::Class,'data_kategori'])->name('penjualan.datakategori');
   Route::get('penjualan/ringkasan', [PenjualanController::Class,'ringkasan'])->name('penjualan.ringkasan');
   Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
   Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
   Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');
   Route::get('/exportexcel', [TransaksiController::class, 'excel'])->name('export.excel');
   Route::get('chart-line-ajax', [DashboardController::class, 'chartLineAjax'])->name('chartLineAjax');
   
   
   Route::get('asset.index',[AssetController::class,'index'])->name('asset.index');
   Route::post('asset.store', [AssetController::Class,'store'])->name('asset.store');
   Route::post('asset.edits', [AssetController::Class,'edits'])->name('asset.edits');
   Route::post('asset.updates', [AssetController::Class,'updates'])->name('asset.updates');
   Route::post('asset.hapus', [AssetController::Class,'hapus'])->name('asset.hapus');

   Route::get('kategori_asset.index',[KategoriAssetController::class,'index'])->name('kategori_asset.index');
   Route::post('kategori_asset.store', [KategoriAssetController::Class,'store'])->name('kategori_asset.store');
   Route::post('kategori_asset.edits', [KategoriAssetController::Class,'edits'])->name('kategori_asset.edits');
   Route::post('kategori_asset.updates', [KategoriAssetController::Class,'updates'])->name('kategori_asset.updates');
   Route::post('kategori_asset.hapus', [KategoriAssetController::Class,'hapus'])->name('kategori_asset.hapus');

   Route::get('merk.index',[MerkController::class,'index'])->name('merk.index');
   Route::post('merk.store', [MerkController::Class,'store'])->name('merk.store');
   Route::post('merk.edits', [MerkController::Class,'edits'])->name('merk.edits');
   Route::post('merk.updates', [MerkController::Class,'updates'])->name('merk.updates');
   Route::post('merk.hapus', [MerkController::Class,'hapus'])->name('merk.hapus');
   Route::get('getkategori', [MerkController::class, 'getkategori']);

   Route::get('departemen.index',[DepartemenController::class,'index'])->name('departemen.index');
   Route::post('departemen.store', [DepartemenController::Class,'store'])->name('departemen.store');
   Route::post('departemen.edits', [DepartemenController::Class,'edits'])->name('departemen.edits');
   Route::post('departemen.updates', [DepartemenController::Class,'updates'])->name('departemen.updates');
   Route::post('departemen.hapus', [DepartemenController::Class,'hapus'])->name('departemen.hapus');

   Route::get('userasset.index',[UserAssetController::class,'index'])->name('userasset.index');
   Route::post('userasset.store', [UserAssetController::Class,'store'])->name('userasset.store');
   Route::post('userasset.edits', [UserAssetController::Class,'edits'])->name('userasset.edits');
   Route::post('userasset.updates', [UserAssetController::Class,'updates'])->name('userasset.updates');
   Route::post('userasset.hapus', [UserAssetController::Class,'hapus'])->name('userasset.hapus');

   Route::get('dataasset.index',[DataAssetController::class,'index'])->name('dataasset.index');
   Route::post('dataasset.store', [DataAssetController::Class,'store'])->name('dataasset.store');
   Route::post('dataasset.edits', [DataAssetController::Class,'edits'])->name('dataasset.edits');
   Route::post('dataasset.updates', [DataAssetController::Class,'updates'])->name('dataasset.updates');
   Route::post('dataasset.hapus', [DataAssetController::Class,'hapus'])->name('dataasset.hapus');
   Route::post('dataasset.view', [DataAssetController::Class,'view'])->name('dataasset.view');
   Route::get('getmerk', [DataAssetController::class, 'getmerk']);
   Route::get('getkategoris', [DataAssetController::class, 'getkategoris']);
   
});

Route::group(['middleware' =>['role:operator|admin']], function() {
   Route::get('wisata.index',[WisataController::class,'index'])->name('wisata.index');
   Route::post('wisata.store', [WisataController::Class,'store'])->name('wisata.store');
   Route::post('wisata.edits', [WisataController::Class,'edits'])->name('wisata.edits');
   Route::post('wisata.updates', [WisataController::Class,'updates'])->name('wisata.updates');
   Route::post('wisata.hapus', [WisataController::Class,'hapus'])->name('wisata.hapus');
   Route::get('kategori.index',[KategoriController::class,'index'])->name('kategori.index');
   Route::post('kategori.store', [KategoriController::Class,'store'])->name('kategori.store');
   Route::post('kategori.edits', [KategoriController::Class,'edits'])->name('kategori.edits');
   Route::post('kategori.updates', [KategoriController::Class,'updates'])->name('kategori.updates');
   Route::post('kategori.hapus', [KategoriController::Class,'hapus'])->name('kategori.hapus');
   Route::get('/item/data', [ItemController::class, 'data'])->name('item.data');
   Route::resource('/item', ItemController::class);
   Route::get('/promo/data', [PromoController::class, 'data'])->name('promo.data');
   Route::resource('/promo', PromoController::class);
   Route::get('getitem', [ItemController::class, 'getItem']);
   Route::get('bank.index',[BankController::class,'index'])->name('bank.index');
   Route::post('bank.store', [BankController::Class,'store'])->name('bank.store');
   Route::post('bank.edits', [BankController::Class,'edits'])->name('bank.edits');
   Route::post('bank.updates', [BankController::Class,'updates'])->name('bank.updates');
   Route::post('bank.hapus', [BankController::Class,'hapus'])->name('bank.hapus');
   Route::get('ewalet.index',[EwaletController::class,'index'])->name('ewalet.index');
   Route::post('ewalet.store', [EwaletController::Class,'store'])->name('ewalet.store');
   Route::post('ewalet.edits', [EwaletController::Class,'edits'])->name('ewalet.edits');
   Route::post('ewalet.updates', [EwaletController::Class,'updates'])->name('ewalet.updates');
   Route::post('ewalet.hapus', [EwaletController::Class,'hapus'])->name('ewalet.hapus');
});

Route::group(['middleware' =>['role:kasir']], function() {
   Route::get('getbank', [BankController::class, 'getbank']);
   Route::get('getewalet', [EwaletController::class, 'getewalet']);
   Route::get('/transaksi/baru', [TransaksiController::class, 'create'])->name('transaksi.baru');
   Route::get('/transaksi/selesai', [TransaksiController::class, 'selesai'])->name('transaksi.selesai');
   Route::get('/transaksi/selesai/{id}', [TransaksiController::class, 'selesaib'])->name('transaksi.selesaib');
   Route::get('/transaksi/{id}/data', [DetailTransaksiController::class, 'data'])->name('transaksi.data');
   Route::get('/transaksi/loadform/{diskon}/{total}/{diterima}', [DetailTransaksiController::class, 'loadForm'])->name('transaksi.load_form');
   Route::post('/transaksi/simpan', [TransaksiController::class, 'store'])->name('transaksi.simpan');
   Route::get('/transaksi/belum/{id}', [TransaksiController::class, 'belum'])->name('page.transaksibelum');
   Route::get('/transaksi/belumselesai', [TransaksiController::class, 'belumselesai'])->name('belumselesai.index');
   Route::get('/transaksi/belum/{id}/data', [DetailTransaksiController::class, 'detail'])->name('belumtransaksi.data');
   Route::get('/transaksi/hapus', [TransaksiController::class, 'hapusbelum'])->name('transaksihapus');
   Route::get('/shiftkasir/index', [ShiftController::Class,'shiftkasir'])->name('shiftkasir.index');
   Route::get('/shiftbaru', [ShiftController::Class,'shiftbaru'])->name('shiftbaru');
   Route::get('/shiftakhir', [ShiftController::Class,'shiftakhir'])->name('shiftakhir');
   Route::get('/shiftsaatini', [ShiftController::Class,'shiftsaatini'])->name('shiftsaatini');
   Route::get('/shiftberakhir', [ShiftController::Class,'shiftberakhir'])->name('shiftberakhir');
   Route::post('/shift/store', [ShiftController::Class,'store'])->name('shift.store');
   Route::post('/shift/update', [ShiftController::Class,'update'])->name('shift.update');
   Route::get('/historishift', [ShiftController::Class,'historishift'])->name('historishift');
   Route::get('/aktivitas', [ShiftController::Class,'aktivitas'])->name('aktivitas.index');
   Route::get('/aktivitas/data', [ShiftController::class, 'data'])->name('aktivitas.data');
   Route::resource('/transaksikasir', TransaksiController::class);
   Route::get('/transaksikasir/detail/{id}', [TransaksiController::class, 'detail'])->name('transaksidetail');
   Route::resource('/transaksi', DetailTransaksiController::class)
   ->except('create', 'show', 'edit');
});


require __DIR__.'/auth.php';
