<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', [App\Http\Controllers\LoginController::class, 'index'])->name('login');

Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('home');

Route::get('/kumpulan', [App\Http\Controllers\RembugController::class, 'index'])->name('kumpulan');
Route::post('/kumpulan/save', [App\Http\Controllers\RembugController::class, 'store'])->name('kumpulan.store');
Route::get('/kumpulan/next-number', [App\Http\Controllers\RembugController::class, 'getLatestRembugNumber'])->name('get_number_kumpulan');

Route::get('/anggota', [App\Http\Controllers\MemberController::class, 'index'])->name('anggota');
Route::post('/anggota/save', [App\Http\Controllers\MemberController::class, 'store'])->name('anggota.store');
Route::get('/anggota/next-number', [App\Http\Controllers\MemberController::class, 'getMemberAndRembugData'])->name('get_member_and_rembug_data');

Route::get('/simpanan', [App\Http\Controllers\SavingController::class, 'index'])->name('simpanan');
Route::post('/simpanan/save', [App\Http\Controllers\SavingController::class, 'store'])->name('simpanan.store');
Route::get('/simpanan/next-number', [App\Http\Controllers\SavingController::class, 'getSavingData'])->name('get_saving_data');

Route::get('/pembiayaan', [App\Http\Controllers\LoanController::class, 'index'])->name('pembiayaan');
Route::post('/pembiayaan/save', [App\Http\Controllers\LoanController::class, 'store'])->name('pembiayaan.store');
Route::get('/pembiayaan/next-number', [App\Http\Controllers\LoanController::class, 'getLoanData'])->name('get_loan_data');

Route::get('/management-user', [App\Http\Controllers\UserMemberController::class, 'index'])->name('management_user');
Route::post('/management-user/save', [App\Http\Controllers\UserMemberController::class, 'store'])->name('management_user.store');
Route::get('/management-user/anggota', [App\Http\Controllers\UserMemberController::class, 'getMemberData'])->name('get_member_data');

Route::get('/input-simpanan', [App\Http\Controllers\InputSavingController::class, 'index'])->name('input_simpanan');
Route::post('/input-simpanan/save', [App\Http\Controllers\InputSavingController::class, 'store'])->name('input_simpanan.store');

Route::get('/input-pembiayaan', [App\Http\Controllers\InputLoanController::class, 'index'])->name('input_pembiayaan');
Route::post('/input-pembiayaan/save', [App\Http\Controllers\InputLoanController::class, 'store'])->name('input_pembiayaan.store');

Route::get('/input-simpanan-kolektif', [App\Http\Controllers\InputSavingController::class, 'indexKolektif'])->name('input_simpanan_kolektif');
Route::get('/input-simpanan-kolektif/get-data', [App\Http\Controllers\InputSavingController::class, 'getMemberDataSimpananKolektif'])->name('get_member_data_simpanan_kolektif');
Route::post('/input-simpanan-kolektif/save', [App\Http\Controllers\InputSavingController::class, 'storeSimpananKolektif'])->name('input_simpanan_kolektif.store');

Route::get('/penarikan-simpanan-kolektif', [App\Http\Controllers\InputSavingController::class, 'indexPenarikanKolektif'])->name('penarikan_simpanan_kolektif');

Route::get('/input-pembiayaan-kolektif', [App\Http\Controllers\InputLoanController::class, 'indexKolektif'])->name('input_pembiayaan_kolektif');
Route::get('/input-pembiayaan-kolektif/get-data', [App\Http\Controllers\InputLoanController::class, 'getMemberDataPembiayaanKolektif'])->name('get_member_data_pembiayaan_kolektif');
Route::post('/input-pembiayaan-kolektif/save', [App\Http\Controllers\InputLoanController::class, 'storePembiayaanKolektif'])->name('input_pembiayaan_kolektif.store');

Route::get('/informasi-berita', [App\Http\Controllers\InformationController::class, 'index'])->name('informasi_berita');
Route::get('/informasi-berita/create', [App\Http\Controllers\InformationController::class, 'create'])->name('informasi_berita.create');
Route::post('/informasi-berita/store', [App\Http\Controllers\InformationController::class, 'store'])->name('informasi_berita.store');
Route::get('/pesan-anggota', [App\Http\Controllers\MessageController::class, 'index'])->name('pesan_anggota');
