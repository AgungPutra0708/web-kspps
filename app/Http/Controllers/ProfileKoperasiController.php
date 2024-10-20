<?php

namespace App\Http\Controllers;

use App\Models\ProfileKoperasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Pastikan ini ditambahkan

class ProfileKoperasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all profiles from the database
        $data = [
            'dataProfile' => ProfileKoperasiModel::first(), // Get the first profile record
        ];
        return view('admin.profilekoperasi', $data);
    }

    /**
     * Store a newly created resource in storage or update if it already exists.
     */
    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'short_koperasi_name' => 'required',
            'long_koperasi_name' => 'nullable',
            'phone_koperasi' => 'nullable|numeric',
            'address_koperasi' => 'nullable',
            'bannerKoperasi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1048',
            'bannerKoperasiIndonesia' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1048',
            'id_spreadsheet' => 'nullable',
            'id_api_spreadsheet' => 'nullable',
            'link_market' => 'nullable|url',
            'link_baitul_mal' => 'nullable|url',
        ]);

        // Mencari profil yang sudah ada
        $profile = ProfileKoperasiModel::first();

        // Menyimpan file Logo Koperasi jika ada
        if ($request->hasFile('bannerKoperasi')) {
            $bannerFile = $request->file('bannerKoperasi');
            $koperasiBannerPath = 'koperasi/' . Str::random(10) . '_' . $bannerFile->getClientOriginalName(); // Nama file yang disimpan dengan random string
            $bannerFile->move(public_path('storage/koperasi'), $koperasiBannerPath); // Memindahkan file ke public/storage/koperasi
        } else {
            $koperasiBannerPath = $profile ? $profile->logo_koperasi : null; // Ambil nilai saat ini jika tidak ada file baru
        }

        // Menyimpan file Logo Koperasi Indonesia jika ada
        if ($request->hasFile('bannerKoperasiIndonesia')) {
            $bannerIndonesiaFile = $request->file('bannerKoperasiIndonesia');
            $koperasiIndonesiaBannerPath = 'koperasi/' . Str::random(10) . '_' . $bannerIndonesiaFile->getClientOriginalName(); // Nama file yang disimpan dengan random string
            $bannerIndonesiaFile->move(public_path('storage/koperasi'), $koperasiIndonesiaBannerPath); // Memindahkan file ke public/storage/koperasi
        } else {
            $koperasiIndonesiaBannerPath = $profile ? $profile->logo_koperasi_indonesia : null; // Ambil nilai saat ini jika tidak ada file baru
        }

        // Data yang akan disimpan ke database
        $data = [
            'nama_koperasi' => $request->short_koperasi_name,
            'nama_koperasi_lengkap' => $request->long_koperasi_name,
            'phone_koperasi' => $request->phone_koperasi,
            'alamat_koperasi' => $request->address_koperasi,
            'logo_koperasi' => $koperasiBannerPath,
            'logo_koperasi_indonesia' => $koperasiIndonesiaBannerPath,
            'id_spreadsheet_shu' => $request->id_spreadsheet,
            'id_api_spreadsheet_shu' => $request->id_api_spreadsheet,
            'link_market' => $request->link_market,
            'link_baitul_mal' => $request->link_baitul_mal,
        ];

        try {
            if ($profile) {
                // Update profil yang ada
                $profile->update($data);
                $message = 'Profil Koperasi berhasil diperbarui!';
            } else {
                // Buat profil baru
                ProfileKoperasiModel::create($data);
                $message = 'Profil Koperasi berhasil disimpan!';
            }

            // Redirect ke halaman yang dituju dengan pesan sukses
            return redirect()->route('profile_koperasi')->with('success', $message);
        } catch (\Exception $e) {
            // Tangani error dan kembalikan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }
}
