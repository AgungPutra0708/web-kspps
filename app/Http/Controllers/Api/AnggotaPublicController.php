<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnggotaModel;
use App\Models\PinjamanModel;
use App\Models\ProfileKoperasiModel;
use App\Models\RekeningSimpananModel;
use App\Models\SimpananModel;
use App\Models\UserMemberModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AnggotaPublicController extends Controller
{
    public function show($token)
    {
        try {
            $id = Crypt::decryptString(urldecode($token));
        } catch (\Exception $e) {
            abort(404);
        }

        $user = UserMemberModel::with('anggota')
            ->where('id', $id)
            ->where('is_condition', 0)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Anggota tidak aktif'
            ], 403);
        }

        // Ambil semua rekening simpanan berdasarkan id_anggota
        $dataRekeningSimpanan = RekeningSimpananModel::where('id_anggota', $user->id_user)->get();
        // Ambil semua data simpanan
        $simpananData = SimpananModel::all();
        // Variabel untuk total saldo simpanan utama
        $totalSaldoUtama = 0;
        $idSimpananUtama = null; // Variabel untuk menyimpan ID simpanan utama
        // Simpanan yang akan ditampilkan
        $dataSimpanan = [];
        foreach ($dataRekeningSimpanan as $rekening) {
            // Ambil data simpanan berdasarkan id_simpanan dari rekening
            $simpanan = $simpananData->firstWhere('id', $rekening->id_simpanan);
            if ($simpanan) {
                // Hitung saldo_akhir berdasarkan transaksi_simpanans untuk id_anggota dan id_simpanan
                $saldoAkhir = $simpanan->transaksiSimpanans($user->id_user)
                    ->select(DB::raw('SUM(CASE WHEN metode_transaksi = "+" THEN jumlah_setoran ELSE -jumlah_setoran END) as saldo_akhir'))
                    ->where('id_simpanan', $rekening->id_simpanan)
                    ->value('saldo_akhir');
                if ($saldoAkhir != 0) {
                    // Jika simpanan utama, tambahkan ke total saldo utama dan simpan ID-nya
                    if ($simpanan->utama == 'true' && $simpanan->nama_simpanan == 'Modal Wajib') {
                        $totalSaldoUtama += $saldoAkhir;
                        $idSimpananUtama = $simpanan->id; // Simpan ID simpanan utama
                    }
                    // Tambahkan data simpanan ke array dataSimpanan
                    $dataSimpanan[] = [
                        'id_simpanan' => Crypt::encrypt($simpanan->id),  // ID Simpanan
                        'id_anggota' => $user->id_user,  // ID Anggota yang sedang difilter
                        'no_anggota' => $simpanan->anggota->no_anggota ?? null, // No Anggota dari tabel anggota
                        'nama_anggota' => $simpanan->anggota->nama_anggota ?? null, // Nama Anggota dari tabel anggota
                        'no_rekening_simpanan' => $rekening->no_rekening_simpanan ?? "-", // No Simpanan
                        'no_simpanan' => $simpanan->no_simpanan ?? null, // No Simpanan
                        'nama_simpanan' => $simpanan->nama_simpanan ?? null, // Nama Simpanan
                        'saldo_akhir' => $saldoAkhir, // Saldo akhir dari tabel transaksi_simpanans
                        'utama' => $simpanan->utama,
                    ];
                }
            }
        }

        // Ambil semua data pinjaman
        $pinjamanData = PinjamanModel::with('pembiayaan')->where('id_anggota', $user->id_user)->get();
        // Map pinjamanData untuk mengenkripsi id_pinjaman
        $dataPinjaman = $pinjamanData->map(function ($item) {
            return [
                'id_pinjaman' => Crypt::encrypt($item->id),  // Enkripsi ID Pinjaman
                'no_pinjaman' => $item->no_pinjaman,
                'nama_pembiayaan' => $item->pembiayaan->nama_pembiayaan,
                'sisa_besar_pinjaman' => $item->sisa_besar_pinjaman,
                'sisa_besar_margin' => $item->sisa_besar_margin,
                'besar_margin' => $item->besar_margin,
                'lama_pinjaman' => $item->lama_pinjaman,
                'status_pinjaman' => $item->status_pinjaman == "done" ? "Lunas" : "Berjalan",
                'kondisi_pinjaman' => $item->kondisi_pinjaman,
            ];
        });

        $no_user = AnggotaModel::with('rembug')->find($user->id_user)->no_anggota;
        $dataProfile = ProfileKoperasiModel::first();
        $spreadsheetId = $dataProfile && $dataProfile->id_spreadsheet_shu ? $dataProfile->id_spreadsheet_shu : ''; // Replace with your Spreadsheet ID
        $apiKey = $dataProfile && $dataProfile->id_api_spreadsheet_shu ? $dataProfile->id_api_spreadsheet_shu : ''; // Replace with your API Key

        // Fetch data from Google Sheets API
        $response = Http::get("https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/Sheet1?key={$apiKey}");

        if ($response->successful()) {
            $data = $response->json()['values'] ?? []; // Get the data values

            // Filter data based on no_user (the first column)
            $filteredData = array_filter($data, function ($row) use ($no_user) {
                // Check if the first element (index 0) matches no_user
                return isset($row[0]) && $row[0] === $no_user;
            });

            // Reset array keys for filtered data
            $filteredData = array_values($filteredData);
        } else {
            $filteredData = []; // Set data to empty if the request failed
        }

        return response()->json([
            'status' => 'valid',
            'data' => [
                'no_anggota' => $user->anggota->no_anggota,
                'nama'       => $user->anggota->nama_anggota,
                'status'     => 'AKTIF',
                'simpanan' => $dataSimpanan,
                'total_simpanan_utama' => $totalSaldoUtama,
                'pinjaman' => $dataPinjaman,
                'shu' => $filteredData
            ],

        ]);
    }
}
