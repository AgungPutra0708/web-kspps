<?php

namespace App\Http\Controllers;

use App\Models\PembiayaanModel;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        return view('admin.pembiayaan');
    }

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'loan_number' => 'required',
            'loan_name' => 'required',
        ]);

        $data = [
            'no_pembiayaan' => $request->loan_number,
            'nama_pembiayaan' => $request->loan_name,
            'keterangan_pembiayaan' => $request->sloan_desc,
        ];

        PembiayaanModel::create($data);

        return redirect()->route('pembiayaan')->with('success', 'Data pembiayaan berhasil disimpan!');
    }

    public function getLoanData()
    {
        // Ambil nomor anggota terbesar dari tabel
        $latestPost = PembiayaanModel::orderBy('no_pembiayaan', 'desc')->lockForUpdate()->first();
        $nextNumber = $latestPost ? intval(substr($latestPost->no_pembiayaan, 2)) + 1 : 1;
        $formattedNumber = 'P-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // Kembalikan data dalam bentuk JSON
        return response()->json([
            'loan_number' => $formattedNumber,
        ]);
    }
}
