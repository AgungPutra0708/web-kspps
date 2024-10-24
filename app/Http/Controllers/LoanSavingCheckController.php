<?php

namespace App\Http\Controllers;

use App\Models\AnggotaModel;
use App\Models\PinjamanModel;
use App\Models\RekeningSimpananModel;
use App\Models\SimpananModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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
        $id_anggota = Crypt::decrypt($request->input('id_anggota'));

        // Ambil semua data simpanan
        $simpananData = SimpananModel::all();
        $pinjamanData = PinjamanModel::where('id_anggota', $id_anggota)->get();

        // Filter simpanan dan hitung saldo akhir berdasarkan id_anggota
        $filteredDataSimpanan = $simpananData->map(function ($item) use ($id_anggota) {
            // Hitung saldo_akhir berdasarkan transaksi_simpanans untuk id_anggota dan id_simpanan
            $saldoAkhir = $item->transaksiSimpanans($id_anggota)
                ->select(DB::raw('SUM(CASE WHEN metode_transaksi = "+" THEN jumlah_setoran ELSE -jumlah_setoran END) as saldo_akhir'))
                ->value('saldo_akhir');

            $dataRekeningSimpanan = RekeningSimpananModel::where('id_anggota', $id_anggota)->where('id_simpanan', $item->id)->first();

            return [
                'id_simpanan' => Crypt::encrypt($item->id),  // ID Simpanan
                'id_anggota' => $id_anggota,  // ID Anggota yang sedang difilter
                'no_anggota' => $item->anggota->no_anggota ?? null, // No Anggota dari tabel anggota
                'nama_anggota' => $item->anggota->nama_anggota ?? null, // Nama Anggota dari tabel anggota
                'no_rekening_simpanan' => $dataRekeningSimpanan->no_rekening_simpanan ?? "-", // No Simpanan
                'id_rekening_simpanan' => Crypt::encrypt($dataRekeningSimpanan->id) ?? "-", // No Simpanan
                'no_simpanan' => $item->no_simpanan ?? null, // No Simpanan
                'nama_simpanan' => $item->nama_simpanan ?? null, // Nama Simpanan
                'saldo_akhir' => $saldoAkhir ?? 0, // Saldo akhir dari tabel transaksi_simpanans
                'utama' => $item->utama ?? null,
            ];
        });

        // Map pinjamanData untuk mengenkripsi id_pinjaman
        $filteredDataPinjaman = $pinjamanData->map(function ($item) {
            return [
                'id_pinjaman' => Crypt::encrypt($item->id),  // Enkripsi ID Pinjaman
                'no_pinjaman' => $item->no_pinjaman,
                'besar_pinjaman' => $item->besar_pinjaman,
                'besar_margin' => $item->besar_margin,
                'lama_pinjaman' => $item->lama_pinjaman,
                'status_pinjaman' => $item->status_pinjaman,
            ];
        });

        // Kembalikan data dalam bentuk JSON
        return response()->json([
            'saving_data' => $filteredDataSimpanan,
            'loan_data' => $filteredDataPinjaman,
        ]);
    }
}
