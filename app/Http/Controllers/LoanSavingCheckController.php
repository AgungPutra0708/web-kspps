<?php

namespace App\Http\Controllers;

use App\Models\AnggotaModel;
use App\Models\PinjamanModel;
use App\Models\SimpananModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanSavingCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'dataAnggota' => AnggotaModel::all(),
        ];
        return view('admin.cek', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getSavingLoanData(Request $request)
    {
        $id_anggota = $request->input('id_anggota');

        // Ambil semua data simpanan
        $simpananData = SimpananModel::all();
        $pinjamanData = PinjamanModel::where('id_anggota', $id_anggota)->get();

        // Filter simpanan dan hitung saldo akhir berdasarkan id_anggota
        $filteredDataSimpanan = $simpananData->map(function ($item) use ($id_anggota) {
            // Hitung saldo_akhir berdasarkan transaksi_simpanans untuk id_anggota dan id_simpanan
            $saldoAkhir = $item->transaksiSimpanans($id_anggota)
                ->select(DB::raw('SUM(CASE WHEN metode_transaksi = "+" THEN jumlah_setoran ELSE -jumlah_setoran END) as saldo_akhir'))
                ->value('saldo_akhir');

            return [
                'id_simpanan' => $item->id,  // ID Simpanan
                'id_anggota' => $id_anggota,  // ID Anggota yang sedang difilter
                'no_anggota' => $item->anggota->no_anggota ?? null, // No Anggota dari tabel anggota
                'nama_anggota' => $item->anggota->nama_anggota ?? null, // Nama Anggota dari tabel anggota
                'nama_simpanan' => $item->nama_simpanan ?? null, // Nama Simpanan
                'saldo_akhir' => $saldoAkhir ?? 0, // Saldo akhir dari tabel transaksi_simpanans
            ];
        });

        // Kembalikan data dalam bentuk JSON
        return response()->json([
            'saving_data' => $filteredDataSimpanan,
            'loan_data' => $pinjamanData,
        ]);
    }
}
