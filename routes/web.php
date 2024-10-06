<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [App\Http\Controllers\LoginController::class, 'index'])->name('login');
Route::post('/login/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->middleware('role:petugas')->name('dashboard');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/kumpulan', [App\Http\Controllers\RembugController::class, 'index'])->middleware('role:petugas')->name('kumpulan');
Route::post('/kumpulan/save', [App\Http\Controllers\RembugController::class, 'store'])->middleware('role:petugas')->name('kumpulan.store');
Route::get('/kumpulan/next-number', [App\Http\Controllers\RembugController::class, 'getLatestRembugNumber'])->middleware('role:petugas')->name('get_number_kumpulan');
Route::post('/kumpulan/update/{id}', [App\Http\Controllers\RembugController::class, 'update'])->name('kumpulan.update');
Route::post('/kumpulan/delete/{id}', [App\Http\Controllers\RembugController::class, 'destroy'])->name('kumpulan.destroy');

Route::get('/petugas', [App\Http\Controllers\PetugasController::class, 'index'])->middleware('role:petugas')->name('petugas');
Route::post('/petugas/save', [App\Http\Controllers\PetugasController::class, 'store'])->middleware('role:petugas')->name('petugas.store');
Route::get('/petugas/next-number', [App\Http\Controllers\PetugasController::class, 'getLatestPetugasNumber'])->middleware('role:petugas')->name('get_number_petugas');
Route::get('/petugas/get-data', [App\Http\Controllers\PetugasController::class, 'getDataPetugas'])->middleware('role:petugas')->name('get_data_petugas');
Route::post('/petugas/update/{id}', [App\Http\Controllers\PetugasController::class, 'update'])->name('petugas.update');
Route::post('/petugas/delete/{id}', [App\Http\Controllers\PetugasController::class, 'destroy'])->name('petugas.destroy');

Route::get('/anggota', [App\Http\Controllers\MemberController::class, 'index'])->middleware('role:petugas')->name('anggota');
Route::post('/anggota/save', [App\Http\Controllers\MemberController::class, 'store'])->middleware('role:petugas')->name('anggota.store');
Route::get('/anggota/next-number', [App\Http\Controllers\MemberController::class, 'getMemberAndRembugData'])->middleware('role:petugas')->name('get_member_and_rembug_data');
Route::post('/anggota/update/{id}', [App\Http\Controllers\MemberController::class, 'updateMember'])->name('member.update');
Route::post('/anggota/delete/{id}', [App\Http\Controllers\MemberController::class, 'deleteMember'])->name('member.destroy');

Route::get('/simpanan', [App\Http\Controllers\SavingController::class, 'index'])->middleware('role:petugas')->name('simpanan');
Route::post('/simpanan/save', [App\Http\Controllers\SavingController::class, 'store'])->middleware('role:petugas')->name('simpanan.store');
Route::get('/simpanan/next-number', [App\Http\Controllers\SavingController::class, 'getSavingData'])->middleware('role:petugas')->name('get_saving_data');
Route::post('/simpanan/update/{id}', [App\Http\Controllers\SavingController::class, 'update'])->name('simpanan.update');
Route::post('/simpanan/delete/{id}', [App\Http\Controllers\SavingController::class, 'destroy'])->name('simpanan.destroy');

Route::get('/pembiayaan', [App\Http\Controllers\LoanController::class, 'index'])->middleware('role:petugas')->name('pembiayaan');
Route::post('/pembiayaan/save', [App\Http\Controllers\LoanController::class, 'store'])->middleware('role:petugas')->name('pembiayaan.store');
Route::get('/pembiayaan/next-number', [App\Http\Controllers\LoanController::class, 'getLoanData'])->middleware('role:petugas')->name('get_loan_data');
Route::post('/pembiayaan/update/{id}', [App\Http\Controllers\LoanController::class, 'update'])->name('pembiayaan.update');
Route::post('/pembiayaan/delete/{id}', [App\Http\Controllers\LoanController::class, 'destroy'])->name('pembiayaan.destroy');

