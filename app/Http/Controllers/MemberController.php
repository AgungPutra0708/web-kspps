<?php

namespace App\Http\Controllers;

use App\Models\AnggotaModel;
use App\Models\RembugModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class MemberController extends Controller
{
    public function index()
    {
        return view('anggota');
    }

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'member_number' => 'required',
            'member_name' => 'required',
            'member_group' => 'required',
            'member_phone' => 'nullable|digits_between:10,13',
            'member_card' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file foto KTP
        ]);

        // Menyimpan file foto KTP jika ada
        if ($request->hasFile('member_card')) {
            // Simpan di folder 'private/ktp' di dalam storage/app/private
            $memberCardPath = $request->file('member_card')->store('private/ktp');
        } else {
            $memberCardPath = null; // Jika tidak ada foto KTP
        }

        $data = [
            'no_anggota' => $request->member_number,
            'nama_anggota' => $request->member_name,
            'id_rembug' => $request->member_group, // ID Rembug
            'phone_anggota' => $request->member_phone,
            'idcard_anggota' => $memberCardPath, // Simpan path file KTP
        ];

        // Menyimpan data ke tabel anggotas
        AnggotaModel::create($data);

        // Redirect ke halaman anggota dengan pesan sukses
        return redirect()->route('anggota')->with('success', 'Data anggota berhasil disimpan!');
    }
    public function getMemberAndRembugData()
    {
        // Ambil nomor anggota terbesar dari tabel
        $latestPost = AnggotaModel::orderBy('no_anggota', 'desc')->lockForUpdate()->first();
        $nextNumber = $latestPost ? intval(substr($latestPost->no_anggota, 2)) + 1 : 1;
        $formattedNumber = 'A-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // Ambil data rembug
        $rembugData = RembugModel::all();

        // Mengenkripsi id rembug menggunakan Crypt::encrypt()
        $encryptedData = $rembugData->map(function ($item) {
            return [
                'id' => $item->id,  // Enkripsi id
                'no_rembug' => $item->no_rembug,
                'nama_rembug' => $item->nama_rembug,
            ];
        });

        // Kembalikan data dalam bentuk JSON
        return response()->json([
            'member_number' => $formattedNumber,
            'rembug_data' => $encryptedData
        ]);
    }
}
