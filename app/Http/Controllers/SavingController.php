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

        $saving = SimpananModel::where('no_simpanan', $request->saving_number)->first();
        if ($saving) {
            return redirect()->route('simpanan')->with('error', 'No simpanan sudah digunakan silahkan pilih yang lain!');
        } else {
            $data = [
                'no_simpanan' => $request->saving_number,
                'nama_simpanan' => $request->saving_name,
                'keterangan_simpanan' => $request->saving_desc,
            ];

            SimpananModel::create($data);
        }

        return redirect()->route('simpanan')->with('success', 'Data simpanan berhasil disimpan!');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'edit_saving_number' => 'required',
            'edit_saving_name' => 'nullable',
            'edit_saving_desc' => 'nullable',
        ]);

        $saving = SimpananModel::where('no_simpanan', $request->input('edit_saving_number'))->first();
        if ($saving) {
            return redirect()->route('simpanan')->with('error', 'No simpanan sudah digunakan silahkan pilih yang lain!');
        } else {
            // Find saving by ID
            $savingUpdate = SimpananModel::findOrFail($id);

            // Update saving data
            $savingUpdate->no_simpanan = $request->input('edit_saving_number');
            $savingUpdate->nama_simpanan = $request->input('edit_saving_name');
            $savingUpdate->keterangan_simpanan = $request->input('edit_saving_desc');

            $savingUpdate->save();
        }

        return redirect()->back()->with('success', 'Data simpanan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $saving = SimpananModel::findOrFail($id);

        // Delete the saving from the database
        $saving->delete();

        return redirect()->back()->with('success', 'Data simpanan berhasil dihapus');
    }
    public function getSavingData()
    {
        // Ambil nomor anggota terbesar dari tabel
        $latestPost = SimpananModel::all();
        // Kembalikan data dalam bentuk JSON
        return response()->json([
            'saving_data' => $latestPost,
        ]);
    }
}
