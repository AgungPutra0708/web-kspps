<?php

namespace App\Http\Controllers;

use App\Models\SimpananModel;
use Illuminate\Http\Request;

class SavingController extends Controller
{
    public function index()
    {
        return view('admin.simpanan');
    }

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'saving_number' => 'required',
            'saving_name' => 'required',
        ]);

        $data = [
            'no_simpanan' => $request->saving_number,
            'nama_simpanan' => $request->saving_name,
            'keterangan_simpanan' => $request->saving_desc,
        ];

        SimpananModel::create($data);

        return redirect()->route('simpanan')->with('success', 'Data simpanan berhasil disimpan!');
    }

    public function getSavingData()
    {
        // Ambil nomor anggota terbesar dari tabel
        $latestPost = SimpananModel::orderBy('no_simpanan', 'desc')->lockForUpdate()->first();
        $nextNumber = $latestPost ? intval(substr($latestPost->no_simpanan, 2)) + 1 : 1;
        $formattedNumber = 'S-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // Kembalikan data dalam bentuk JSON
        return response()->json([
            'saving_number' => $formattedNumber,
        ]);
    }
}
