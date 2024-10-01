<?php

namespace App\Http\Controllers;

use App\Models\AnggotaModel;
use App\Models\UserMemberModel;
use Illuminate\Http\Request;

class UserMemberController extends Controller
{
    public function index()
    {
        return view('admin.user');
    }

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'member_name' => 'required',
            'member_username' => 'required',
            'member_password' => 'required',
        ]);

        $data = [
            'id_anggota' => $request->member_name,
            'username' => $request->member_username,
            'password' => $request->member_password,
        ];

        // Menyimpan data ke tabel anggotas
        UserMemberModel::create($data);

        // Redirect ke halaman anggota dengan pesan sukses
        return redirect()->route('management_user')->with('success', 'Data user anggota berhasil disimpan!');
    }

    public function getMemberData()
    {
        // Ambil data anggota beserta nama rembug menggunakan eager loading
        $anggotaData = AnggotaModel::with('rembug')->get();

        // Transform data untuk mengirimkan response JSON
        $encryptedData = $anggotaData->map(function ($item) {
            return [
                'id' => $item->id,  // ID Anggota
                'no_anggota' => $item->no_anggota, // No Anggota
                'nama_anggota' => $item->nama_anggota, // Nama Anggota
                'nama_rembug' => $item->rembug->nama_rembug ?? null, // Nama Rembug dari tabel rembug
            ];
        });

        // Kembalikan data dalam bentuk JSON
        return response()->json([
            'anggota_data' => $encryptedData
        ]);
    }
}
