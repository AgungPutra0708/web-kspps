<?php

namespace App\Http\Controllers;

use App\Models\AnggotaModel;
use App\Models\PembiayaanModel;
use App\Models\PinjamanModel;
use App\Models\RembugModel;
use App\Models\TransaksiPinjamanModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InputLoanController extends Controller
{
    public function index()
    {
        $data = [
            'dataPembiayaan' => PembiayaanModel::all(),
            'dataAnggota' => AnggotaModel::all(),
        ];
        return view('admin.inputpembiayaan', $data);
    }

    public function store(Request $request)
    {
        // Decode JSON array dari input hidden
        $pembiayaanArray = json_decode($request->pembiayaan_array, true);

        // Validasi array jika diperlukan
        if (empty($pembiayaanArray)) {
            return redirect()->back()->withErrors(['message' => 'Data pembiayaan kosong']);
        }

        // Loop melalui setiap item dalam array dan simpan ke database
        foreach ($pembiayaanArray as $pembiayaan) {
            $kodePembiayaan = PembiayaanModel::find($pembiayaan['id_pembiayaan']);
            $kodeAnggota = AnggotaModel::find($pembiayaan['id_anggota']);
            // Get the last inserted no_pinjaman for the current pembiayaan
            $lastPinjaman = PinjamanModel::where('id_pembiayaan', $pembiayaan['id_pembiayaan'])
                ->orderBy('no_pinjaman', 'desc')
                ->first();

            if ($lastPinjaman) {
                // Extract the numeric part after the hyphen and increment it
                $lastNumber = (int) substr($lastPinjaman->no_pinjaman, strrpos($lastPinjaman->no_pinjaman, '-') + 1);
                $nextNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            } else {
                // If no previous records, start from 00001
                $nextNumber = '00001';
            }

            // Create the combined code: MRB-00001
            $noPinjaman = $kodePembiayaan->no_pembiayaan . '-' . $nextNumber;
            PinjamanModel::create([
                'no_pinjaman' => $noPinjaman,
                'id_pembiayaan' => (int) $pembiayaan['id_pembiayaan'],
                'id_anggota' => (int) $pembiayaan['id_anggota'],
                'besar_pinjaman' => $pembiayaan['nominal_pinjaman'],
                'besar_margin' => $pembiayaan['nominal_margin'],
                'lama_pinjaman' => $pembiayaan['lama_pinjaman'],
                'sisa_besar_pinjaman' => $pembiayaan['nominal_pinjaman'],
                'sisa_besar_margin' => $pembiayaan['nominal_margin'],
                'sisa_pinjaman' => $pembiayaan['lama_pinjaman'],
                'angsur_pinjaman' => $pembiayaan['angsur_pinjaman'],
                'angsur_margin' => $pembiayaan['angsur_margin'],
                'kondisi_pinjaman' => $pembiayaan['kondisi_pinjaman'],
                'keterangan_pinjaman' => $pembiayaan['keterangan_pinjaman'],
                'status_pinjaman' => 'on_going'
            ]);
        }

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data transaksi pembiayaan berhasil ditambahkan.');
    }

    public function indexKolektif()
    {
        $data = [
            'dataPembiayaan' => PembiayaanModel::all(),
            'dataKumpulan' => RembugModel::all(),
        ];
        return view('admin.inputpembiayaankolektif', $data);
    }

    public function getMemberDataPembiayaanKolektif(Request $request)
    {
        $idPembiayaan = $request->input('id_pembiayaan');
        $idRembug = $request->input('id_rembug');

        // Ambil data anggota dengan filter id_rembug
        $anggotaData = AnggotaModel::where('id_rembug', $idRembug)
            ->with('rembug')
            ->get();

        // Transform data untuk mengirimkan response JSON
        $encryptedData = $anggotaData->map(function ($item) use ($idPembiayaan) {
            // Ambil data dari tabel pinjamans
            $pinjamanData = $item->pembiayaanAnggota($idPembiayaan)
                ->select('id AS id_pinjaman', 'besar_pinjaman', 'besar_margin', 'sisa_pinjaman', 'angsur_pinjaman', 'angsur_margin', 'sisa_besar_pinjaman', 'sisa_besar_margin')
                ->first();

            return [
                'id_anggota' => $item->id,  // ID Anggota
                'id_pembiayaan' => $idPembiayaan,  // ID Pembiayaan
                'id_pinjaman' => $pinjamanData->id_pinjaman ?? 0,  // ID Pinjaman
                'no_anggota' => $item->no_anggota, // No Anggota
                'nama_anggota' => $item->nama_anggota, // Nama Anggota
                'nama_rembug' => $item->rembug->nama_rembug ?? null, // Nama Rembug dari tabel rembug
                'besar_pinjaman' => $pinjamanData->besar_pinjaman ?? 0,
                'besar_margin' => $pinjamanData->besar_margin ?? 0,
                'sisa_pinjaman' => $pinjamanData->sisa_pinjaman ?? 0,
                'sisa_besar_pinjaman' => $pinjamanData->sisa_besar_pinjaman ?? 0,
                'sisa_besar_margin' => $pinjamanData->sisa_besar_margin ?? 0,
                'angsur_pinjaman' => $pinjamanData->angsur_pinjaman ?? 0,
                'angsur_margin' => $pinjamanData->angsur_margin ?? 0,
            ];
        });

        // Kembalikan data dalam bentuk JSON
        return response()->json([
            'anggota_data' => $encryptedData
        ]);
    }

    public function storePembiayaanKolektif(Request $request)
    {
        // Ambil array pembiayaan dari input hidden
        $pembiayaanArray = json_decode($request->input('pembiayaan_array'), true);

        // Loop melalui array pembiayaan dan simpan ke database
        foreach ($pembiayaanArray as $pembiayaan) {
            // Skip if id_pinjaman is null, 0, or invalid, or if angsur_pinjaman or angsur_margin is null, empty, or 0
            if (
                empty($pembiayaan['id_pinjaman']) || !is_numeric($pembiayaan['id_pinjaman']) ||
                empty($pembiayaan['angsur_pinjaman']) || !is_numeric($pembiayaan['angsur_pinjaman']) ||
                empty($pembiayaan['angsur_margin']) || !is_numeric($pembiayaan['angsur_margin'])
            ) {
                continue; // Skip this iteration if any of the conditions are met
            }

            // Create new TransaksiPinjamanModel entry
            TransaksiPinjamanModel::create([
                'id_anggota' => $pembiayaan['id_anggota'],
                'id_pembiayaan' => $pembiayaan['id_pembiayaan'],
                'id_pinjaman' => $pembiayaan['id_pinjaman'],
                'angsur_pinjaman' => $pembiayaan['angsur_pinjaman'],
                'angsur_margin' => $pembiayaan['angsur_margin'],
                'angsuran_ke' => $pembiayaan['angsuran_ke'],
                'tanggal_transaksi' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            // Update the related PinjamanModel entry
            PinjamanModel::where('id', $pembiayaan['id_pinjaman'])
                ->update([
                    'sisa_besar_pinjaman' => DB::raw('sisa_besar_pinjaman - ' . $pembiayaan['angsur_pinjaman']),
                    'sisa_besar_margin' => DB::raw('sisa_besar_margin - ' . $pembiayaan['angsur_margin']),
                    'sisa_pinjaman' => DB::raw('sisa_pinjaman - 1'),
                ]);

            // Check if the sisa_pinjaman has reached 0
            $pinjaman = PinjamanModel::find($pembiayaan['id_pinjaman']);
            if ($pinjaman->sisa_pinjaman == 0) {
                // Update the status_pinjaman to "done"
                $pinjaman->status_pinjaman = 'done';
                $pinjaman->save();
            }
        }

        return redirect()->back()->with('success', 'Data pembiayaan kolektif berhasil disimpan.');
    }

    public function getLastTransactionLoan(Request $request)
    {
        if ($request->ajax()) {
            $data = TransaksiPinjamanModel::select([
                'anggotas.nama_anggota',
                'pembiayaans.nama_pembiayaan as produk_pembiayaan',
                'transaksi_pinjamans.angsur_pinjaman',
                'transaksi_pinjamans.angsur_margin',
                'transaksi_pinjamans.tanggal_transaksi'
            ])
                ->join('anggotas', 'anggotas.id', '=', 'transaksi_pinjamans.id_anggota')  // Perbaiki kondisi ON
                ->join('pembiayaans', 'pembiayaans.id', '=', 'transaksi_pinjamans.id_pembiayaan')  // Perbaiki kondisi ON
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('tanggal_transaksi', function ($row) {
                    return Carbon::parse($row->tanggal_transaksi)->format('d/m/Y H:i:s');
                })
                ->make(true);
        }
    }
}
