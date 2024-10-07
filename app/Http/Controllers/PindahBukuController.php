<?php

namespace App\Http\Controllers;

use App\Models\AnggotaModel;
use App\Models\RekeningSimpananModel;
use App\Models\SimpananModel;
use App\Models\TransaksiSimpananModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PindahBukuController extends Controller
{
    public function index()
    {
        $data = [
            'dataSimpanan' => SimpananModel::all(),
            'dataAnggota' => AnggotaModel::all(),
        ];
        return view('admin.pindahbuku', $data);
    }

    public function store(Request $request)
    {
        // Decode JSON array dari input hidden
        $simpananArray = json_decode($request->simpanan_array, true);

        // Validasi array jika diperlukan
        if (empty($simpananArray)) {
            return redirect()->back()->withErrors(['message' => 'Data simpanan kosong']);
        }

        // Loop melalui setiap item dalam array dan simpan ke database
        foreach ($simpananArray as $simpanan) {
            // Ambil data anggota dengan filter id_rembug
            $anggotaData = AnggotaModel::where('id', $simpanan['id_anggota_asal'])->get();

            // Ambil saldo akhir dari transaksi simpanan berdasarkan id_anggota dan id_simpanan asal
            $rekeningSimpananAsal = RekeningSimpananModel::where('id_simpanan', $simpanan['id_simpanan_asal'])
                ->where('id_anggota', $simpanan['id_anggota_asal'])
                ->first();

            if (!$rekeningSimpananAsal) {
                $kodeSimpanan = SimpananModel::find($simpanan['id_simpanan_asal']);
                $kodeAnggota = AnggotaModel::find($simpanan['id_anggota_asal']);

                // Buat kode rekening simpanan baru jika belum ada
                $memberCodePart = substr($kodeAnggota->no_anggota, strpos($kodeAnggota->no_anggota, '-') + 1);
                $noRekeningSimpanan = $kodeSimpanan->no_simpanan . '-' . $memberCodePart;

                $rekeningSimpananAsal = RekeningSimpananModel::create([
                    'no_rekening_simpanan' => $noRekeningSimpanan,
                    'id_anggota' => $simpanan['id_anggota_asal'],
                    'id_simpanan' => $simpanan['id_simpanan_asal'],
                ]);
            }

            // Hitung saldo akhir dari transaksi simpanan
            $saldoAkhir = TransaksiSimpananModel::where('id_rekening_simpanan', $rekeningSimpananAsal->id)
                ->select(DB::raw('SUM(CASE WHEN metode_transaksi = "+" THEN jumlah_setoran ELSE -jumlah_setoran END) as saldo_akhir'))
                ->value('saldo_akhir');

            // Pengecekan saldo: jika saldo akhir kurang dari nominal setoran, kembalikan pesan error
            if ($saldoAkhir < $simpanan['nominal_setoran']) {
                return redirect()->back()->with(['error' => 'Saldo tidak mencukupi']);
            }

            // Lanjutkan ke proses simpanan jika saldo mencukupi
            $rekeningSimpananTujuan = RekeningSimpananModel::where('id_simpanan', $simpanan['id_simpanan_tujuan'])
                ->where('id_anggota', $simpanan['id_anggota_tujuan'])
                ->first();

            if (!$rekeningSimpananTujuan) {
                $kodeSimpanan = SimpananModel::find($simpanan['id_simpanan_tujuan']);
                $kodeAnggota = AnggotaModel::find($simpanan['id_anggota_tujuan']);
                $memberCodePart = substr($kodeAnggota->no_anggota, strpos($kodeAnggota->no_anggota, '-') + 1);
                $noRekeningSimpanan = $kodeSimpanan->no_simpanan . '-' . $memberCodePart;

                $rekeningSimpananTujuan = RekeningSimpananModel::create([
                    'no_rekening_simpanan' => $noRekeningSimpanan,
                    'id_anggota' => $simpanan['id_anggota_tujuan'],
                    'id_simpanan' => $simpanan['id_simpanan_tujuan'],
                ]);
            }

            // Create Transaksi Simpanan asal
            TransaksiSimpananModel::create([
                'id_rekening_simpanan' => $rekeningSimpananAsal->id,
                'id_simpanan' => $simpanan['id_simpanan_asal'],
                'id_anggota' => $simpanan['id_anggota_asal'],
                'metode_transaksi' => "-",
                'jumlah_setoran' => $simpanan['nominal_setoran'],
                'keterangan' => $simpanan['keterangan'],
                'tanggal_transaksi' => Carbon::now(),
            ]);

            // Create Transaksi Simpanan tujuan
            TransaksiSimpananModel::create([
                'id_rekening_simpanan' => $rekeningSimpananTujuan->id,
                'id_simpanan' => $simpanan['id_simpanan_tujuan'],
                'id_anggota' => $simpanan['id_anggota_tujuan'],
                'metode_transaksi' => "+",
                'jumlah_setoran' => $simpanan['nominal_setoran'],
                'keterangan' => $simpanan['keterangan'],
                'tanggal_transaksi' => Carbon::now(),
            ]);
        }

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data pindah buku berhasil ditambahkan.');
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
