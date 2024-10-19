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

        // Retrieve all information records except the soft-deleted ones
        $dataInformasi = InformasiModel::whereNull('deleted_at')->get()->filter(function ($informasi) use ($id_user) {
            // Filter messages that are relevant to the current user
            if ($informasi->kondisi_informasi === 'pesan' && $informasi->id_anggota != $id_user) {
                return false;
            }
            return true;
        })->all(); // Get all filtered results

        // Separate the records based on kondisi_informasi and apply transformations
        $infoData = collect($dataInformasi)
            ->where('kondisi_informasi', 'info')
            ->sortByDesc('created_at') // Sort by created_at in descending order
            ->take(2) // Take the top 2 after sorting
            ->map(function ($informasi) {
                $informasi->class = 'card-primary-border-radius text-white'; // Set class for info
                $informasi->keterangan = Str::words($informasi->keterangan, 5, ' ...');
                return $informasi;
            });

        $pesanData = collect($dataInformasi)
            ->where('kondisi_informasi', 'pesan')
            ->sortByDesc('created_at') // Sort by created_at in descending order
            ->take(2) // Take the top 2 after sorting
            ->map(function ($informasi) use ($id_user) {
                $informasi->class = 'bg-light text-secondary'; // Set class for pesan
                $informasi->keterangan = Str::words($informasi->keterangan, 5, ' ...');
                return $informasi;
            });

        // Pass the processed data to the view
        return view('anggota.message', compact('infoData', 'pesanData'));
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