Route::get('/management-user', [App\Http\Controllers\UserMemberController::class, 'index'])->middleware('role:petugas')->name('management_user');
Route::post('/management-user/save', [App\Http\Controllers\UserMemberController::class, 'store'])->middleware('role:petugas')->name('management_user.store');
Route::get('/management-user/anggota', [App\Http\Controllers\UserMemberController::class, 'getMemberData'])->middleware('role:petugas')->name('get_member_data');

Route::get('/input-simpanan', [App\Http\Controllers\InputSavingController::class, 'index'])->middleware('role:petugas')->name('input_simpanan');
Route::post('/input-simpanan/save', [App\Http\Controllers\InputSavingController::class, 'store'])->middleware('role:petugas')->name('input_simpanan.store');

Route::get('/input-pembiayaan', [App\Http\Controllers\InputLoanController::class, 'index'])->middleware('role:petugas')->name('input_pembiayaan');
Route::post('/input-pembiayaan/save', [App\Http\Controllers\InputLoanController::class, 'store'])->middleware('role:petugas')->name('input_pembiayaan.store');

Route::get('/input-simpanan-kolektif', [App\Http\Controllers\InputSavingController::class, 'indexKolektif'])->middleware('role:petugas')->name('input_simpanan_kolektif');
Route::get('/input-simpanan-kolektif/get-data', [App\Http\Controllers\InputSavingController::class, 'getMemberDataSimpananKolektif'])->middleware('role:petugas')->name('get_member_data_simpanan_kolektif');
Route::post('/input-simpanan-kolektif/save', [App\Http\Controllers\InputSavingController::class, 'storeSimpananKolektif'])->middleware('role:petugas')->name('input_simpanan_kolektif.store');
Route::get('/input-simpanan-kolektif/get-last-data', [App\Http\Controllers\InputSavingController::class, 'getLastTransactionSaving'])->middleware('role:petugas')->name('transaksi_simpanan_terakhir');

Route::get('/penarikan-simpanan-kolektif', [App\Http\Controllers\InputSavingController::class, 'indexPenarikanKolektif'])->middleware('role:petugas')->name('penarikan_simpanan_kolektif');

Route::get('/input-pembiayaan-kolektif', [App\Http\Controllers\InputLoanController::class, 'indexKolektif'])->middleware('role:petugas')->name('input_pembiayaan_kolektif');
Route::get('/input-pembiayaan-kolektif/get-data', [App\Http\Controllers\InputLoanController::class, 'getMemberDataPembiayaanKolektif'])->middleware('role:petugas')->name('get_member_data_pembiayaan_kolektif');
Route::post('/input-pembiayaan-kolektif/save', [App\Http\Controllers\InputLoanController::class, 'storePembiayaanKolektif'])->middleware('role:petugas')->name('input_pembiayaan_kolektif.store');
Route::get('/input-pembiayaan-kolektif/get-last-data', [App\Http\Controllers\InputLoanController::class, 'getLastTransactionLoan'])->middleware('role:petugas')->name('transaksi_angsuran_terakhir');

Route::get('/informasi-berita', [App\Http\Controllers\InformationController::class, 'index'])->middleware('role:petugas')->name('informasi_berita');
Route::get('/informasi-berita/create', [App\Http\Controllers\InformationController::class, 'create'])->middleware('role:petugas')->name('informasi_berita.create');
Route::post('/informasi-berita/store', [App\Http\Controllers\InformationController::class, 'store'])->middleware('role:petugas')->name('informasi_berita.store');

Route::get('/pesan-anggota', [App\Http\Controllers\MessageController::class, 'index'])->middleware('role:petugas')->name('pesan_anggota');
Route::get('/pesan-anggota/create', [App\Http\Controllers\MessageController::class, 'create'])->middleware('role:petugas')->name('pesan_anggota.create');
Route::post('/pesan-anggota/store', [App\Http\Controllers\MessageController::class, 'store'])->middleware('role:petugas')->name('pesan_anggota.store');
