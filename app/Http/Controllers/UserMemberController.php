<?php

namespace App\Http\Controllers;

use App\Models\AnggotaModel;
use App\Models\User;
use App\Models\UserMemberModel;
use App\Services\QrCodeService;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserMemberController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = UserMemberModel::with('anggota')
                ->select([
                    'user_anggotas.id',
                    'anggotas.no_anggota',
                    'anggotas.nama_anggota',
                    'user_anggotas.username',
                    'user_anggotas.is_condition',
                ])
                ->join('anggotas', 'anggotas.id', '=', 'user_anggotas.id_user')
                ->where('user_anggotas.status', 'anggota')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->is_condition == 0
                        ? '<span class="badge badge-pill badge-primary w-100 p-2">Active</span>'
                        : '<span class="badge badge-pill badge-danger w-100 p-2">Not Active</span>';
                })
                ->addColumn('action', function ($row) {
                    $detailUrl = route('management_user.show', ['id' => $row->id]);
                    $deleteUrl = route('management_user.destroy', ['id' => $row->id]);
                    $qrUrl = route('admin.anggota.qr', ['id' => $row->id]);

                    return '
                        <a href="' . $detailUrl . '" class="btn btn-info btn-sm">Detail</a>
                        <button class="btn btn-success btn-sm btn-qr" data-url="' . $qrUrl . '">QR</button>
                        <button class="btn btn-danger btn-sm delete-user" data-url="' . $deleteUrl . '">Delete</button>
                        <button class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '">Ubah Status</button>
                    ';
                })
                ->rawColumns(['status', 'action']) // Tambahkan 'status' agar HTML badge ditampilkan
                ->make(true);
        }

        return view('admin.user');
    }

    public function getMemberData()
    {
        // Ambil data anggota beserta nama rembug menggunakan eager loading
        $anggotaData = AnggotaModel::with('rembug')->get();

        // Transform data untuk mengirimkan response JSON
        $encryptedData = $anggotaData->map(function ($item) {
            $iduser = "";
            $username = "";
            $userAnggotaData = UserMemberModel::where('id_user', $item->id)->where('status', 'anggota')->first();

            if ($userAnggotaData) {
                $iduser = $userAnggotaData->id;
                $username = $userAnggotaData->username;
            }

            return [
                'id' => $item->id,  // ID Anggota
                'no_anggota' => $item->no_anggota, // No Anggota
                'nama_anggota' => $item->nama_anggota, // Nama Anggota
                'nama_rembug' => $item->rembug->nama_rembug ?? null,
                'id_rembug' => $item->id_rembug,
                'phone_anggota' => $item->phone_anggota,
                'ktp_image' => asset('storage/' . $item->idcard_anggota), // URL gambar KTP
                'id_user' => $iduser,
                'username' => $username,
            ];
        });

        return response()->json([
            'anggota_data' => $encryptedData
        ]);
    }

    public function changeStatus(Request $request)
    {
        $user = UserMemberModel::findOrFail($request->id);

        $user->is_condition = $user->is_condition == 0 ? 1 : 0;

        $user->save();

        return response()->json(['message' => 'Status berhasil diperbarui!']);
    }

    public function changePassword(Request $request)
    {
        $user = UserMemberModel::findOrFail(Auth::id());

        $user->password = Hash::make($request->new_password);

        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diperbarui!');
    }

    public function create()
    {
        return view('admin.createuser');
    }

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'member_name' => 'required|exists:anggotas,id',
            'member_username' => 'required|unique:user_anggotas,username,' . $request->member_name . ',id_user',
            'member_password' => 'nullable|min:6', // Minimal 6 karakter
        ]);

        // Cek apakah user sudah ada dengan status anggota
        $existingUser = UserMemberModel::where('id_user', $request->member_name)
            ->where('status', 'anggota')
            ->first();

        if ($existingUser) {
            // Jika password diisi, lakukan update password
            if (!empty($request->member_password)) {
                $existingUser->update([
                    'password' => Hash::make($request->member_password)
                ]);
                return redirect()->back()->with('success', 'Password user anggota berhasil diperbarui!');
            }
            return redirect()->back()->with('error', 'User anggota sudah ada. Password tidak diperbarui karena kosong.');
        }

        // Simpan user baru
        UserMemberModel::create([
            'id_user' => $request->member_name,
            'status' => "anggota",
            'username' => $request->member_username,
            'password' => Hash::make($request->member_password),
            'is_condition' => 0,
        ]);

        return redirect()->route('management_user')->with('success', 'Data user anggota berhasil disimpan!');
    }

    public function show($id)
    {
        $data = UserMemberModel::with('anggota')
            ->select([
                'user_anggotas.id',
                'anggotas.id as id_anggota',
                'anggotas.no_anggota',
                'anggotas.nama_anggota',
                'user_anggotas.username',
                'user_anggotas.is_condition',
            ])
            ->join('anggotas', 'anggotas.id', '=', 'user_anggotas.id_user')
            ->where('user_anggotas.status', 'anggota')
            ->where('user_anggotas.id', $id)
            ->first();

        return view('admin.createuser', compact('data'));
    }

    public function destroy($id)
    {
        $user = UserMemberModel::findOrFail($id);

        $user->delete();

        return response()->json(['success' => 'User anggota berhasil dihapus!']);
    }

    public function generateQrCode($id, QrCodeService $qr)
    {
        // hanya anggota aktif
        $user = UserMemberModel::where('id', $id)
            ->where('status', 'anggota')
            ->where('is_condition', 0)
            ->firstOrFail();

        // encrypt ID (aman & tidak bisa ditebak)
        $token = urlencode(Crypt::encryptString($user->id));

        // URL API public
        $url = url('/kartu-anggota?token=' . $token);

        $svg = $qr->generate($url);

        return response($svg)
            ->header('Content-Type', 'image/svg+xml');
    }
}
