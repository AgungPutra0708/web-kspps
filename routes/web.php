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
Route::get('/pembiayaan', [App\Http\Controllers\LoanController::class, 'index'])->name('pembiayaan');
Route::get('/management-user', [App\Http\Controllers\UserMemberController::class, 'index'])->name('management_user');
Route::get('/input-simpanan', [App\Http\Controllers\InputSavingController::class, 'index'])->name('input_simpanan');
Route::get('/input-pembiayaan', [App\Http\Controllers\InputLoanController::class, 'index'])->name('input_pembiayaan');
Route::get('/input-simpanan-kolektif', [App\Http\Controllers\InputSavingController::class, 'indexKolektif'])->name('input_simpanan_kolektif');
Route::get('/penarikan-simpanan-kolektif', [App\Http\Controllers\InputSavingController::class, 'indexPenarikanKolektif'])->name('penarikan_simpanan_kolektif');
Route::get('/input-pembiayaan-kolektif', [App\Http\Controllers\InputLoanController::class, 'indexKolektif'])->name('input_pembiayaan_kolektif');
Route::get('/informasi-berita', [App\Http\Controllers\InformationController::class, 'index'])->name('informasi_berita');
Route::get('/pesan-anggota', [App\Http\Controllers\MessageController::class, 'index'])->name('pesan_anggota');
