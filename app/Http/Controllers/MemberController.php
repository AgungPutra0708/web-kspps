<?php

namespace App\Http\Controllers;

use App\Models\AnggotaModel;
use App\Models\RembugModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function index()
    {
        return view('admin.anggota');
    }

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'member_number' => 'required',
            'member_name' => 'required',
            'member_group' => 'required',
            'member_phone' => 'nullable|digits_between:10,13',
            'member_card' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file foto KTP
        ]);

        // Menyimpan file foto KTP jika ada
        if ($request->hasFile('member_card')) {
            // Simpan di folder 'public/ktp' di dalam storage/app/public
            $memberCardPath = $request->file('member_card')->store('ktp', 'public');
        } else {
            $memberCardPath = null; // Jika tidak ada foto KTP
        }

        $data = [
            'no_anggota' => $request->member_number,
            'nama_anggota' => $request->member_name,
            'id_rembug' => $request->member_group, // ID Rembug
            'phone_anggota' => $request->member_phone,
            'idcard_anggota' => $memberCardPath, // Simpan path file KTP
        ];

        // Menyimpan data ke tabel anggotas
        AnggotaModel::create($data);

        // Redirect ke halaman anggota dengan pesan sukses
        return redirect()->route('anggota')->with('success', 'Data anggota berhasil disimpan!');
    }

    public function updateMember(Request $request, $id)
    {
        $request->validate([
            'edit_member_name' => 'required',
            'edit_member_group' => 'required',
            'edit_member_phone' => 'nullable|numeric',
            'edit_member_card' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Find member by ID
        $member = AnggotaModel::findOrFail($id);

        // Update member data
        $member->nama_anggota = $request->input('edit_member_name');
        $member->id_rembug = $request->input('edit_member_group');
        $member->phone_anggota = $request->input('edit_member_phone');

        // Check if a new KTP image is uploaded
        if ($request->hasFile('edit_member_card')) {
            // Store in public directory
            $memberCardPath = $request->file('edit_member_card')->store('ktp', 'public');
            $member->idcard_anggota = $memberCardPath;
        }

        $member->save();

        return redirect()->back()->with('success', 'Data anggota berhasil diperbarui');
    }

    public function deleteMember($id)
    {
        $member = AnggotaModel::findOrFail($id);

        // Check if the member has an associated KTP image
        if ($member->ktp_image) {
            // Delete the image from the public storage
            Storage::disk('public')->delete($member->ktp_image);
        }

        // Delete the member from the database
        $member->delete();

        return redirect()->back()->with('success', 'Data anggota berhasil dihapus');
    }


    public function getMemberAndRembugData()
    {
        // Ambil nomor anggota terbesar dari tabel
        $latestPost = AnggotaModel::orderBy('no_anggota', 'desc')->lockForUpdate()->first();
        $nextNumber = $latestPost ? intval(substr($latestPost->no_anggota, 2)) + 1 : 1;
        $formattedNumber = '101-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        // Ambil data rembug
        $rembugData = RembugModel::all();

        // Mengenkripsi id rembug menggunakan Crypt::encrypt()
        $encryptedData = $rembugData->map(function ($item) {
            return [
                'id' => $item->id,  // Enkripsi id
                'no_rembug' => $item->no_rembug,
                'nama_rembug' => $item->nama_rembug,
                'alamat_rembug' => $item->alamat_rembug,
            ];
        });

        // Kembalikan data dalam bentuk JSON
        return response()->json([
            'member_number' => $formattedNumber,
            'rembug_data' => $encryptedData
        ]);
    }

    public function getKtpImage($filename)
    {
        // Define the path to the KTP image in the correct directory
        $path = storage_path('app/private/private/ktp/' . $filename);

        // Check if the file exists
        if (!file_exists($path)) {
            abort(404); // File not found
        }

        // Return the file as a response
        return response()->file($path);
    }
}
