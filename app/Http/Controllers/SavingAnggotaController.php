<?php

namespace App\Http\Controllers;

use App\Models\RekeningSimpananModel;
use App\Models\SimpananModel;
use App\Models\TransaksiSimpananModel;
use Carbon\Carbon;
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

        // Variabel untuk total saldo simpanan utama
        $totalSaldoUtama = 0;
        $idSimpananUtama = null; // Variabel untuk menyimpan ID simpanan utama

        // Filter simpanan dan hitung saldo akhir berdasarkan id_anggota
        $dataSimpanan = $simpananData->map(function ($item) use ($id_anggota, &$totalSaldoUtama, &$idSimpananUtama) {
            // Hitung saldo_akhir berdasarkan transaksi_simpanans untuk id_anggota dan id_simpanan
            $saldoAkhir = $item->transaksiSimpanans($id_anggota)
                ->select(DB::raw('SUM(CASE WHEN metode_transaksi = "+" THEN jumlah_setoran ELSE -jumlah_setoran END) as saldo_akhir'))
                ->value('saldo_akhir');

            $dataRekeningSimpanan = RekeningSimpananModel::where('id_anggota', $id_anggota)
                ->where('id_simpanan', $item->id)
                ->first();

            // Jika simpanan utama, tambahkan ke total saldo utama dan simpan ID-nya
            if ($item->utama == 'true') {
                $totalSaldoUtama += $saldoAkhir;
                $idSimpananUtama = $item->id; // Simpan ID simpanan utama
            }

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

        return view('anggota.detail-saving', compact('dataSimpanan', 'totalSaldoUtama', 'idSimpananUtama'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        $id_simpanan = Crypt::decrypt($id);
        $id_anggota = Session::get('id_user');

        // Ambil data simpanan yang akan diedit
        $simpananData = SimpananModel::find($id_simpanan);

        // Fetch transaksi untuk simpanan yang di-edit
        if ($simpananData && $simpananData->utama == 'true') {
            // Ambil semua simpanan yang utama
            $simpananUtama = SimpananModel::where('utama', 'true')->pluck('id')->toArray();
            $transaksiSimpananData = TransaksiSimpananModel::whereIn('id_simpanan', $simpananUtama)
                ->where('id_anggota', $id_anggota)
                ->orderBy('tanggal_transaksi', 'desc')
                ->get();
        } else {
            $transaksiSimpananData = TransaksiSimpananModel::where('id_simpanan', $id_simpanan)
                ->where('id_anggota', $id_anggota)
                ->orderBy('tanggal_transaksi', 'desc')
                ->get();
        }

        // Map the data to display
        $transaksiSimpanan = $transaksiSimpananData->map(function ($item) {
            $kondisiTransaksi = $item->metode_transaksi == "+" ? "Setoran" : "Penarikan";
            return [
                'id' => Crypt::encrypt($item->id),
                'keterangan' => Carbon::parse($item->tanggal_transaksi)->format('d/m/Y') . '<br>' . $kondisiTransaksi . '<br>' . $item->keterangan,
                'nominal' => $item->metode_transaksi . ' Rp ' . number_format($item->jumlah_setoran, 2, ',', '.'),
                'metode_transaksi' => $item->metode_transaksi,
            ];
        });

        return view('anggota.transaksi-saving', compact('transaksiSimpanan'));
    }
}
