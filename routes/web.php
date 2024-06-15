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
use App\Http\Controllers\RekonsiliasiController;
use App\Http\Controllers\LapProdukController;
use App\Http\Controllers\LapPaketController;
use App\Http\Controllers\LapMitraController;
use App\Http\Controllers\LapSetoranController;
use App\Http\Controllers\LapBonusController;


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
	Route::post('/actionMember', 'actionMember')->name('login.actionMember');
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
	Route::controller(UserTypeController::class)->name('user_type.')->prefix('user_type')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/create', 'create')->name('create');
		Route::get('/edit/{id}', 'edit')->name('edit');
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');	
		Route::get('/access/{id}', 'access')->name('access');	
		Route::post('/updateAccess/{id}', 'updateAccess')->name('updateAccess');	
	});
	Route::controller(UsersController::class)->name('users.')->prefix('users')->group(function(){
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
		Route::post('/createAll', 'createAll')->name('createAll');		
		Route::get('/edit/{id}', 'edit')->name('edit');		
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');		
		Route::get('/submit/{id}', 'submit')->name('submit');		
		Route::get('/addDetail', 'addDetail')->name('addDetail');		
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
		Route::post('/tampilData', 'tampilData')->name('tampilData');		
		Route::post('/create', 'create')->name('create');		
		Route::get('/detail/{id}', 'detail')->name('detail');		
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');		
		Route::get('/approval/{id}', 'approval')->name('approval');		
		Route::post('/approval/{id}', 'saveApproval')->name('saveApproval');		
	});
	Route::controller(RekonsiliasiController::class)->name('rekonsiliasi.')->prefix('rekonsiliasi')->group(function(){
		Route::get('/index', 'index')->name('index');
		Route::get('/insert', 'insert')->name('insert');
		Route::post('/tampilData', 'tampilData')->name('tampilData');		
		Route::post('/create', 'create')->name('create');		
		Route::get('/detail/{id}', 'detail')->name('detail');		
		Route::post('/update/{id}', 'update')->name('update');
		Route::get('/delete/{id}', 'delete')->name('delete');		
		Route::get('/approval/{id}', 'approval')->name('approval');		
		Route::post('/approval/{id}', 'saveApproval')->name('saveApproval');		
		Route::get('/tampilData', 'tampilData')->name('tampilData');		
	});

	Route::controller(LapProdukController::class)->name('lapProduk.')->prefix('lapProduk')->group(function(){
		Route::get('/index', 'index')->name('index');		
		Route::post('/filter', 'filter')->name('filter');
		Route::get('/cetak/{id}', 'cetak')->name('cetak');		
	});
	Route::controller(LapPaketController::class)->name('lapPaket.')->prefix('lapPaket')->group(function(){
		Route::get('/index', 'index')->name('index');		
		Route::post('/filter', 'filter')->name('filter');
		Route::get('/cetak/{id}', 'cetak')->name('cetak');		
	});
	Route::controller(LapBonusController::class)->name('lapBonus.')->prefix('lapBonus')->group(function(){
		Route::get('/index', 'index')->name('index');		
		Route::post('/filter', 'filter')->name('filter');
		Route::get('/cetak/{id}', 'cetak')->name('cetak');		
	});
	Route::controller(LapSetoranController::class)->name('lapSetoran.')->prefix('lapSetoran')->group(function(){
		Route::get('/index', 'index')->name('index');		
		Route::post('/filter', 'filter')->name('filter');
		Route::get('/cetak/{id}', 'cetak')->name('cetak');		
	});
	Route::controller(LapMitraController::class)->name('lapMitra.')->prefix('lapMitra')->group(function(){
		Route::get('/index', 'index')->name('index');		
		Route::post('/filter', 'filter')->name('filter');
		Route::get('/cetak/{id}', 'cetak')->name('cetak');		
	});
	Route::controller(LapRekonsiliasiController::class)->name('lapRekonsiliasi.')->prefix('lapRekonsiliasi')->group(function(){
		Route::get('/index', 'index')->name('index');		
		Route::post('/filter', 'filter')->name('filter');
		Route::get('/cetak/{id}', 'cetak')->name('cetak');		
	});
	

});