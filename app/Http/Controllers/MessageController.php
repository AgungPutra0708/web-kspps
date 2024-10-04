<?php

namespace App\Http\Controllers;

use App\Models\AnggotaModel;
use App\Models\InformasiModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Melakukan join antara tabel informasis dan anggotas
            $data = InformasiModel::select(['informasis.id', 'anggotas.nama_anggota', 'informasis.judul', 'informasis.created_at'])
                ->join('anggotas', 'informasis.id_anggota', '=', 'anggotas.id') // Menambahkan join
                ->where('kondisi_informasi', 'pesan')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d/m/Y');
                })
                ->make(true);
        }
        return view('admin.pesan');
    }

    public function create()
    {
        $data = [
            'dataAnggota' => AnggotaModel::all(),
        ];
        return view('admin.createpesan', $data);
    }

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'judul_informasi' => 'required',
            'member_name' => 'required',
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
            'kondisi_informasi' => 'pesan',
            'id_anggota' => $request->member_name,
        ];

        // Menyimpan data ke tabel anggotas
        InformasiModel::create($data);

        // Redirect ke halaman anggota dengan pesan sukses
        return redirect()->route('pesan_anggota')->with('success', 'Data Pesan berhasil disimpan!');
    }
}
