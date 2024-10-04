<?php

namespace App\Http\Controllers;

use App\Models\AnggotaModel;
use App\Models\RembugModel;
use App\Models\SimpananModel;
use App\Models\TransaksiSimpananModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InputSavingController extends Controller
{
    public function index()
    {
        $data = [
            'dataSimpanan' => SimpananModel::all(),
            'dataAnggota' => AnggotaModel::all(),
        ];
        return view('admin.inputsimpanan', $data);
    }

    public function store(Request $request)
    {
        // Decode JSON array dari input hidden
        $simpananArray = json_decode($request->simpanan_array, true);

        // Validasi array jika diperlukan
        if (empty($simpananArray)) {
            return redirect()->back()->withErrors(['message' => 'Data simpanan kosong']);
        }

        // Loop melalui setiap item dalam array dan simpan ke database
        foreach ($simpananArray as $simpanan) {
            TransaksiSimpananModel::create([
                'id_simpanan' => $simpanan['id_simpanan'],
                'id_anggota' => $simpanan['id_anggota'],
                'metode_transaksi' => $simpanan['metode_transaksi'],
                'jumlah_setoran' => $simpanan['nominal_setoran'],
                'keterangan' => $simpanan['keterangan'],
                'tanggal_transaksi' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data transaksi simpanan berhasil ditambahkan.');
    }

    public function indexKolektif()
    {
        $data = [
            'dataSimpanan' => SimpananModel::all(),
            'dataKumpulan' => RembugModel::all(),
        ];
        return view('admin.inputsimpanankolektif', $data);
    }

    public function getMemberDataSimpananKolektif(Request $request)
    {
        $idSimpanan = $request->input('id_simpanan');
        $idRembug = $request->input('id_rembug');

        // Ambil data anggota dengan filter id_rembug
        $anggotaData = AnggotaModel::where('id_rembug', $idRembug)
            ->with('rembug')
            ->get();

        // Transform data untuk mengirimkan response JSON
        $encryptedData = $anggotaData->map(function ($item) use ($idSimpanan) {
            // Hitung saldo_akhir berdasarkan summary dari transaksi_simpanans dengan id_anggota dan id_simpanan
            $saldoAkhir = $item->transaksiSimpanans($idSimpanan)
                ->select(DB::raw('SUM(CASE WHEN metode_transaksi = "+" THEN jumlah_setoran ELSE -jumlah_setoran END) as saldo_akhir'))
                ->value('saldo_akhir');

            return [
                'id_anggota' => $item->id,  // ID Anggota
                'id_simpanan' => $idSimpanan,  // ID Simpanan
                'no_anggota' => $item->no_anggota, // No Anggota
                'nama_anggota' => $item->nama_anggota, // Nama Anggota
                'nama_rembug' => $item->rembug->nama_rembug ?? null, // Nama Rembug dari tabel rembug
                'saldo_akhir' => $saldoAkhir ?? 0, // Saldo akhir dari tabel transaksi_simpanans
            ];
        });

        // Kembalikan data dalam bentuk JSON
        return response()->json([
            'anggota_data' => $encryptedData
        ]);
    }

    public function storeSimpananKolektif(Request $request)
    {
        // Ambil array simpanan dari input hidden
        $simpananArray = json_decode($request->input('simpanan_array'), true);

        // Loop melalui array simpanan dan simpan ke database
        foreach ($simpananArray as $simpanan) {
            // Check that jumlah_setoran is a valid number and not null, 0, or an empty string
            if (!empty($simpanan['jumlah_setoran']) && is_numeric($simpanan['jumlah_setoran'])) {
                TransaksiSimpananModel::create([
                    'id_anggota' => $simpanan['id_anggota'],
                    'id_simpanan' => $simpanan['id_simpanan'],
                    'metode_transaksi' => $simpanan['metode_transaksi'],
                    'jumlah_setoran' => $simpanan['jumlah_setoran'],
                    'keterangan' => $simpanan['keterangan'],
                    'tanggal_transaksi' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Data simpanan kolektif berhasil disimpan.');
    }


    public function indexPenarikanKolektif()
    {
        $data = [
            'dataSimpanan' => SimpananModel::all(),
            'dataKumpulan' => RembugModel::all(),
        ];
        return view('admin.penarikansimpanankolektif', $data);
    }
}
