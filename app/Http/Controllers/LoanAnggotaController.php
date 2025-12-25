<?php

namespace App\Http\Controllers;

use App\Models\PinjamanModel;
use App\Models\TransaksiPinjamanModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class LoanAnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id_anggota = Session::get('id_user');
        // Ambil semua data pinjaman
        $pinjamanData = PinjamanModel::with('pembiayaan')->where('id_anggota', $id_anggota)->get();
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
        return view('anggota.detail-loan', compact('dataPinjaman'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        $id = Crypt::decrypt($id);
        // Ambil semua data, tanpa paginate
        $transaksiPinjamanData = TransaksiPinjamanModel::where('id_pinjaman', $id)
            ->orderBy('angsuran_ke', 'asc')
            ->get();
        $totalAngsuran = $transaksiPinjamanData->count();
        // Mapping untuk view
        $transaksiPinjaman = $transaksiPinjamanData->map(function ($item, $key) use ($totalAngsuran) {
            $currentAngsuran = $totalAngsuran - $key;
            return [
                'id' => Crypt::encrypt($item->id),
                'keterangan' => Carbon::parse($item->tanggal_transaksi)->format('d/m/Y') . '<br>Angsuran ke - ' . $item->angsuran_ke,
                'nominal' => 'Rp ' . number_format($item->angsur_pinjaman, 0, ',', '.'),
            ];
        });
        return view('anggota.transaksi-loan', compact('transaksiPinjaman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
