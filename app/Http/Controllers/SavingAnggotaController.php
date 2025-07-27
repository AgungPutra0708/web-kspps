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
        // Ambil semua rekening simpanan berdasarkan id_anggota
        $dataRekeningSimpanan = RekeningSimpananModel::where('id_anggota', $id_anggota)->get();
        // Ambil semua data simpanan
        $simpananData = SimpananModel::all();
        // Variabel untuk total saldo simpanan utama
        $totalSaldoUtama = 0;
        $idSimpananUtama = null; // Variabel untuk menyimpan ID simpanan utama
        // Simpanan yang akan ditampilkan
        $dataSimpanan = [];
        foreach ($dataRekeningSimpanan as $rekening) {
            // Ambil data simpanan berdasarkan id_simpanan dari rekening
            $simpanan = $simpananData->firstWhere('id', $rekening->id_simpanan);
            if ($simpanan) {
                // Hitung saldo_akhir berdasarkan transaksi_simpanans untuk id_anggota dan id_simpanan
                $saldoAkhir = $simpanan->transaksiSimpanans($id_anggota)
                    ->select(DB::raw('SUM(CASE WHEN metode_transaksi = "+" THEN jumlah_setoran ELSE -jumlah_setoran END) as saldo_akhir'))
                    ->where('id_simpanan', $rekening->id_simpanan)
                    ->value('saldo_akhir');
                if ($saldoAkhir != 0) {
                    // Jika simpanan utama, tambahkan ke total saldo utama dan simpan ID-nya
                    if ($simpanan->utama == 'true') {
                        $totalSaldoUtama += $saldoAkhir;
                        $idSimpananUtama = $simpanan->id; // Simpan ID simpanan utama
                    }
                    // Tambahkan data simpanan ke array dataSimpanan
                    $dataSimpanan[] = [
                        'id_simpanan' => Crypt::encrypt($simpanan->id),  // ID Simpanan
                        'id_anggota' => $id_anggota,  // ID Anggota yang sedang difilter
                        'no_anggota' => $simpanan->anggota->no_anggota ?? null, // No Anggota dari tabel anggota
                        'nama_anggota' => $simpanan->anggota->nama_anggota ?? null, // Nama Anggota dari tabel anggota
                        'no_rekening_simpanan' => $rekening->no_rekening_simpanan ?? "-", // No Simpanan
                        'no_simpanan' => $simpanan->no_simpanan ?? null, // No Simpanan
                        'nama_simpanan' => $simpanan->nama_simpanan ?? null, // Nama Simpanan
                        'saldo_akhir' => $saldoAkhir, // Saldo akhir dari tabel transaksi_simpanans
                        'utama' => $simpanan->utama,
                    ];
                }
            }
        }
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
                'nominal' => $item->metode_transaksi . ' Rp ' . number_format($item->jumlah_setoran, 0, ',', '.'),
                'metode_transaksi' => $item->metode_transaksi,
            ];
        });
        return view('anggota.transaksi-saving', compact('transaksiSimpanan'));
    }
}
