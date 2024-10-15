<?php

namespace App\Http\Controllers;

use App\Models\AnggotaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        return view('layout.anggota');
    }

    public function indexHome()
    {
        // Initialize saldoAkhir to 0
        $saldoAkhir = 0;

        // Retrieve anggota data based on id_user from the session
        $anggotaData = AnggotaModel::find(Session::get('id_user'));

        if ($anggotaData) {
            // Calculate saldo_akhir using the transaksiAllSimpanans relationship
            $saldoAkhir = $anggotaData->transaksiAllSimpanans()
                ->select(DB::raw('SUM(CASE WHEN metode_transaksi = "+" THEN jumlah_setoran ELSE -jumlah_setoran END) as saldo_akhir'))
                ->value('saldo_akhir');
        }

        // Prepare the data to send as response
        $data = [
            'saldoSimpanan' => $saldoAkhir
        ];

        // Render the view with the anggota data
        return view('anggota.home', $data);
    }
}
