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
        $simpananArray = json_decode($request->simpanan_array, true);

        if (empty($simpananArray)) {
            return redirect()->back()->withErrors(['message' => 'Data simpanan kosong']);
        }

        DB::beginTransaction();

        try {

            $transaksiIds = []; // ← kumpulkan semua id transaksi

            foreach ($simpananArray as $simpanan) {

                // =============================
                // REKENING ASAL
                // =============================

                $rekeningSimpananAsal = RekeningSimpananModel::where('id_simpanan', $simpanan['id_simpanan_asal'])
                    ->where('id_anggota', $simpanan['id_anggota_asal'])
                    ->first();

                if (!$rekeningSimpananAsal) {

                    $kodeSimpanan = SimpananModel::find($simpanan['id_simpanan_asal']);
                    $kodeAnggota = AnggotaModel::find($simpanan['id_anggota_asal']);

                    if (!$kodeSimpanan || !$kodeAnggota) {
                        throw new \Exception('Data Simpanan Asal atau Anggota tidak ditemukan.');
                    }

                    $memberCodePart = substr(
                        $kodeAnggota->no_anggota,
                        strpos($kodeAnggota->no_anggota, '-') + 1
                    );

                    $rekeningSimpananAsal = RekeningSimpananModel::create([
                        'no_rekening_simpanan' => $kodeSimpanan->no_simpanan . '-' . $memberCodePart,
                        'id_anggota' => $simpanan['id_anggota_asal'],
                        'id_simpanan' => $simpanan['id_simpanan_asal'],
                    ]);
                }

                $cleanAmount = str_replace('.', '', $simpanan['nominal_setoran']);
                $cleanAmount = str_replace(',', '.', $cleanAmount);

                // Hitung saldo akhir
                $saldoAkhir = TransaksiSimpananModel::where('id_rekening_simpanan', $rekeningSimpananAsal->id)
                    ->select(DB::raw('SUM(CASE WHEN metode_transaksi = "+" THEN jumlah_setoran ELSE -jumlah_setoran END) as saldo_akhir'))
                    ->value('saldo_akhir') ?? 0;

                if ($saldoAkhir < $cleanAmount) {
                    throw new \Exception('Saldo tidak mencukupi.');
                }

                // =============================
                // REKENING TUJUAN
                // =============================

                $rekeningSimpananTujuan = RekeningSimpananModel::where('id_simpanan', $simpanan['id_simpanan_tujuan'])
                    ->where('id_anggota', $simpanan['id_anggota_tujuan'])
                    ->first();

                if (!$rekeningSimpananTujuan) {

                    $kodeSimpanan = SimpananModel::find($simpanan['id_simpanan_tujuan']);
                    $kodeAnggota = AnggotaModel::find($simpanan['id_anggota_tujuan']);

                    if (!$kodeSimpanan || !$kodeAnggota) {
                        throw new \Exception('Data Simpanan Tujuan atau Anggota tidak ditemukan.');
                    }

                    $memberCodePart = substr(
                        $kodeAnggota->no_anggota,
                        strpos($kodeAnggota->no_anggota, '-') + 1
                    );

                    $rekeningSimpananTujuan = RekeningSimpananModel::create([
                        'no_rekening_simpanan' => $kodeSimpanan->no_simpanan . '-' . $memberCodePart,
                        'id_anggota' => $simpanan['id_anggota_tujuan'],
                        'id_simpanan' => $simpanan['id_simpanan_tujuan'],
                    ]);
                }

                // =============================
                // TRANSAKSI ASAL (-)
                // =============================

                $transaksiAsal = TransaksiSimpananModel::create([
                    'id_rekening_simpanan' => $rekeningSimpananAsal->id,
                    'id_simpanan' => $simpanan['id_simpanan_asal'],
                    'id_anggota' => $simpanan['id_anggota_asal'],
                    'metode_transaksi' => "-",
                    'jumlah_setoran' => $cleanAmount,
                    'keterangan' => $simpanan['keterangan'],
                    'tanggal_transaksi' => Carbon::now(),
                ]);

                $transaksiIds[] = $transaksiAsal->id;

                // =============================
                // TRANSAKSI TUJUAN (+)
                // =============================

                $transaksiTujuan = TransaksiSimpananModel::create([
                    'id_rekening_simpanan' => $rekeningSimpananTujuan->id,
                    'id_simpanan' => $simpanan['id_simpanan_tujuan'],
                    'id_anggota' => $simpanan['id_anggota_tujuan'],
                    'metode_transaksi' => "+",
                    'jumlah_setoran' => $cleanAmount,
                    'keterangan' => $simpanan['keterangan'],
                    'tanggal_transaksi' => Carbon::now(),
                ]);

                $transaksiIds[] = $transaksiTujuan->id;
            }

            DB::commit();

            return redirect()->back()->with([
                'success'   => 'Data pindah buku berhasil ditambahkan.',
                'print_url' => route('simpanan.print', [
                    'ids' => implode(',', $transaksiIds)
                ])
            ]);
        } catch (\Exception $e) {

            DB::rollback();

            return redirect()->back()->with([
                'error' => $e->getMessage()
            ]);
        }
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
