<?php

namespace App\Http\Controllers;

use App\Models\PinjamanModel;
use App\Models\RekeningSimpananModel;
use App\Models\SimpananModel;
use App\Models\TransaksiAnggotaModel;
use App\Models\TransaksiPinjamanModel;
use App\Models\TransaksiSimpananModel;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class QrCodeController extends Controller
{
    public function index()
    { // Debug: Tampilkan data yang sudah difilter dan dihitung
        return view('anggota.qrcode');
    }

    public function getSimpananAnggota()
    {
        $id_anggota = Session()->get('id_user');

        // Ambil semua data simpanan
        $simpananData = SimpananModel::where('jenis_simpanan', 'bayar')->orWhere('jenis_simpanan', 'terima bayar')->get();

        // Filter simpanan dan hitung saldo akhir berdasarkan id_anggota
        $filteredDataSimpanan = $simpananData->filter(function ($item) use ($id_anggota) {
            $rekeningSimpanan = RekeningSimpananModel::where('id_anggota', $id_anggota)
                ->where('id_simpanan', $item->id)
                ->whereNotNull('no_rekening_simpanan')
                ->where('no_rekening_simpanan', '<>', '')
                ->first();

            if (!$rekeningSimpanan) {
                return false;
            }

            return TransaksiSimpananModel::where('id_rekening_simpanan', $rekeningSimpanan->id)->exists();
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
        return $filteredDataSimpanan;
    }

    public function getSimpananTerima()
    {
        $id_anggota = Session()->get('id_user');

        // Ambil semua data simpanan
        $simpananData = SimpananModel::where('jenis_simpanan', 'terima')->orWhere('jenis_simpanan', 'terima bayar')->get();

        // Filter simpanan dan hitung saldo akhir berdasarkan id_anggota
        $filteredDataSimpanan = $simpananData->filter(function ($item) use ($id_anggota) {
            return RekeningSimpananModel::where('id_anggota', $id_anggota)
                ->where('id_simpanan', $item->id)
                ->whereNotNull('no_rekening_simpanan')
                ->where('no_rekening_simpanan', '<>', '')
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
        return $filteredDataSimpanan;
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

    public function detail(Request $request)
    {
        $qrData = Crypt::decryptString($request->qr_data);

        $explode = explode('|', $qrData);

        $dataTransaksi = [
            'no_rekening' => $explode[1] ?? '000000000',
            'nama_penerima' => $explode[2] ?? 'Tidak diketahui',
            'total_angsur' => 0
        ];

        if($explode[0] === 'simpanan') {
            $dataTransaksi['jenis_transaksi'] = 'Simpanan';
        } elseif($explode[0] === 'pembiayaan') {
            $dataTransaksi['jenis_transaksi'] = 'Pembiayaan';
            $dataPembiayaan = PinjamanModel::where('no_pinjaman', $dataTransaksi['no_rekening'])->first();
            if($dataPembiayaan) {
                if ($dataPembiayaan->status_pinjaman == 'done') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Pinjaman Sudah Lunas'
                    ], 400);
                }
                $dataTransaksi['total_angsur'] = $dataPembiayaan->angsur_pinjaman + $dataPembiayaan->angsur_margin;
            }
        } else {     
            $dataTransaksi['jenis_transaksi'] = 'Tidak diketahui';
        }

        return view('anggota.qrcode-detail', compact('dataTransaksi'));
    }

    public function process(Request $request)
    {
        $no_rekening = $request->no_rekening;
        $jenis_transaksi = $request->jenis_transaksi;
        $nominal = $request->nominal;
        $rekening_asal = Crypt::decrypt($request->rekening_asal);
        $keterangan = $request->keterangan;
        
        if(!$no_rekening || !$jenis_transaksi || !$nominal || !$rekening_asal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data transaksi tidak lengkap'
            ], 400);
        }

        if($jenis_transaksi === 'Simpanan') {
            $rekeningSimpananAsal = RekeningSimpananModel::where('id', $rekening_asal)->first();
            $rekeningSimpananTujuan = RekeningSimpananModel::where('no_rekening_simpanan', $no_rekening)->first();

            $saldoAkhir = TransaksiSimpananModel::where('id_rekening_simpanan', $rekeningSimpananAsal->id)
                ->select(DB::raw('SUM(
                    CASE 
                        WHEN metode_transaksi = "+" 
                        THEN jumlah_setoran 
                        ELSE -jumlah_setoran 
                    END
                ) as saldo_akhir'))
                ->value('saldo_akhir') ?? 0;

            if ((float)$saldoAkhir < (float)$nominal) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Saldo simpanan tidak mencukupi'
                ], 400);
            }

            if($rekening_asal === $rekeningSimpananTujuan->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Rekening tujuan tidak boleh sama dengan rekening asal'
                ], 400);
            }

            $dataTransaksiSimpananAsal = [
                'id_rekening_simpanan' => $rekeningSimpananAsal->id,
                'id_simpanan' => $rekeningSimpananAsal->id_simpanan,
                'id_anggota' => $rekeningSimpananAsal->id_anggota,
                'metode_transaksi' => '-',
                'jumlah_setoran' => $nominal,
                'keterangan' => $keterangan ?? 'Transfer ke ' . $rekeningSimpananTujuan->no_rekening_simpanan,
            ];

            $dataTransaksiSimpananTujuan = [
                'id_rekening_simpanan' => $rekeningSimpananTujuan->id,
                'id_simpanan' => $rekeningSimpananTujuan->id_simpanan,
                'id_anggota' => $rekeningSimpananTujuan->id_anggota,
                'metode_transaksi' => '+',
                'jumlah_setoran' => $nominal,
                'keterangan' => $keterangan ?? 'Transfer dari ' . $rekeningSimpananAsal->no_rekening_simpanan,
            ];

            $dataTransaksiAnggota = [
                'id_anggota' => $rekeningSimpananAsal->id_anggota,
                'id_transaksi_simpanan_asal' => null, // Akan diisi setelah transaksi simpanan dibuat
                'id_rekening_simpanan_asal' => $rekeningSimpananAsal->id,
                'no_rekening_simpanan_asal' => $rekeningSimpananAsal->no_rekening_simpanan,
                'id_transaksi_simpanan_tujuan' => null, // Akan diisi setelah transaksi simpanan dibuat
                'id_rekening_simpanan_tujuan' => $rekeningSimpananTujuan->id,
                'no_rekening_simpanan_tujuan' => $rekeningSimpananTujuan->no_rekening_simpanan,
                'jumlah' => $nominal,
                'tanggal_transaksi' => now(),
                'jenis_transaksi' => 'transfer',
                'keterangan' => $keterangan ?? 'Transfer antar rekening simpanan',
            ];

            if(!$rekeningSimpananAsal || !$rekeningSimpananTujuan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Rekening simpanan tidak ditemukan'
                ], 404);
            }

            DB::beginTransaction();
            try {
                $transaksiSimpananAsal = TransaksiSimpananModel::create($dataTransaksiSimpananAsal);
                $transaksiSimpananTujuan = TransaksiSimpananModel::create($dataTransaksiSimpananTujuan);
                $dataTransaksiAnggota['id_transaksi_simpanan_asal'] = $transaksiSimpananAsal->id;
                $dataTransaksiAnggota['id_transaksi_simpanan_tujuan'] = $transaksiSimpananTujuan->id;
                TransaksiAnggotaModel::create($dataTransaksiAnggota);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaksi gagal: ' . $e->getMessage()
                ], 500);
            }
        }elseif($jenis_transaksi === 'Pembiayaan') {
            $rekeningSimpananAsal = RekeningSimpananModel::where('id', $rekening_asal)->first();
            $rekeningPinjaman = PinjamanModel::where('no_pinjaman', $no_rekening)->first();

            $saldoAkhir = TransaksiSimpananModel::where('id_rekening_simpanan', $rekeningSimpananAsal->id)
                ->select(DB::raw('SUM(
                    CASE 
                        WHEN metode_transaksi = "+" 
                        THEN jumlah_setoran 
                        ELSE -jumlah_setoran 
                    END
                ) as saldo_akhir'))
                ->value('saldo_akhir') ?? 0;

            if ((float)$saldoAkhir < (float)$nominal) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Saldo simpanan tidak mencukupi'
                ], 400);
            }

            if($rekeningPinjaman->status_pinjaman == 'done') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pinjaman Sudah Lunas'
                ], 404);
            }

            $dataTransaksiSimpananAsal = [
                'id_rekening_simpanan' => $rekeningSimpananAsal->id,
                'id_simpanan' => $rekeningSimpananAsal->id_simpanan,
                'id_anggota' => $rekeningSimpananAsal->id_anggota,
                'metode_transaksi' => '-',
                'jumlah_setoran' => $nominal,
                'keterangan' => $keterangan ?? 'Pembayaran pembiayaan untuk ' . $rekeningPinjaman->no_pinjaman,
            ];

            $dataTransaksiPinjaman = [
                'id_anggota' => $rekeningPinjaman->id_anggota,
                'id_pembiayaan' => $rekeningPinjaman->id_pembiayaan,
                'id_pinjaman' => $rekeningPinjaman->id,
                'angsur_pinjaman' => $rekeningPinjaman->angsur_pinjaman,
                'angsur_margin' => $rekeningPinjaman->angsur_margin,
                'angsuran_ke' => $rekeningPinjaman->angsuran_ke,
                'tanggal_transaksi' => now(),
                'keterangan' => $keterangan ?? 'Pembayaran pembiayaan dari ' . $rekeningSimpananAsal->no_rekening_simpanan,
            ];

            $dataTransaksiAnggota = [
                'id_anggota' => $rekeningSimpananAsal->id_anggota,
                'id_transaksi_simpanan_asal' => null, // Akan diisi setelah transaksi simpanan dibuat
                'id_rekening_simpanan_asal' => $rekeningSimpananAsal->id,
                'no_rekening_simpanan_asal' => $rekeningSimpananAsal->no_rekening_simpanan,
                'id_transaksi_simpanan_tujuan' => null, // Akan diisi setelah transaksi simpanan dibuat
                'id_rekening_simpanan_tujuan' => null,
                'no_rekening_simpanan_tujuan' => null,
                'id_transaksi_pinjaman' => null, // Akan diisi setelah transaksi simpanan dibuat
                'id_pinjaman_tujuan' => $rekeningPinjaman->id,
                'no_pinjaman_tujuan' => $rekeningPinjaman->no_pinjaman,
                'jumlah' => $nominal,
                'tanggal_transaksi' => now(),
                'jenis_transaksi' => 'pinjaman',
                'keterangan' => $keterangan ?? 'Pembayaran pembiayaan dari ' . $rekeningSimpananAsal->no_rekening_simpanan,
            ];

            if(!$rekeningSimpananAsal) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Rekening simpanan tidak ditemukan'
                ], 404);
            }

            if(!$rekeningPinjaman) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Rekening pinjaman tidak ditemukan'
                ], 404);
            }

            DB::beginTransaction();
            try {
                $transaksiSimpananAsal = TransaksiSimpananModel::create($dataTransaksiSimpananAsal);
                $transaksiPinjaman = TransaksiPinjamanModel::create($dataTransaksiPinjaman);
                $dataTransaksiAnggota['id_transaksi_simpanan_asal'] = $transaksiSimpananAsal->id;
                $dataTransaksiAnggota['id_transaksi_pinjaman'] = $transaksiPinjaman->id;
                TransaksiAnggotaModel::create($dataTransaksiAnggota);

                // Update the related PinjamanModel entry
                PinjamanModel::where('id', $rekeningPinjaman->id)
                    ->update([
                        'sisa_besar_pinjaman' => DB::raw('sisa_besar_pinjaman - ' . $rekeningPinjaman->angsur_pinjaman),
                        'sisa_besar_margin' => DB::raw('sisa_besar_margin - ' . $rekeningPinjaman->angsur_margin),
                        'sisa_pinjaman' => DB::raw('sisa_pinjaman - 1'),
                    ]);

                // Check if the sisa_pinjaman has reached 0
                $pinjaman = PinjamanModel::find($rekeningPinjaman->id);
                if ($pinjaman->sisa_pinjaman == 0) {
                    // Update the status_pinjaman to "done"
                    $pinjaman->status_pinjaman = 'done';
                    $pinjaman->save();
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaksi gagal: ' . $e->getMessage()
                ], 500);
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berhasil'
        ]);
    }
}