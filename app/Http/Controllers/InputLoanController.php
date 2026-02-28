<?php

namespace App\Http\Controllers;

use App\Models\AnggotaModel;
use App\Models\PembiayaanModel;
use App\Models\PinjamanModel;
use App\Models\RembugModel;
use App\Models\TransaksiPinjamanModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
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
            PinjamanModel::create([
                'no_pinjaman' => $pembiayaan['no_pinjaman'],
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

    public function edit($encryptedId)
    {
        // Dekripsi ID transaksi pembiayaan
        $id_pinjaman = Crypt::decrypt($encryptedId);

        // Ambil data transaksi pembiayaan
        $pinjaman = PinjamanModel::findOrFail($id_pinjaman);

        // Data pembiayaan dan anggota
        $data = [
            'dataPembiayaan' => PembiayaanModel::all(),
            'dataAnggota' => AnggotaModel::all(),
            'pinjaman' => $pinjaman
        ];

        // Mengirimkan data ke view
        return view('admin.editpembiayaan', $data);
    }

    public function updatePembiayaan(Request $request, $id)
    {
        // Validasi data input
        $request->validate([
            'loan_product' => 'required',
            'member_name' => 'required',
            'nominal_pinjaman' => 'required',
            'nominal_margin' => 'required',
            'lama_pinjaman' => 'required',
            'kondisi_pinjaman' => 'required|string',
            'loan_desc' => 'nullable|string',
        ]);

        // Temukan data pinjaman berdasarkan ID yang dienkripsi
        $pinjaman = PinjamanModel::findOrFail(Crypt::decrypt($id));

        // Update data pinjaman berdasarkan input
        $pinjaman->update([
            'id_pembiayaan' => $request->loan_product,
            'id_anggota' => $request->member_name,
            'besar_pinjaman' => $request->nominal_pinjaman,
            'besar_margin' => $request->nominal_margin,
            'angsur_pinjaman' => $request->nominal_angsuran_pinjaman,
            'angsur_margin' => $request->nominal_angsuran_margin,
            'lama_pinjaman' => $request->lama_pinjaman,
            'kondisi_pinjaman' => $request->kondisi_pinjaman,
            'status_pinjaman' => $request->status_pinjaman,
            'keterangan_pinjaman' => $request->loan_desc,
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('cek_saldo')->with('success', 'Data pinjaman berhasil diupdate.');
    }

    public function destroyPinjaman($encryptedId)
    {
        // Dekripsi ID transaksi pembiayaan
        $id_pinjaman = Crypt::decrypt($encryptedId);

        // Ambil pinjaman terkait dan sesuaikan nilai sisa_besar_pinjaman dan sisa_besar_margin
        $pinjaman = PinjamanModel::find($id_pinjaman);
        // Hapus pinjaman
        $pinjaman->delete();

        return redirect()->route('cek_saldo')->with('success', 'Pinjaman berhasil dihapus.');
    }

    public function indexKolektif()
    {
        $data = [
            'dataPembiayaan' => PembiayaanModel::all(),
            'dataKumpulan' => RembugModel::all(),
        ];
        return view('admin.inputpembiayaankolektif', $data);
    }

    public function indexAngsuran()
    {
        $data = [
            'dataPembiayaan' => PembiayaanModel::all(),
            'dataKumpulan' => RembugModel::all(),
        ];
        return view('admin.inputangsuran', $data);
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
            // Skip if both angsur_pinjaman and angsur_margin are empty or invalid
            if (
                empty($pembiayaan['id_pinjaman']) || !is_numeric($pembiayaan['id_pinjaman']) ||
                (empty($pembiayaan['angsur_pinjaman']) && empty($pembiayaan['angsur_margin'])) ||
                (!is_numeric($pembiayaan['angsur_pinjaman']) && !empty($pembiayaan['angsur_pinjaman'])) ||
                (!is_numeric($pembiayaan['angsur_margin']) && !empty($pembiayaan['angsur_margin']))
            ) {
                continue; // Skip this iteration if both angsur_pinjaman and angsur_margin are empty
            }

            // Ambil tanggal transaksi dari pembiayaan, jika tidak ada default ke tanggal saat ini
            $tanggalTransaksi = $pembiayaan['tanggal_transaksi'] ?? Carbon::now()->format('Y-m-d');

            // Create new TransaksiPinjamanModel entry
            TransaksiPinjamanModel::create([
                'id_anggota' => $pembiayaan['id_anggota'],
                'id_pembiayaan' => $pembiayaan['id_pembiayaan'],
                'id_pinjaman' => $pembiayaan['id_pinjaman'],
                'angsur_pinjaman' => $pembiayaan['angsur_pinjaman'],
                'angsur_margin' => $pembiayaan['angsur_margin'],
                'angsuran_ke' => $pembiayaan['angsuran_ke'],
                'tanggal_transaksi' => $tanggalTransaksi, // Gunakan tanggal transaksi dari input
                'id_petugas' => Session::get('id_user'), // Simpan ID petugas yang melakukan transaksi
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

    public function storeAngsuran(Request $request)
    {
        if($request->input('angsuran_pokok') == 0 && $request->input('angsuran_margin') == 0) {
            return redirect()->back()->with(['error' => 'Angsuran pokok dan margin tidak boleh keduanya nol.']);
        }

        if($request->input('sisa_angsuran') <= 0) {
            return redirect()->back()->with(['error' => 'Anggota tidak memiliki sisa angsuran.']);
        }

        $transaksiIds = [];

        // Ambil tanggal transaksi dari pembiayaan, jika tidak ada default ke tanggal saat ini
        $tanggalTransaksi = $request->input('tanggal_transaksi') ?? Carbon::now()->format('Y-m-d');

        // Create new TransaksiPinjamanModel entry
        $transaksi = TransaksiPinjamanModel::create([
            'id_anggota' => $request->input('id_anggota'),
            'id_pembiayaan' => $request->input('id_pembiayaan'),
            'id_pinjaman' => $request->input('id_pinjaman'),
            'angsur_pinjaman' => $request->input('angsuran_pokok'),
            'angsur_margin' => $request->input('angsuran_margin'),
            'angsuran_ke' => $request->input('angsuran_ke'),
            'tanggal_transaksi' => $tanggalTransaksi, // Gunakan tanggal transaksi dari input
            'id_petugas' => Session::get('id_user'), // Simpan ID petugas yang melakukan transaksi
        ]);

        // Update the related PinjamanModel entry
        PinjamanModel::where('id', $request->input('id_pinjaman'))
            ->update([
                'sisa_besar_pinjaman' => DB::raw('sisa_besar_pinjaman - ' . $request->input('angsuran_pokok')),
                'sisa_besar_margin' => DB::raw('sisa_besar_margin - ' . $request->input('angsuran_margin')),
                'sisa_pinjaman' => DB::raw('sisa_pinjaman - 1'),
            ]);

        // Check if the sisa_pinjaman has reached 0
        $pinjaman = PinjamanModel::find($request->input('id_pinjaman'));
        if ($pinjaman->sisa_pinjaman == 0) {
            // Update the status_pinjaman to "done"
            $pinjaman->status_pinjaman = 'done';
            $pinjaman->save();
        }
        
        $transaksiIds[] = $transaksi->id;

        return redirect()->back()->with([
                'success'   => 'Data transaksi angsuran berhasil ditambahkan.',
                'print_url' => route('angsuran.print', [
                    'ids' => implode(',', $transaksiIds)
                ])
            ]);
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
                ->take(10)
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('tanggal_transaksi', function ($row) {
                    return Carbon::parse($row->tanggal_transaksi)->format('d/m/Y H:i:s');
                })
                ->make(true);
        }
    }

    public function checkNoPinjaman(Request $request)
    {
        // Validasi request
        $request->validate([
            'no_pinjaman' => 'required|string',
        ]);

        // Mencari no_pinjaman di dalam database
        $existingLoan = PinjamanModel::where('no_pinjaman', $request->no_pinjaman)->first();

        // Jika ditemukan, maka no_pinjaman sudah ada
        if ($existingLoan) {
            return response()->json(['exists' => true]);
        } else {
            return response()->json(['exists' => false]);
        }
    }

    public function printAngsuran(Request $request)
    {
        $ids = explode(',', $request->ids);

        $transaksis = TransaksiPinjamanModel::with([
            'pinjaman',
            'pembiayaan',
            'anggota',
            'petugas'
        ])
        ->whereIn('id', $ids)
        ->get();

        return view('admin.printpinjaman', compact('transaksis'));
    }
}
