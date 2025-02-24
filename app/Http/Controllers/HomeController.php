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
        // Initialize saldoSimpanan to 0
        $saldoSimpanan = 0;

        // Set locale ke bahasa Indonesia
        Carbon::setLocale('id');

        // Get the current Gregorian date
        $gregorianDate = Carbon::now('Asia/Jakarta')->translatedFormat('d F, Y');

        // Get the current Hijri date with full month name
        function convertToHijri($gregorianDate)
        {
            $hijriMonths = [
                1 => 'Muharam',
                2 => 'Safar',
                3 => 'Rabiul Awal',
                4 => 'Rabiul Akhir',
                5 => 'Jumadil Awal',
                6 => 'Jumadil Akhir',
                7 => 'Rajab',
                8 => 'Syaban',
                9 => 'Ramadan',
                10 => 'Syawal',
                11 => 'Zulkaidah',
                12 => 'Zulhijah'
            ];

            // Kurangi 1 hari dari tanggal Gregorian untuk menyesuaikan selisih
            $adjustedDate = Carbon::createFromFormat('Y-m-d', $gregorianDate)->subDay()->toDateString();

            // Formatter dengan pola yang menghasilkan tanggal, bulan, dan tahun
            $formatter = \IntlDateFormatter::create(
                'en_US@calendar=islamic',
                \IntlDateFormatter::SHORT,
                \IntlDateFormatter::NONE,
                'Asia/Jakarta',
                \IntlDateFormatter::TRADITIONAL,
                'd M y' // Format menghasilkan "12 5 1445"
            );

            $hijri = $formatter->format(new \DateTime($adjustedDate));

            // Pecah hasil hijri berdasarkan spasi
            [$day, $month, $year] = explode(' ', $hijri);

            // Ganti angka bulan dengan nama bulan Hijriah
            return "$day {$hijriMonths[(int)$month]} $year";
        }
        $hijriDate = convertToHijri(Carbon::now('Asia/Jakarta')->toDateString());

        // Retrieve anggota data based on id_user from the session
        $anggotaData = AnggotaModel::find(Session::get('id_user'));

        if ($anggotaData) {
            // Calculate saldo_akhir using the transaksiAllSimpanans relationship
            $saldoSimpanan = $anggotaData->transaksiAllSimpanans()
                ->select(DB::raw('SUM(CASE WHEN metode_transaksi = "+" THEN jumlah_setoran ELSE -jumlah_setoran END) as saldo_akhir'))
                ->join('simpanans', 'simpanans.id', '=', 'transaksi_simpanans.id_simpanan') // Correct the join condition
                ->whereNull('simpanans.deleted_at') // Ensure simpanan is not soft deleted
                ->value('saldo_akhir');
        }

        $dataProfile = ProfileKoperasiModel::first();

        // Render the view with the anggota data
        return view('anggota.home', compact('saldoSimpanan', 'gregorianDate', 'hijriDate', 'dataProfile'));
    }
}
