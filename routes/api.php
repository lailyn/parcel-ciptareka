<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthMobileController;
use App\Http\Controllers\Api\KurirController;
use App\Http\Controllers\Api\ProfilController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthMobileController::class, 'login']);
Route::middleware('checkToken')->group(function () {
    Route::post('/logout', [AuthMobileController::class, 'logout']);
    
    Route::get('/daftar-kurir', [KurirController::class, 'daftar_kurir']);
    
    Route::get('/daftar-manifest-jemput', [KurirController::class, 'daftar_manifest_jemput']);    
    Route::get('/daftar-manifest-antar', [KurirController::class, 'daftar_manifest_antar']);
    Route::get('/daftar-manifest-lintas', [KurirController::class, 'daftar_manifest_lintas']);


    Route::post('/konfirmasi-penjemputan', [KurirController::class, 'konfirmasi_penjemputan']);    
    Route::post('/konfirmasi-pengantaran', [KurirController::class, 'konfirmasi_pengantaran']);    
    Route::post('/konfirmasi-lintas', [KurirController::class, 'konfirmasi_lintas']);    

    Route::post('/penerimaan-paket-jemput', [KurirController::class, 'penerimaan_paket_jemput']);    
    Route::post('/penerimaan-paket-lintas', [KurirController::class, 'penerimaan_paket_lintas']);    
    
    Route::post('/antar-barang', [KurirController::class, 'antar_barang']);    
    
    Route::get('/detail-paket/{id}', [KurirController::class, 'detail_paket']);    
    
    Route::get('/riwayat-jemput-kurir', [KurirController::class, 'riwayat_jemput_kurir']);    
    Route::get('/riwayat-jemput-outlet', [KurirController::class, 'riwayat_jemput_outlet']);  

    Route::get('/riwayat-jemput-port', [KurirController::class, 'riwayat_jemput_port']);    
    Route::get('/riwayat-lintas-port', [KurirController::class, 'riwayat_lintas_port']);  
    
    Route::get('/riwayat-lintas-kurir', [KurirController::class, 'riwayat_lintas_kurir']);        
    
    Route::get('/riwayat-antar-kurir', [KurirController::class, 'riwayat_antar']);  

    Route::get('/profil', [ProfilController::class, 'profil']);  
    Route::post('/ganti_password', [ProfilController::class, 'gantiPassword']);  
    Route::post('/kirim_otp_password', [ProfilController::class, 'kirim_otp_password']);  
    Route::post('/kirim_email', [ProfilController::class, 'kirimEmail']);  

});


