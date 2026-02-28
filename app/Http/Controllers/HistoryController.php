<?php

namespace App\Http\Controllers;

use App\Models\TransaksiSimpananModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id_rekening_simpanan)
    {
        $id_rekening_simpanan = Crypt::decrypt($id_rekening_simpanan);

        return view('admin.historysimpanan', compact('id_rekening_simpanan'));
    }

    public function data(Request $request, $id_rekening_simpanan)
    {
        $id_rekening_simpanan = Crypt::decrypt($id_rekening_simpanan);

        $query = TransaksiSimpananModel::where('id_rekening_simpanan', $id_rekening_simpanan);

        return DataTables::of($query)

            ->addIndexColumn()

            ->editColumn('tanggal_transaksi', function ($row) {
                return \Carbon\Carbon::parse($row->tanggal_transaksi)->format('d-m-Y H:i');
            })

            ->editColumn('jumlah_setoran', function ($row) {
                return number_format($row->jumlah_setoran, 0, ',', '.');
            })

            ->addColumn('aksi', function ($row) {
                $editUrl = route('edit_transaction', Crypt::encrypt($row->id));
                $deleteUrl = route('delete_transaction', $row->id);
                $printUrl = route('simpanan.print', [
                    'ids' => implode(',', [$row->id]),
                ]);
                if (Session::get('role_petugas') == 'ADMIN') {
                    return '
                        <a href="'.$editUrl.'" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="'.$printUrl.'" class="btn btn-info btn-sm" target="_blank">
                            <i class="fas fa-print"></i>
                        </a>
                        <form action="'.$deleteUrl.'" method="POST" style="display:inline;">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm(\'Yakin hapus?\')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    ';
                } else {
                    return '
                        <a href="'.$printUrl.'" class="btn btn-info btn-sm" target="_blank">
                            <i class="fas fa-print"></i>
                        </a>
                    ';
                }
            })

            ->rawColumns(['aksi'])
            ->make(true);
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
