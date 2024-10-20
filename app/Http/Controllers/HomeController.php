<?php

namespace App\Http\Controllers;

use App\Models\AnggotaModel;
use App\Models\ProfileKoperasiModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use MohamedSabil83\LaravelHijrian\Facades\Hijrian;

class HomeController extends Controller
{
    public function index()
    {
        return view('layout.anggota');
    }

    public function indexHome()
    {
        // Initialize saldoAkhir to 0
        $saldoSimpanan = 0;

        // Set locale ke bahasa Indonesia
        Carbon::setLocale('id');

        // Get the current Gregorian date
        $gregorianDate = Carbon::now('Asia/Jakarta')->translatedFormat('d F, Y');

        // Get the current Hijri date with full month name
        $hijriDate = Hijrian::hijri(Carbon::now('Asia/Jakarta'));

        // Retrieve anggota data based on id_user from the session
        $anggotaData = AnggotaModel::find(Session::get('id_user'));

        if ($anggotaData) {
            // Calculate saldo_akhir using the transaksiAllSimpanans relationship
            $saldoSimpanan = $anggotaData->transaksiAllSimpanans()
                ->select(DB::raw('SUM(CASE WHEN metode_transaksi = "+" THEN jumlah_setoran ELSE -jumlah_setoran END) as saldo_akhir'))
                ->value('saldo_akhir');
        }

        $dataProfile = ProfileKoperasiModel::first();

        // Render the view with the anggota data
        return view('anggota.home', compact('saldoSimpanan', 'gregorianDate', 'hijriDate', 'dataProfile'));
    }
}
