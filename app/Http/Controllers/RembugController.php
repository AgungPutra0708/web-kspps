<?php

namespace App\Http\Controllers;

use App\Models\RembugModel;
use Illuminate\Http\Request;

class RembugController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [];
        return view('admin.rembug', $data);
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
        // Validasi input
        $request->validate([
            'group_number' => 'required',
            'group_name' => 'required',
            'group_address' => 'nullable|string', // Sesuaikan jika ada validasi khusus untuk alamat
        ]);

        // Simpan data ke database
        RembugModel::create([
            'no_rembug' => $request->input('group_number'), // Mengambil data dengan benar
            'nama_rembug' => $request->input('group_name'), // Pastikan field ini ada di tabel
            'alamat_rembug' => $request->input('group_address'), // Pastikan field ini ada di tabel
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('kumpulan')->with('success', 'Data kumpulan berhasil disimpan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'edit_group_name' => 'required',
            'edit_group_address' => 'nullable',
        ]);

        // Find group by ID
        $group = RembugModel::findOrFail($id);

        // Update group data
        $group->nama_rembug = $request->input('edit_group_name');
        $group->alamat_rembug = $request->input('edit_group_address');

        $group->save();

        return redirect()->back()->with('success', 'Data rembug berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $group = RembugModel::findOrFail($id);

        // Delete the group from the database
        $group->delete();

        return redirect()->back()->with('success', 'Data rembug berhasil dihapus');
    }

    public function getLatestRembugNumber()
    {
        // Ambil nomor post terbesar dari tabel
        $latestPost = RembugModel::orderBy('no_rembug', 'desc')->lockForUpdate()->first();

        // Jika ada post, tambah 1 dari nomor post terakhir
        $nextNumber = $latestPost ? intval(substr($latestPost->no_rembug, 2)) + 1 : 1;

        // Format nomor post dengan prefix 'R-' dan pad angka menjadi 4 digit (contoh: R-0001)
        return 'R-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
