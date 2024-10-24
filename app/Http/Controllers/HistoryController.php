<?php

namespace App\Http\Controllers;

use App\Models\TransaksiSimpananModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id_rekening_simpanan)
    {
        // Dekripsi ID simpanan
        $id_rekening_simpanan = Crypt::decrypt($id_rekening_simpanan);
        // Ambil history berdasarkan id_simpanan
        $historyData = TransaksiSimpananModel::where('id_rekening_simpanan', $id_rekening_simpanan)->get();
        return view('admin.historysimpanan', compact('historyData', 'id_rekening_simpanan'));
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
