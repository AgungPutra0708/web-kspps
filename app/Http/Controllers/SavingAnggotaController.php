<?php

namespace App\Http\Controllers;

use App\Models\RekeningSimpananModel;
use App\Models\SimpananModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SavingAnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id_anggota = Session::get('id_user');

        // Ambil semua data simpanan
        $simpananData = SimpananModel::all();

        // Filter simpanan dan hitung saldo akhir berdasarkan id_anggota
        $dataSimpanan = $simpananData->map(function ($item) use ($id_anggota) {
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
                'no_simpanan' => $item->no_simpanan ?? null, // No Simpanan
                'nama_simpanan' => $item->nama_simpanan ?? null, // Nama Simpanan
                'saldo_akhir' => $saldoAkhir ?? 0, // Saldo akhir dari tabel transaksi_simpanans
                'utama' => $item->utama,
            ];
        });
        return view('anggota.detail-saving', compact('dataSimpanan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
