<?php

namespace App\Http\Controllers;

use App\Models\PinjamanModel;
use App\Models\RekeningSimpananModel;
use App\Models\SimpananModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
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
                'besar_pinjaman' => $item->besar_pinjaman,
                'besar_margin' => $item->besar_margin,
                'lama_pinjaman' => $item->lama_pinjaman,
                'status_pinjaman' => $item->status_pinjaman,
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
    public function edit(string $id)
    {
        //
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
