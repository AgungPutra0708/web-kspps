<?php

namespace App\Http\Controllers;

use App\Models\ProfileKoperasiModel;
use App\Models\RekeningSimpananModel;
use App\Models\SimpananModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EksternalAnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexMarket()
    {
        $dataProfile = ProfileKoperasiModel::first();
        return view('anggota.market', compact('dataProfile'));
    }

    /**
     * Display a listing of the resource.
     */
    public function indexTamwil()
    {
        $id_anggota = Session::get('id_user');
        // Ambil semua rekening simpanan berdasarkan id_anggota
        $dataRekeningSimpanan = RekeningSimpananModel::with('simpanan')
            ->where('id_anggota', $id_anggota)
            ->whereHas('simpanan', function ($query) {
                $query->whereNull('deleted_at')
                    ->where('utama', 'false');
            })
            ->get();

        $simpananData = SimpananModel::all();

        $dataSimpanan = [];
        foreach ($dataRekeningSimpanan as $rekening) {
            // Ambil data simpanan berdasarkan id_simpanan dari rekening
            $simpanan = $simpananData->firstWhere('id', $rekening->id_simpanan);
            if ($simpanan) {
                // Hitung saldo_akhir berdasarkan transaksi_simpanans untuk id_anggota dan id_simpanan
                $saldoAkhir = $simpanan->transaksiSimpanans($id_anggota)
                    ->select(DB::raw('SUM(CASE WHEN metode_transaksi = "+" THEN jumlah_setoran ELSE -jumlah_setoran END) as saldo_akhir'))
                    ->where('id_simpanan', $rekening->id_simpanan)
                    ->value('saldo_akhir');
                if ($saldoAkhir != 0) {
                    // Tambahkan data simpanan ke array dataSimpanan
                    $dataSimpanan[] = [
                        'id_simpanan' => $simpanan->id,  // ID Simpanan
                        'id_anggota' => $id_anggota,  // ID Anggota yang sedang difilter
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
        return view('anggota.tamwil', compact('dataSimpanan'));
    }

    public function sendToWhatsappSetoran(Request $request)
    {
        $noAnggota   = $request->nomorAnggotaSetoran;
        $namaLengkap = $request->namaLengkapSetoran;
        $noRekening  = $request->nomorRekeningSetoran;
        $jumlah      = number_format($request->jumlahSetoran, 0, ',', '.');
        $metode      = $request->setoranMelalui;
        $alasan      = $request->alasanSetoran;

        // Inisialisasi variabel untuk data tambahan
        $additionalInfo = '';

        // Handle data tambahan berdasarkan metode penarikan
        if ($metode === 'Tunai') {
            $hariSetor = $request->hariSetor;
            $jamSetor = $request->jamSetor;
            $additionalInfo = "- Jadwal Setoran: *{$hariSetor}, {$jamSetor}*";
        }

        // Upload dan ambil URL publik
        $path = $request->file('buktiTransferSetoran')->store('bukti-transfer', 'public');
        $fullUrl = asset(Storage::url($path));

        // Shorten dengan CleanURI
        $shortUrl = $fullUrl;
        try {
            $shortResponse = Http::asForm()->post('https://cleanuri.com/api/v1/shorten', [
                'url' => $fullUrl
            ]);
            if ($shortResponse->successful() && isset($shortResponse['result_url'])) {
                $shortUrl = $shortResponse['result_url'];
            }
        } catch (\Exception $e) {
            Log::error("CleanURI error: " . $e->getMessage());
        }

        // Format pesan WhatsApp
        $message = <<<EOT
            Assalamualaikum Wr. Wb.
            CS BMT Sarana yang terhormat,

            Saya yang bertanda tangan di bawah ini:
            - Nomor Anggota: *{$noAnggota}*
            - Nama Lengkap: *{$namaLengkap}*
            - Nomor Rekening: *{$noRekening}*
            - Jumlah Setoran: *Rp {$jumlah}*
            - Metode Setoran: *{$metode}*
            - Alasan Setoran: *{$alasan}*
            - Bukti Transfer: {$shortUrl}
            {$additionalInfo}

            Dengan ini menyampaikan bahwa telah melakukan SETORAN TABUNGAN.

            Mohon untuk dapat diproses lebih lanjut.

            Terima kasih atas perhatian dan kerjasamanya.

            Wassalamualaikum Wr. Wb.
            EOT;

        $waNumber = "6281217283960"; // Ganti dengan nomor WA CS kamu
        $waLink = "https://wa.me/{$waNumber}?text=" . urlencode($message);

        return response()->json([
            'success' => true,
            'whatsapp_url' => $waLink,
            'bukti_url' => $shortUrl
        ]);
    }

    public function sendToWhatsappPenarikan(Request $request)
    {
        $noAnggota   = $request->nomorAnggotaPenarikan;
        $namaLengkap = $request->namaLengkapPenarikan;
        $noRekening  = $request->nomorRekeningPenarikan;
        $jumlah      = number_format($request->jumlahPenarikan, 0, ',', '.');
        $metode      = $request->penarikanMelalui;
        $alasan      = $request->alasanPenarikan;

        // Inisialisasi variabel untuk data tambahan
        $additionalInfo = '';

        // Handle data tambahan berdasarkan metode penarikan
        if ($metode === 'Tunai Langsung Kasir') {
            $hariAmbil = $request->hariAmbil;
            $jamAmbil = $request->jamAmbil;
            $additionalInfo = "- Jadwal Pengambilan: *{$hariAmbil}, {$jamAmbil}*";
        } elseif ($metode === 'Transfer Ke Bank') {
            $namaBank = $request->namaBank;
            $nomorRekeningBank = $request->nomorRekeningBank;
            $atasNamaBank = $request->atasNamaBank;
            $additionalInfo = "- Bank Tujuan: *{$namaBank}*\n- Nomor Rekening: *{$nomorRekeningBank}*\n- Atas Nama: *{$atasNamaBank}*";
        } elseif ($metode === 'Transfer Ke E Wallet') {
            $jenisEwallet = $request->jenisEwallet;
            $nomorHandphone = $request->nomorHandphone;
            $atasNamaEwallet = $request->atasNamaEwallet;
            $additionalInfo = "- Jenis E-Wallet: *{$jenisEwallet}*\n- Nomor Handphone: *{$nomorHandphone}*\n- Atas Nama: *{$atasNamaEwallet}*";
        }

        // Format pesan WhatsApp
        $message = <<<EOT
            Assalamualaikum Wr. Wb.
            CS BMT Sarana yang terhormat,

            Saya yang bertanda tangan di bawah ini:
            - Nomor Anggota: *{$noAnggota}*
            - Nama Lengkap: *{$namaLengkap}*
            - Nomor Rekening: *{$noRekening}*
            - Jumlah Penarikan: *Rp {$jumlah}*
            - Metode Penarikan: *{$metode}*
            - Alasan Penarikan: *{$alasan}*
            {$additionalInfo}

            Dengan ini menyampaikan bahwa telah melakukan PENARIKAN TABUNGAN.

            Mohon untuk dapat diproses lebih lanjut.

            Terima kasih atas perhatian dan kerjasamanya.

            Wassalamualaikum Wr. Wb.
            EOT;

        $waNumber = "6281217283960"; // Ganti dengan nomor WA CS kamu
        $waLink = "https://wa.me/{$waNumber}?text=" . urlencode($message);

        return response()->json([
            'success' => true,
            'whatsapp_url' => $waLink
        ]);
    }
}
