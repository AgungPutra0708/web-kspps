<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AnggotaPublicController;

// routes/api.php
Route::get('/anggota/{token}', [AnggotaPublicController::class, 'show']);
