<?php

namespace App\Http\Controllers;

use App\Models\AnggotaModel;
use App\Models\RekeningSimpananModel;
use App\Models\RembugModel;
use App\Models\SimpananModel;
use App\Models\TransaksiSimpananModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InputSavingController extends Controller
{
    public function index()
    {
        $data = [
            'dataSimpanan' => SimpananModel::all(),
            'dataAnggota' => AnggotaModel::all(),
        ];
        return view('admin.inputsimpanan', $data);
    }

    public function store(Request $request)
    {
        // Decode JSON array from the hidden input
        $simpananArray = json_decode($request->simpanan_array, true);

        // Validate array if necessary
        if (empty($simpananArray)) {
            return redirect()->back()->withErrors(['message' => 'Data simpanan kosong']);
        }

        // Use database transactions to ensure atomicity
        DB::beginTransaction();

        try {
            // Loop through each item in the array and save to the database
            foreach ($simpananArray as $simpanan) {
                $rekeningSimpanan = RekeningSimpananModel::where('id_simpanan', $simpanan['id_simpanan'])
                    ->where('id_anggota', $simpanan['id_anggota'])
                    ->first();

                if (!$rekeningSimpanan) {
                    $kodeSimpanan = SimpananModel::find($simpanan['id_simpanan']);
                    $kodeAnggota = AnggotaModel::find($simpanan['id_anggota']);

                    if (!$kodeSimpanan || !$kodeAnggota) {
                        throw new \Exception('Data Simpanan atau Anggota tidak ditemukan.');
                    }

                    // Extract the member code part after '-'
                    $memberCodePart = substr($kodeAnggota->no_anggota, strpos($kodeAnggota->no_anggota, '-') + 1);

                    // Create the combined code: SP-00001
                    $noRekeningSimpanan = $kodeSimpanan->no_simpanan . '-' . $memberCodePart;

                    // Create Rekening Simpanan
                    $rekeningSimpanan = RekeningSimpananModel::create([
                        'no_rekening_simpanan' => $noRekeningSimpanan,
                        'id_anggota' => $simpanan['id_anggota'],
                        'id_simpanan' => $simpanan['id_simpanan'],
                    ]);
                }

                // Clean and format 'nominal_setoran' to ensure it's a valid decimal
                $cleanAmount = str_replace('.', '', $simpanan['nominal_setoran']); // Remove dots
                $cleanAmount = str_replace(',', '.', $cleanAmount); // Replace commas with dots

                // Create Transaksi Simpanan
                TransaksiSimpananModel::create([
                    'id_rekening_simpanan' => $rekeningSimpanan->id,
                    'id_simpanan' => $simpanan['id_simpanan'],
                    'id_anggota' => $simpanan['id_anggota'],
                    'metode_transaksi' => $simpanan['metode_transaksi'],
                    'jumlah_setoran' => number_format((float) $cleanAmount, 2, '.', ''), // Store as a decimal (15,2)
                    'keterangan' => $simpanan['keterangan'],
                    'tanggal_transaksi' => Carbon::now(),
                ]);
            }

            // Commit the transaction
            DB::commit();

            // Redirect back with success message
            return redirect()->back()->with('success', 'Data transaksi simpanan berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollback();

            // Return back with error message
            return redirect()->back()->with(['error' => 'Gagal menyimpan data transaksi simpanan: ' . $e->getMessage()]);
        }
    }

    public function indexKolektif()
    {
        $data = [
            'dataSimpanan' => SimpananModel::all(),
            'dataKumpulan' => RembugModel::all(),
        ];
        return view('admin.inputsimpanankolektif', $data);
    }

    public function getMemberDataSimpananKolektif(Request $request)
    {
        $idSimpanan = $request->input('id_simpanan');
        $idRembug = $request->input('id_rembug');

        // Ambil data anggota dengan filter id_rembug
        $anggotaData = AnggotaModel::where('id_rembug', $idRembug)
            ->with('rembug')
            ->get();

        // Transform data untuk mengirimkan response JSON
        $encryptedData = $anggotaData->map(function ($item) use ($idSimpanan) {
            // Hitung saldo_akhir berdasarkan summary dari transaksi_simpanans dengan id_anggota dan id_simpanan
            $saldoAkhir = $item->transaksiSimpanans($idSimpanan)
                ->select(DB::raw('SUM(CASE WHEN metode_transaksi = "+" THEN jumlah_setoran ELSE -jumlah_setoran END) as saldo_akhir'))
                ->value('saldo_akhir');

            return [
                'id_anggota' => $item->id,  // ID Anggota
                'id_simpanan' => $idSimpanan,  // ID Simpanan
                'no_anggota' => $item->no_anggota, // No Anggota
                'nama_anggota' => $item->nama_anggota, // Nama Anggota
                'nama_rembug' => $item->rembug->nama_rembug ?? null, // Nama Rembug dari tabel rembug
                'saldo_akhir' => $saldoAkhir ?? 0, // Saldo akhir dari tabel transaksi_simpanans
            ];
        });

        // Kembalikan data dalam bentuk JSON
        return response()->json([
            'anggota_data' => $encryptedData
        ]);
    }

    public function storeSimpananKolektif(Request $request)
    {
        // Ambil array simpanan dari input hidden
        $simpananArray = json_decode($request->input('simpanan_array'), true);

        foreach ($simpananArray as $simpanan) {
            $rekeningSimpanan = RekeningSimpananModel::where('id_simpanan', $simpanan['id_simpanan'])
                ->where('id_anggota', $simpanan['id_anggota'])
                ->first();

            if (!$rekeningSimpanan) {
                $kodeSimpanan = SimpananModel::find($simpanan['id_simpanan']);
                $kodeAnggota = AnggotaModel::find($simpanan['id_anggota']);

                // Extract the member code part after '-'
                $memberCodePart = substr($kodeAnggota->no_anggota, strpos($kodeAnggota->no_anggota, '-') + 1);

                // Create the combined code: SP-00001
                $noRekeningSimpanan = $kodeSimpanan->no_simpanan . '-' . $memberCodePart;

                // Create Rekening Simpanan
                $rekeningSimpanan = RekeningSimpananModel::create([
                    'no_rekening_simpanan' => $noRekeningSimpanan,
                    'id_anggota' => $simpanan['id_anggota'],
                    'id_simpanan' => $simpanan['id_simpanan'],
                ]);
            }

            // Create Transaksi Simpanan
            if (!empty($simpanan['jumlah_setoran']) && is_numeric($simpanan['jumlah_setoran'])) {
                TransaksiSimpananModel::create([
                    'id_rekening_simpanan' => $rekeningSimpanan->id,
                    'id_anggota' => $simpanan['id_anggota'],
                    'id_simpanan' => $simpanan['id_simpanan'],
                    'metode_transaksi' => $simpanan['metode_transaksi'],
                    'jumlah_setoran' => $simpanan['jumlah_setoran'],
                    'keterangan' => $simpanan['keterangan'],
                    'tanggal_transaksi' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Data simpanan kolektif berhasil disimpan.');
    }

    public function indexPenarikanKolektif()
    {
        $data = [
            'dataSimpanan' => SimpananModel::all(),
            'dataKumpulan' => RembugModel::all(),
        ];
        return view('admin.penarikansimpanankolektif', $data);
    }

    public function getLastTransactionSaving(Request $request)
    {
        if ($request->ajax()) {
            $data = TransaksiSimpananModel::select([
                'anggotas.nama_anggota',
                'simpanans.nama_simpanan as produk_simpanan',
                'transaksi_simpanans.metode_transaksi',
                'transaksi_simpanans.jumlah_setoran',
                'transaksi_simpanans.tanggal_transaksi'
            ])
                ->join('anggotas', 'anggotas.id', '=', 'transaksi_simpanans.id_anggota')
                ->join('simpanans', 'simpanans.id', '=', 'transaksi_simpanans.id_simpanan')
                ->latest('transaksi_simpanans.created_at')
                ->take(25)
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nominal_transaksi', function ($row) {
                    $nominal = $row->metode_transaksi === '+' ? $row->jumlah_setoran : -$row->jumlah_setoran;
                    return number_format($nominal, 2);
                })
                ->editColumn('tanggal_transaksi', function ($row) {
                    return Carbon::parse($row->tanggal_transaksi)->format('d/m/Y H:i:s');
                })
                ->make(true);
        }
    }
}
