<?php

namespace App\Http\Controllers;

use App\Models\PetugasModel;
use App\Models\UserMemberModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.petugas');
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
        // Validasi data input
        $request->validate([
            'petugas_number' => 'required',
            'petugas_name' => 'required',
            'petugas_username' => 'required',
            'petugas_password' => 'required',
        ]);

        $user = UserMemberModel::where('username', $request->petugas_username)->first();
        if ($user) {
            return redirect()->route('petugas')->with('error', 'Username sudah digunakan silahkan pilih yang lain!');
        } else {
            $dataPetugas = [
                'no_petugas' => $request->petugas_number,
                'nama_petugas' => $request->petugas_name,
            ];

            // Menyimpan data ke tabel anggotas
            $id = PetugasModel::create($dataPetugas);

            $dataPetugasUser = [
                'id_user' => $id->id,
                'status' => "petugas",
                'username' => $request->petugas_username,
                'password' => Hash::make($request->petugas_password),
            ];

            UserMemberModel::create($dataPetugasUser);
            // Redirect ke halaman anggota dengan pesan sukses
            return redirect()->route('petugas')->with('success', 'Data petugas berhasil disimpan!');
        }
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

    public function getLatestPetugasNumber()
    {
        // Ambil nomor post terbesar dari tabel
        $latestPost = PetugasModel::orderBy('no_petugas', 'desc')->lockForUpdate()->first();

        // Jika ada post, tambah 1 dari nomor post terakhir
        $nextNumber = $latestPost ? intval(substr($latestPost->no_petugas, 2)) + 1 : 1;

        // Format nomor post dengan prefix 'R-' dan pad angka menjadi 4 digit (contoh: R-0001)
        return 'P-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
