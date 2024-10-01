<?php

namespace App\Http\Controllers;

use App\Models\AnggotaModel;
use App\Models\SimpananModel;
use App\Models\TransaksiSimpananModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        return view('admin.inputsimpanankolektif');
    }
    public function indexPenarikanKolektif()
    {
        return view('admin.penarikansimpanankolektif');
    }
}
