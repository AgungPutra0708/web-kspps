<?php

namespace App\Http\Controllers;

use App\Models\AnggotaModel;
use App\Models\PembiayaanModel;
use App\Models\PinjamanModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InputLoanController extends Controller
{
    public function index()
    {
        $data = [
            'dataPembiayaan' => PembiayaanModel::all(),
            'dataAnggota' => AnggotaModel::all(),
        ];
        return view('admin.inputpembiayaan', $data);
    }

    public function store(Request $request)
    {
        // Decode JSON array dari input hidden
        $pembiayaanArray = json_decode($request->pembiayaan_array, true);

        // Validasi array jika diperlukan
        if (empty($pembiayaanArray)) {
            return redirect()->back()->withErrors(['message' => 'Data pembiayaan kosong']);
        }

        // Loop melalui setiap item dalam array dan simpan ke database
        foreach ($pembiayaanArray as $pembiayaan) {
            PinjamanModel::create([
                'id_pembiayaan' => (int) $pembiayaan['id_pembiayaan'],
                'id_anggota' => (int) $pembiayaan['id_anggota'],
                'besar_pinjaman' => $pembiayaan['nominal_pinjaman'],
                'besar_margin' => $pembiayaan['nominal_margin'],
                'lama_pinjaman' => $pembiayaan['lama_pinjaman'],
                'angsur_pinjaman' => $pembiayaan['angsur_pinjaman'],
                'angsur_margin' => $pembiayaan['angsur_margin'],
                'kondisi_pinjaman' => $pembiayaan['kondisi_pinjaman'],
                'keterangan_pinjaman' => $pembiayaan['keterangan_pinjaman']
            ]);
        }

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data transaksi pembiayaan berhasil ditambahkan.');
    }

    public function indexKolektif()
    {
        return view('admin.inputpembiayaankolektif');
    }
}
