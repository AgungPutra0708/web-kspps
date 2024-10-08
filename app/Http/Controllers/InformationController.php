<?php

namespace App\Http\Controllers;

use App\Models\InformasiModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class InformationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = InformasiModel::select(['id', 'judul', 'keterangan', 'created_at'])->where('kondisi_informasi', 'info')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d/m/Y');
                })
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
            $memberCardPath = $request->file('banner')->store('banner', 'public');
        } else {
            $memberCardPath = null; // Jika tidak ada foto KTP
        }

        $data = [
            'judul' => $request->judul_informasi,
            'keterangan' => $request->keterangan_informasi,
            'banner' => $memberCardPath, // Simpan path file banner
            'kondisi_informasi' => 'info',
        ];

        // Menyimpan data ke tabel anggotas
        InformasiModel::create($data);

        // Redirect ke halaman anggota dengan pesan sukses
        return redirect()->route('informasi_berita')->with('success', 'Data Informasi berhasil disimpan!');
    }
    public function edit($id)
    {
        $informasi = InformasiModel::findOrFail($id);
        return response()->json($informasi);
    }

    public function update(Request $request, $id)
    {
        // Validasi data input
        $request->validate([
            'judul_informasi' => 'required',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $informasi = InformasiModel::findOrFail($id);

        // Update banner if a new one is uploaded
        if ($request->hasFile('banner')) {
            // Optional: delete old banner file if it exists
            if ($informasi->banner) {
                Storage::delete($informasi->banner);
            }
            $informasi->banner = $request->file('banner')->store('banner', 'public');
        }

        $informasi->judul = $request->judul_informasi;
        $informasi->keterangan = $request->keterangan_informasi;
        $informasi->save();

        return redirect()->back()->with(['success' => 'Data Informasi berhasil diperbarui!']);
    }

    public function destroy(Request $request)
    {
        $informasi = InformasiModel::findOrFail($request->delete_id);
        // Optional: delete banner file if it exists
        if ($informasi->banner) {
            Storage::disk('public')->delete($informasi->banner);
        }
        $informasi->delete();

        return redirect()->back()->with(['success' => 'Data Informasi berhasil dihapus!']);
    }
}
