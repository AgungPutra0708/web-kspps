<?php

namespace App\Http\Controllers;

use App\Models\AnggotaModel;
use App\Models\PinjamanModel;
use App\Models\RekeningSimpananModel;
use App\Models\SimpananModel;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class LoanSavingCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'dataAnggota' => AnggotaModel::all(),
        ];
        return view('admin.cek', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getSavingLoanData(Request $request)
    {
        $id_anggota = Crypt::decrypt($request->input('id_anggota'));

        // Ambil semua data simpanan
        $simpananData = SimpananModel::all();
        $pinjamanData = PinjamanModel::where('id_anggota', $id_anggota)->get();

        // Filter simpanan dan hitung saldo akhir berdasarkan id_anggota
        $filteredDataSimpanan = $simpananData->filter(function ($item) use ($id_anggota) {
            // Hanya tampilkan data jika ada rekening simpanan yang sesuai
            return RekeningSimpananModel::where('id_anggota', $id_anggota)
                ->where('id_simpanan', $item->id)
                ->exists();
        })->map(function ($item) use ($id_anggota) {
            $saldoAkhir = $item->transaksiSimpanans($id_anggota)
                ->select(DB::raw('SUM(CASE WHEN metode_transaksi = "+" THEN jumlah_setoran ELSE -jumlah_setoran END) as saldo_akhir'))
                ->value('saldo_akhir');

            $dataRekeningSimpanan = RekeningSimpananModel::where('id_anggota', $id_anggota)->where('id_simpanan', $item->id)->first();

            return [
                'id_simpanan' => Crypt::encrypt($item->id),
                'id_anggota' => $id_anggota,
                'no_anggota' => $item->anggota->no_anggota ?? null,
                'nama_anggota' => $item->anggota->nama_anggota ?? null,
                'no_rekening_simpanan' => $dataRekeningSimpanan->no_rekening_simpanan ?? "-",
                'id_rekening_simpanan' => isset($dataRekeningSimpanan->id) ? Crypt::encrypt($dataRekeningSimpanan->id) : null,
                'no_simpanan' => $item->no_simpanan ?? null,
                'nama_simpanan' => $item->nama_simpanan ?? null,
                'saldo_akhir' => $saldoAkhir ?? 0,
                'utama' => $item->utama ?? null,
            ];
        })->values();

        // Map pinjamanData untuk mengenkripsi id_pinjaman
        $filteredDataPinjaman = $pinjamanData->map(function ($item) {
            return [
                'id_pinjaman' => Crypt::encrypt($item->id),  // Enkripsi ID Pinjaman
                'no_pinjaman' => $item->no_pinjaman,
                'besar_pinjaman' => $item->besar_pinjaman,
                'besar_margin' => $item->besar_margin,
                'lama_pinjaman' => $item->lama_pinjaman,
                'status_pinjaman' => $item->status_pinjaman,
            ];
        });

        // Kembalikan data dalam bentuk JSON
        return response()->json([
            'saving_data' => $filteredDataSimpanan,
            'loan_data' => $filteredDataPinjaman,
        ]);
    }

    public function generateQRSimpanan(string $id, QrCodeService $qr)
    {
        $id = Crypt::decrypt($id);
        
        $rekeningSimpanan = RekeningSimpananModel::with('anggota')->findOrFail($id);
        
        $simpananData = 'simpanan|' . $rekeningSimpanan->no_rekening_simpanan . '|' . $rekeningSimpanan->anggota->nama_anggota;

        $encryptedData = Crypt::encryptString($simpananData);

        $svg = $qr->generateTanpaLogo($encryptedData);

        return response($svg)
            ->header('Content-Type', 'image/svg+xml');
    }

    public function generateQRPembiayaan(string $id, QrCodeService $qr)
    {
        $id = Crypt::decrypt($id);
        
        $rekeningPinjaman = PinjamanModel::with('anggota')->findOrFail($id);
        
        $pinjamanData = 'pembiayaan|' . $rekeningPinjaman->no_pinjaman . '|' . $rekeningPinjaman->anggota->nama_anggota;

        $encryptedData = Crypt::encryptString($pinjamanData);

        $svg = $qr->generateTanpaLogo($encryptedData);

        return response($svg)
            ->header('Content-Type', 'image/svg+xml');
    }
}
