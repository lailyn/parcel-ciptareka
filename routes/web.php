<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PartnershipController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\UserTypeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SetoranPaketController;
use App\Http\Controllers\PengembalianSetoranController;
use App\Http\Controllers\SetoranManajemenController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(AuthController::class)->group(function(){    
	Route::get('/b4ckd00R', 'index')->name('login');
	Route::post('/login', 'login_action')->name('login.action');
	Route::get('/logout', 'logout')->name('logout');
	Route::get('/refresh_captcha', 'refresh_captcha')->name('refresh_captcha');    
	Route::get('/registerk4n', 'register_action')->name('register');    
	Route::get('/kirim_email', 'kirim_email')->name('kirim_email');    
});

// Route::get('dashboard', [DashboardController::class, 'dashboard/index'])->name('dashboard');

Route::middleware('auth')->group(function () {

	Route::controller(DashboardController::class)->name('dashboard.')->group(function(){
		Route::get('dashboard', 'index')->name('index');        
	});

	Route::controller(SettingController::class)->name('setting.')->prefix('setting')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(LevelController::class)->name('level.')->prefix('level')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});
	Route::controller(ProdukController::class)->name('produk.')->prefix('produk')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});
	Route::controller(PaketController::class)->name('paket.')->prefix('paket')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::get('/addDetail/{id}', 'addDetail')->name('addDetail');
		Route::post('/saveDetail/{id}', 'saveDetail')->name('saveDetail');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
		Route::get('/deleteDetail/{id}/{ids}', 'deleteDetail')->name('deleteDetail');
	});
	Route::controller(PeriodeController::class)->name('periode.')->prefix('periode')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});
	Route::controller(MemberController::class)->name('member.')->prefix('member')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/import', 'import')->name('import');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::post('/importPost', 'importPost')->name('importPost');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
		Route::get('/jadiPartner/{id}', 'jadiPartner')->name('jadiPartner');
		Route::get('/pilihPaket/{id}', 'pilihPaket')->name('pilihPaket');		
		Route::get('/download', 'download')->name('download');		
		Route::post('/saveDetail/{id}', 'saveDetail')->name('saveDetail');
		Route::get('/deleteDetail/{id}/{ids}', 'deleteDetail')->name('deleteDetail');		
	});
	Route::controller(PartnershipController::class)->name('partnership.')->prefix('partnership')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');	
		Route::get('/akun/{id}', 'akun')->name('akun');	
	});

	Route::controller(KaryawanController::class)->name('karyawan.')->prefix('karyawan')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::post('/saveCakupan', 'saveCakupan')->name('saveCakupan');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::get('/akun/{id}', 'akun')->name('akun');
		Route::get('/cakupan/{id}', 'cakupan')->name('cakupan');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
		Route::get('/delCakupan/{id}', 'delCakupan')->name('delCakupan');
	});
	Route::controller(SetoranPaketController::class)->name('setoranPaket.')->prefix('setoranPaket')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');		
		Route::get('/edit/{id}', 'edit')->name('edit');		
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');		
		Route::get('/submit/{id}', 'submit')->name('submit');		
	});
	Route::controller(PengembalianSetoranController::class)->name('pengembalianSetoran.')->prefix('pengembalianSetoran')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');		
		Route::get('/edit/{id}', 'edit')->name('edit');		
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');		
		Route::get('/submit/{id}', 'submit')->name('submit');		
	});
	Route::controller(SetoranManajemenController::class)->name('setoranManajemen.')->prefix('setoranManajemen')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');		
		Route::get('/edit/{id}', 'edit')->name('edit');		
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');		
		Route::get('/submit/{id}', 'submit')->name('submit');		
	});



	Route::controller(NegaraController::class)->name('negara.')->prefix('negara')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(SatuanPaketController::class)->name('satuan_paket.')->prefix('satuan_paket')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});
	
	Route::controller(SatuanBeratController::class)->name('satuan_berat.')->prefix('satuan_berat')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(JenisBarangController::class)->name('jenis_barang.')->prefix('jenis_barang')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	

	Route::controller(JenisTarifController::class)->name('jenis_tarif.')->prefix('jenis_tarif')->group(function(){		
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(JenisMetodeController::class)->name('jenis_metode.')->prefix('jenis_metode')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(TarifController::class)->name('tarif.')->prefix('tarif')->group(function(){
		Route::get('/history', 'history')->name('history');
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
		Route::get('/nonaktif/{id}', 'nonaktif')->name('nonaktif');
	});

	Route::controller(MetodeBayarController::class)->name('metode_bayar.')->prefix('metode_bayar')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(JabatanKaryawanController::class)->name('jabatan_karyawan.')->prefix('jabatan_karyawan')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(DepartemenController::class)->name('departemen.')->prefix('departemen')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	

	Route::controller(JenisKendaraanController::class)->name('jenis_kendaraan.')->prefix('jenis_kendaraan')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(KendaraanDanPlatController::class)->name('kendaraan_dan_plat.')->prefix('kendaraan_dan_plat')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(SupplierController::class)->name('supplier.')->prefix('supplier')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(OutletController::class)->name('outlet.')->prefix('outlet')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(JadwalPickupController::class)->name('jadwal_pickup.')->prefix('jadwal_pickup')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(UserTypeController::class)->name('user_type.')->prefix('user_type')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/access/{id}', 'access')->name('access');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::post('/updateAccess/{id}', 'updateAccess')->name('updateAccess');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(UsersController::class)->name('users.')->prefix('users')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(JenisPaketController::class)->name('jenis_paket.')->prefix('jenis_paket')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(ProvinsiController::class)->name('provinsi.')->prefix('provinsi')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::post('/provinsi', 'provinsi')->name('provinsi');
		Route::post('/kab', 'kab')->name('kab');
		Route::post('/kec', 'kec')->name('kec');
		Route::post('/kel', 'kel')->name('kel');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(KabupatenController::class)->name('kabupaten.')->prefix('kabupaten')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(KecamatanController::class)->name('kecamatan.')->prefix('kecamatan')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(KelurahanController::class)->name('kelurahan.')->prefix('kelurahan')->group(function(){
		Route::get('/list', 'list')->name('list');
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});


	Route::controller(PengirimanController::class)->name('pengiriman.')->prefix('pengiriman')->group(function(){
		Route::get('/lacak', 'lacak')->name('lacak');
		Route::get('/dataAjax', 'dataAjax')->name('dataAjax');
		Route::get('/search', 'search')->name('search');
		Route::get('/list', 'list')->name('list');
		Route::get('/cariKode', 'cariKode')->name('cariKode');
		Route::post('/cekOngkir', 'cekOngkir')->name('cekOngkir');
		Route::post('/cekAsuransi', 'cekAsuransi')->name('cekAsuransi');
		Route::get('/stt-list', 'index')->name('stt-list');
		Route::get('/stt-create', 'insert')->name('stt-create');
		Route::get('/history', 'history')->name('history');
		Route::post('/lacakPengiriman', 'lacakPengiriman')->name('lacakPengiriman');
		Route::post('/create', 'create')->name('create');
		Route::get('/bayar/{id}', 'bayar')->name('bayar');
		Route::get('/cetakSTT/{id}', 'cetakSTT')->name('cetakSTT');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/updateBayar/{id}', 'updateBayar')->name('updateBayar');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(PickupController::class)->name('pickup.')->prefix('pickup')->group(function(){
		Route::get('/list', 'list')->name('list');
		Route::get('/dataAjax', 'dataAjax')->name('dataAjax');
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/generate', 'generate')->name('generate');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(PenerimaanController::class)->name('penerimaan.')->prefix('penerimaan')->group(function(){
		Route::get('/list', 'list')->name('list');
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::post('/generate', 'generate')->name('generate');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(ManifestJemputController::class)->name('manifestJemput.')->prefix('manifestJemput')->group(function(){		
		Route::post('/generate', 'generate')->name('generate');
		Route::get('/list', 'list')->name('list');
		Route::get('/insert', 'insert')->name('insert');
		Route::get('/cekPort/{id}', 'cekPort')->name('cekPort');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::get('/cetak/{id}', 'cetak')->name('cetak');
		Route::get('/detail/{id}', 'detail')->name('detail');
		Route::post('/update/{id}', 'update')->name('update');
		Route::post('/updatePickup/{id}', 'updatePickup')->name('updatePickup');
		Route::post('/updatePenerimaan/{id}', 'updatePenerimaan')->name('updatePenerimaan');
		Route::get('/delete/{id}', 'delete')->name('delete');		
		Route::get('/deleteDetail/{id}', 'deleteDetail')->name('deleteDetail');		
		Route::get('/lock/{id}', 'lock')->name('lock');		
		Route::get('/pickup/{id}', 'pickup')->name('pickup');		
		Route::get('/penerimaan/{id}', 'penerimaan')->name('penerimaan');		
		Route::get('/addItem/{id}/{ids}', 'addItem')->name('addItem');		
	});

	Route::controller(ManifestLintasController::class)->name('manifestLintas.')->prefix('manifestLintas')->group(function(){		
		Route::post('/generate', 'generate')->name('generate');
		Route::get('/list', 'list')->name('list');
		Route::get('/insert', 'insert')->name('insert');
		Route::get('/cekPort/{id}', 'cekPort')->name('cekPort');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::get('/cetak/{id}', 'cetak')->name('cetak');
		Route::get('/detail/{id}', 'detail')->name('detail');
		Route::post('/update/{id}', 'update')->name('update');
		Route::post('/updatePickup/{id}', 'updatePickup')->name('updatePickup');
		Route::post('/updatePenerimaan/{id}', 'updatePenerimaan')->name('updatePenerimaan');
		Route::get('/delete/{id}', 'delete')->name('delete');		
		Route::get('/deleteDetail/{id}', 'deleteDetail')->name('deleteDetail');		
		Route::get('/lock/{id}', 'lock')->name('lock');		
		Route::get('/pickup/{id}', 'pickup')->name('pickup');		
		Route::get('/penerimaan/{id}', 'penerimaan')->name('penerimaan');		
		Route::get('/addItem/{id}/{ids}', 'addItem')->name('addItem');		
	});

	Route::controller(ManifestUtamaController::class)->name('manifestUtama.')->prefix('manifestUtama')->group(function(){		
		Route::post('/generate', 'generate')->name('generate');
		Route::get('/list', 'list')->name('list');
		Route::get('/insert', 'insert')->name('insert');
		Route::get('/cekPort/{id}', 'cekPort')->name('cekPort');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::get('/cetak/{id}', 'cetak')->name('cetak');
		Route::get('/detail/{id}', 'detail')->name('detail');
		Route::post('/update/{id}', 'update')->name('update');
		Route::post('/updatePickup/{id}', 'updatePickup')->name('updatePickup');
		Route::post('/updatePenerimaan/{id}', 'updatePenerimaan')->name('updatePenerimaan');
		Route::get('/delete/{id}', 'delete')->name('delete');		
		Route::get('/deleteDetail/{id}', 'deleteDetail')->name('deleteDetail');		
		Route::get('/lock/{id}', 'lock')->name('lock');		
		Route::get('/open/{id}', 'open')->name('open');		
		Route::get('/riwayat/{id}', 'riwayat')->name('riwayat');		
		Route::get('/dropManifest/{id}/{ids}', 'dropManifest')->name('dropManifest');		
		Route::get('/addItem/{id}/{ids}', 'addItem')->name('addItem');		
		Route::get('/addManifest/{id}/{ids}/{idss}', 'addManifest')->name('addManifest');		
	});

	Route::controller(ManifestAntarController::class)->name('manifestAntar.')->prefix('manifestAntar')->group(function(){		
		Route::post('/generate', 'generate')->name('generate');
		Route::get('/list', 'list')->name('list');
		Route::get('/insert', 'insert')->name('insert');
		Route::get('/cekPort/{id}', 'cekPort')->name('cekPort');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::get('/cetak/{id}', 'cetak')->name('cetak');
		Route::get('/detail/{id}', 'detail')->name('detail');
		Route::post('/update/{id}', 'update')->name('update');
		Route::post('/updatePickup/{id}', 'updatePickup')->name('updatePickup');
		Route::post('/updatePenerimaan/{id}', 'updatePenerimaan')->name('updatePenerimaan');
		Route::get('/delete/{id}', 'delete')->name('delete');		
		Route::post('/cekCakupan', 'cekCakupan')->name('cekCakupan');
		Route::get('/deleteDetail/{id}', 'deleteDetail')->name('deleteDetail');		
		Route::get('/lock/{id}', 'lock')->name('lock');		
		Route::get('/pickup/{id}', 'pickup')->name('pickup');		
		Route::get('/penyerahan/{id}', 'penyerahan')->name('penyerahan');				
		Route::get('/addItem/{id}/{ids}', 'addItem')->name('addItem');		
		Route::post('/simpanDiterima/{id}', 'simpanDiterima')->name('simpanDiterima');
		Route::post('/simpanTidakDiterima/{id}', 'simpanTidakDiterima')->name('simpanTidakDiterima');
		Route::get('/barangDiterima/{id}', 'barangDiterima')->name('barangDiterima');
		Route::get('/barangTidakDiterima/{id}', 'barangTidakDiterima')->name('barangTidakDiterima');
	});

	Route::controller(WarehouseController::class)->name('warehouse.')->prefix('warehouse')->group(function(){
		Route::get('/list', 'list')->name('list');
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::get('/detail', 'detail')->name('detail');
		Route::get('/terima', 'terima')->name('terima');
		Route::post('/create', 'create')->name('create');
		Route::post('/generate', 'generate')->name('generate');
		Route::get('/proses/{id}', 'proses')->name('proses');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(WarehousingController::class)->name('warehousing.')->prefix('warehousing')->group(function(){
		Route::get('/list', 'list')->name('list');
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
		Route::get('/simpan/', 'simpan')->name('simpan');
	});

	Route::controller(SuratJalanController::class)->name('suratJalan.')->prefix('suratJalan')->group(function(){
		Route::get('/list', 'list')->name('list');
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::get('/detail', 'detail')->name('detail');
		Route::post('/generate', 'generate')->name('generate');
		Route::post('/cekCakupan', 'cekCakupan')->name('cekCakupan');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

	Route::controller(DeliveryController::class)->name('delivery.')->prefix('delivery')->group(function(){		
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::post('/generate', 'generate')->name('generate');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
		Route::post('/simpanDiterima/{id}', 'simpanDiterima')->name('simpanDiterima');
		Route::post('/simpanTidakDiterima/{id}', 'simpanTidakDiterima')->name('simpanTidakDiterima');
		Route::get('/barangDiterima/{id}', 'barangDiterima')->name('barangDiterima');
		Route::get('/barangTidakDiterima/{id}', 'barangTidakDiterima')->name('barangTidakDiterima');
	});

	Route::controller(PengirimanLanjutanController::class)->name('pengirimanLanjutan.')->prefix('pengirimanLanjutan')->group(function(){
		Route::get('/list', 'list')->name('list');
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::get('/detail', 'detail')->name('detail');
		Route::get('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');
	});

});