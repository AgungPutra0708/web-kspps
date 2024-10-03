<?php

namespace App\Http\Controllers;

use App\Models\InformasiModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InformationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = InformasiModel::select(['id', 'judul', 'created_at'])->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d/m/Y');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="#" class="edit btn btn-primary btn-sm">Edit</a>';
                    $btn .= ' <a href="#" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.informasi');
    }

    public function create()
    {
        return view('admin.createinformasi');
    }

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'judul_informasi' => 'required',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file foto KTP
        ]);

        // Menyimpan file foto KTP jika ada
        if ($request->hasFile('banner')) {
            // Simpan di folder 'private/banner' di dalam storage/app/private
            $memberCardPath = $request->file('banner')->store('private/banner');
        } else {
            $memberCardPath = null; // Jika tidak ada foto KTP
        }

        $data = [
            'judul' => $request->judul_informasi,
            'keterangan' => $request->keterangan_informasi,
            'banner' => $memberCardPath, // Simpan path file banner
        ];

        // Menyimpan data ke tabel anggotas
        InformasiModel::create($data);

        // Redirect ke halaman anggota dengan pesan sukses
        return redirect()->route('informasi_berita')->with('success', 'Data Informasi berhasil disimpan!');
    }
}
