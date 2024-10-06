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

        $loan = PembiayaanModel::where('no_pembiayaan', $request->input('loan_number'))->first();
        if ($loan) {
            return redirect()->route('pembiayaan')->with('error', 'No pembiayaan sudah digunakan silahkan pilih yang lain!');
        } else {

            $data = [
                'no_pembiayaan' => $request->loan_number,
                'nama_pembiayaan' => $request->loan_name,
                'keterangan_pembiayaan' => $request->sloan_desc,
            ];

            PembiayaanModel::create($data);
        }

        return redirect()->route('pembiayaan')->with('success', 'Data pembiayaan berhasil disimpan!');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'edit_loan_number' => 'required',
            'edit_loan_name' => 'nullable',
            'edit_loan_desc' => 'nullable',
        ]);

        $loan = PembiayaanModel::where('no_pembiayaan', $request->input('edit_loan_number'))->first();
        if ($loan) {
            return redirect()->route('pembiayaan')->with('error', 'No pembiayaan sudah digunakan silahkan pilih yang lain!');
        } else {
            // Find loan by ID
            $loanUpdate = PembiayaanModel::findOrFail($id);

            // Update loan data
            $loanUpdate->no_pembiayaan = $request->input('edit_loan_number');
            $loanUpdate->nama_pembiayaan = $request->input('edit_loan_name');
            $loanUpdate->keterangan_pembiayaan = $request->input('edit_loan_desc');

            $loanUpdate->save();
        }

        return redirect()->back()->with('success', 'Data pembiayaan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pembiayaan = PembiayaanModel::findOrFail($id);

        // Delete the pembiayaan from the database
        $pembiayaan->delete();

        return redirect()->back()->with('success', 'Data pembiayaan berhasil dihapus');
    }

    public function getLoanData()
    {
        // Ambil nomor pembiayaan terbesar dari tabel
        $latestPost = PembiayaanModel::all();
        // Kembalikan data dalam bentuk JSON
        return response()->json([
            'loan_data' => $latestPost,
        ]);
    }
}
