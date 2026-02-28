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
use Illuminate\Support\Facades\Session;
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

    public function indexAo()
    {
        $data = [
            'dataSimpanan' => SimpananModel::all(),
            'dataAnggota' => AnggotaModel::all(),
        ];
        return view('admin.inputsimpananao', $data);
    }

    public function indexAoPenarikan()
    {
        $data = [
            'dataSimpanan' => SimpananModel::all(),
            'dataAnggota' => AnggotaModel::all(),
        ];
        return view('admin.inputpenarikanao', $data);
    }

    public function store(Request $request)
    {
        $simpananArray = json_decode($request->simpanan_array, true);

        if (empty($simpananArray)) {
            return redirect()->back()->withErrors(['message' => 'Data simpanan kosong']);
        }

        DB::beginTransaction();

        try {

            $transaksiIds = []; // ← tampung semua id transaksi

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

                    $memberCodePart = substr(
                        $kodeAnggota->no_anggota,
                        strpos($kodeAnggota->no_anggota, '-') + 1
                    );

                    $noRekeningSimpanan = $kodeSimpanan->no_simpanan . '-' . $memberCodePart;

                    $rekeningSimpanan = RekeningSimpananModel::create([
                        'no_rekening_simpanan' => $noRekeningSimpanan,
                        'id_anggota' => $simpanan['id_anggota'],
                        'id_simpanan' => $simpanan['id_simpanan'],
                    ]);
                }

                $cleanAmount = str_replace('.', '', $simpanan['nominal_setoran']);
                $cleanAmount = str_replace(',', '.', $cleanAmount);

                $transaksi = TransaksiSimpananModel::create([
                    'id_rekening_simpanan' => $rekeningSimpanan->id,
                    'id_simpanan' => $simpanan['id_simpanan'],
                    'id_anggota' => $simpanan['id_anggota'],
                    'metode_transaksi' => $simpanan['metode_transaksi'],
                    'jumlah_setoran' => number_format((float) $cleanAmount, 2, '.', ''),
                    'keterangan' => $simpanan['keterangan'],
                    'tanggal_transaksi' => Carbon::now(),
                    'id_petugas' => Session::get('id_user'), // Simpan ID petugas yang melakukan transaksi
                ]);

                $transaksiIds[] = $transaksi->id; // ← simpan id transaksi
            }

            DB::commit();

            return redirect()->back()->with([
                'success'   => 'Data transaksi simpanan berhasil ditambahkan.',
                'print_url' => route('simpanan.print', [
                    'ids' => implode(',', $transaksiIds)
                ])
            ]);

        } catch (\Exception $e) {

            DB::rollback();

            return redirect()->back()->with([
                'error' => 'Gagal menyimpan data transaksi simpanan: ' . $e->getMessage()
            ]);
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

                $tanggalTransaksi = $simpanan['tanggal_transaksi'] ?? Carbon::now()->format('Y-m-d');

                TransaksiSimpananModel::create([
                    'id_rekening_simpanan' => $rekeningSimpanan->id,
                    'id_anggota' => $simpanan['id_anggota'],
                    'id_simpanan' => $simpanan['id_simpanan'],
                    'metode_transaksi' => $simpanan['metode_transaksi'],
                    'jumlah_setoran' => $simpanan['jumlah_setoran'],
                    'keterangan' => $simpanan['keterangan'],
                    'tanggal_transaksi' => $tanggalTransaksi, // Gunakan tanggal transaksi dari input
                    'id_petugas' => Session::get('id_user'), // Simpan ID petugas yang melakukan transaksi
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
                ->take(10)
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

    public function print(Request $request)
    {
        $ids = explode(',', $request->ids);

        $transaksis = TransaksiSimpananModel::with([
            'rekeningSimpanan',
            'simpanan',
            'petugas'
        ])
        ->whereIn('id', $ids)
        ->get();

        return view('admin.printsimpanan', compact('transaksis'));
    }
}
