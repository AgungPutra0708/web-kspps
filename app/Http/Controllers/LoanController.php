<?php

namespace App\Http\Controllers;

use App\Models\PembiayaanModel;
use App\Models\PinjamanModel;
use App\Models\TransaksiPinjamanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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

    public function history($encryptedId)
    {
        // Dekripsi ID pembiayaan
        $id_pinjaman = Crypt::decrypt($encryptedId);

        // Ambil data history pembiayaan berdasarkan id_pinjaman
        $historyData = TransaksiPinjamanModel::where('id_pinjaman', $id_pinjaman)->get();

        return view('admin.historyloan', compact('historyData', 'id_pinjaman'));
    }
    // Fungsi history tetap sama

    public function edit($encryptedId)
    {
        // Dekripsi ID transaksi pembiayaan
        $id_transaksi = Crypt::decrypt($encryptedId);

        // Ambil data transaksi pembiayaan
        $transaction = TransaksiPinjamanModel::findOrFail($id_transaksi);

        return view('admin.edithistorypembiayaan', compact('transaction'));
    }

    public function destroyTransaction($encryptedId)
    {
        // Dekripsi ID transaksi pembiayaan
        $id_transaksi = Crypt::decrypt($encryptedId);

        // Ambil data transaksi pembiayaan
        $transaction = TransaksiPinjamanModel::findOrFail($id_transaksi);

        // Ambil pinjaman terkait dan sesuaikan nilai sisa_besar_pinjaman dan sisa_besar_margin
        $pinjaman = PinjamanModel::find($transaction->id_pinjaman);
        if ($pinjaman) {
            $pinjaman->sisa_besar_pinjaman += $transaction->angsur_pinjaman;
            $pinjaman->sisa_besar_margin += $transaction->angsur_margin;
            $pinjaman->save();
        }

        // Hapus transaksi pembiayaan
        $transaction->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus dan nilai sisa pembiayaan telah diperbarui.');
    }

    public function updateHistory(Request $request, $encryptedId)
    {
        // Dekripsi ID transaksi
        $id_transaksi = Crypt::decrypt($encryptedId);

        // Ambil transaksi dan pinjaman terkait
        $transaction = TransaksiPinjamanModel::findOrFail($id_transaksi);
        $pinjaman = PinjamanModel::find($transaction->id_pinjaman);

        if ($pinjaman) {
            // Hitung perbedaan angsuran sebelum dan sesudah update
            $differencePokok = $transaction->angsur_pinjaman - $request->input('angsur_pinjaman');
            $differenceMargin = $transaction->angsur_margin - $request->input('angsur_margin');

            // Update sisa_besar_pinjaman dan sisa_besar_margin di PinjamanModel
            $pinjaman->sisa_besar_pinjaman += $differencePokok;
            $pinjaman->sisa_besar_margin += $differenceMargin;
            $pinjaman->save();
        }

        // Update transaksi pembiayaan
        $transaction->angsur_pinjaman = $request->input('angsur_pinjaman');
        $transaction->angsur_margin = $request->input('angsur_margin');
        $transaction->save();

        return redirect()->route('loan.history', Crypt::encrypt($transaction->id_pinjaman))
            ->with('success', 'Transaksi berhasil diperbarui dan nilai sisa pembiayaan telah diperbarui.');
    }
}
