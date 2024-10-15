<?php

namespace App\Http\Controllers;

use App\Models\InformasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class MessageAnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id_user = Session::get('id_user');

        $dataInformasi = InformasiModel::all()->filter(function ($informasi) use ($id_user) {
            if ($informasi->kondisi_informasi === 'pesan' && $informasi->id_anggota != $id_user) {
                return false;
            }
            return true;
        })->map(function ($informasi) use ($id_user) {

            if ($informasi->kondisi_informasi === 'info') {
                $informasi->class = 'bg-primary text-white';
            } elseif ($informasi->kondisi_informasi === 'pesan' && $informasi->id_anggota == $id_user) {
                $informasi->class = 'bg-light text-secondary';
            } else {
                $informasi->class = 'bg-light text-secondary';
            }
            $informasi->keterangan = Str::words($informasi->keterangan, 20, ' ...');
            return $informasi;
        });

        // Pass the processed data to the view
        return view('anggota.message', compact('dataInformasi'));
    }

    public function detail($id)
    {
        // Find the message by its ID
        $informasi = InformasiModel::findOrFail($id);

        // Pass the message data to the view
        return view('anggota.message-detail', compact('informasi'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

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
