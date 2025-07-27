<?php

namespace App\Http\Controllers;

use App\Models\ProfileKoperasiModel;
use App\Models\RekeningSimpananModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

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
        // $dataProfile = ProfileKoperasiModel::first();
        return view('anggota.tamwil', compact('dataRekeningSimpanan'));
    }

    public function sendToWhatsappSetoran(Request $request)
    {
        $noAnggota   = $request->nomorAnggotaSetoran;
        $namaLengkap = $request->namaLengkapSetoran;
        $noRekening  = $request->nomorRekeningSetoran;
        $jumlah      = number_format($request->jumlahSetoran, 0, ',', '.');
        $metode      = $request->setoranMelalui;

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
            - Bukti Transfer: {$shortUrl}

            Dengan ini menyampaikan bahwa telah melakukan SETORAN TABUNGAN.

            Mohon untuk dapat diproses lebih lanjut.

            Terima kasih atas perhatian dan kerjasamanya.

            Wassalamualaikum Wr. Wb.
            EOT;

        $waNumber = "6283872790091"; // Ganti dengan nomor WA CS kamu
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
            {$additionalInfo}

            Dengan ini menyampaikan bahwa telah melakukan PENARIKAN TABUNGAN.

            Mohon untuk dapat diproses lebih lanjut.

            Terima kasih atas perhatian dan kerjasamanya.

            Wassalamualaikum Wr. Wb.
            EOT;

        $waNumber = "6283872790091"; // Ganti dengan nomor WA CS kamu
        $waLink = "https://wa.me/{$waNumber}?text=" . urlencode($message);

        return response()->json([
            'success' => true,
            'whatsapp_url' => $waLink
        ]);
    }
}
