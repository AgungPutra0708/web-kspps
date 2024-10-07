<?php

namespace App\Http\Controllers;

use App\Models\TransaksiSimpananModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $transaction = TransaksiSimpananModel::findOrFail($id);
        return view('admin.editsimpanan', compact('transaction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $transaction = TransaksiSimpananModel::findOrFail($id);
        $transaction->update($request->only('jumlah_setoran', 'metode_transaksi'));
        return redirect()->route('history', Crypt::encrypt($transaction->id_simpanan))->with('success', 'Transaksi simpanan berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        $transaction = TransaksiSimpananModel::findOrFail($id);
        $transaction->delete();
        return redirect()->back()->with('success', 'Transaction deleted successfully.');
    }
}
