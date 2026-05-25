<?php

namespace App\Http\Controllers;

use App\Models\TransaksiAnggotaModel;
use App\Models\AnggotaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class TransaksiAnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.transaksi_anggota');
    }

    /**
     * Get data for DataTables
     */
    public function data(Request $request)
    {
        $query = TransaksiAnggotaModel::with([
            'anggota',
            'rekeningSimpananAsal.anggota',
            'rekeningSimpananTujuan.anggota',
            'pinjamanTujuan.anggota'
        ])
        ->select('transaksi_anggotas.*');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay();
            $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
        }

        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        // Search by member number or name
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->whereHas('anggota', function ($q) use ($search) {
                $q->where('no_anggota', 'like', "%{$search}%")
                  ->orWhere('nama_anggota', 'like', "%{$search}%");
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('tanggal_transaksi', function ($row) {
                return Carbon::parse($row->tanggal_transaksi)->format('d-m-Y H:i');
            })
            ->editColumn('jumlah', function ($row) {
                return number_format($row->jumlah, 0, ',', '.');
            })
            ->addColumn('no_anggota_asal', function ($row) {
                return $row->anggota->no_anggota ?? '-';
            })
            ->addColumn('nama_anggota_asal', function ($row) {
                return $row->anggota->nama_anggota ?? '-';
            })
            ->addColumn('no_rekening_asal', function ($row) {
                return $row->no_rekening_simpanan_asal ?? '-';
            })
            ->addColumn('no_anggota_tujuan', function ($row) {
                if ($row->rekeningSimpananTujuan && $row->rekeningSimpananTujuan->anggota) {
                    return $row->rekeningSimpananTujuan->anggota->no_anggota;
                }

                if ($row->pinjamanTujuan && $row->pinjamanTujuan->anggota) {
                    return $row->pinjamanTujuan->anggota->no_anggota;
                }

                return '-';
            })
            ->addColumn('nama_anggota_tujuan', function ($row) {
                if ($row->rekeningSimpananTujuan && $row->rekeningSimpananTujuan->anggota) {
                    return $row->rekeningSimpananTujuan->anggota->nama_anggota;
                }

                if ($row->pinjamanTujuan && $row->pinjamanTujuan->anggota) {
                    return $row->pinjamanTujuan->anggota->nama_anggota;
                }

                return '-';
            })
            ->addColumn('no_rekening_tujuan', function ($row) {
                if ($row->no_rekening_simpanan_tujuan) {
                    return $row->no_rekening_simpanan_tujuan;
                }

                if ($row->no_pinjaman_tujuan) {
                    return $row->no_pinjaman_tujuan;
                }

                return '-';
            })
            ->addColumn('jenis_transaksi_display', function ($row) {
                $badgeClass = match($row->jenis_transaksi) {
                    'simpanan' => 'badge-info',
                    'pinjaman' => 'badge-warning',
                    'transfer' => 'badge-success',
                    default => 'badge-secondary'
                };
                return '<span class="badge '.$badgeClass.'">'.$row->jenis_transaksi.'</span>';
            })
            ->rawColumns(['jenis_transaksi_display'])
            ->make(true);
    }

    /**
     * Show detail of a specific transaction
     */
    public function detail($id)
    {
        $id = Crypt::decrypt($id);
        $transaksi = TransaksiAnggotaModel::with(['anggota'])->findOrFail($id);

        return view('admin.transaksi_anggota_detail', compact('transaksi'));
    }

    /**
     * Export transactions to Excel
     */
    public function export(Request $request)
    {
        $query = TransaksiAnggotaModel::with([
            'anggota',
            'rekeningSimpananTujuan.anggota',
            'pinjamanTujuan.anggota'
        ]);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay();
            $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
        }

        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        $transaksis = $query->orderBy('tanggal_transaksi', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'No Anggota Asal');
        $sheet->setCellValue('C1', 'Nama Anggota Asal');
        $sheet->setCellValue('D1', 'No Rekening Asal');
        $sheet->setCellValue('E1', 'No Anggota Tujuan');
        $sheet->setCellValue('F1', 'Nama Anggota Tujuan');
        $sheet->setCellValue('G1', 'No Rekening Tujuan');
        $sheet->setCellValue('H1', 'Jenis Transaksi');
        $sheet->setCellValue('I1', 'Jumlah');
        $sheet->setCellValue('J1', 'Tanggal Transaksi');
        $sheet->setCellValue('K1', 'Keterangan');

        $rowNumber = 2;
        foreach ($transaksis as $index => $transaksi) {
            $noAnggotaTujuan = '-';
            $namaAnggotaTujuan = '-';
            $noRekeningTujuan = $transaksi->no_rekening_simpanan_tujuan ?: $transaksi->no_pinjaman_tujuan ?: '-';

            if ($transaksi->rekeningSimpananTujuan && $transaksi->rekeningSimpananTujuan->anggota) {
                $noAnggotaTujuan = $transaksi->rekeningSimpananTujuan->anggota->no_anggota;
                $namaAnggotaTujuan = $transaksi->rekeningSimpananTujuan->anggota->nama_anggota;
            } elseif ($transaksi->pinjamanTujuan && $transaksi->pinjamanTujuan->anggota) {
                $noAnggotaTujuan = $transaksi->pinjamanTujuan->anggota->no_anggota;
                $namaAnggotaTujuan = $transaksi->pinjamanTujuan->anggota->nama_anggota;
            }

            $sheet->setCellValue('A'.$rowNumber, $index + 1);
            $sheet->setCellValue('B'.$rowNumber, $transaksi->anggota->no_anggota ?? '-');
            $sheet->setCellValue('C'.$rowNumber, $transaksi->anggota->nama_anggota ?? '-');
            $sheet->setCellValue('D'.$rowNumber, $transaksi->no_rekening_simpanan_asal ?? '-');
            $sheet->setCellValue('E'.$rowNumber, $noAnggotaTujuan);
            $sheet->setCellValue('F'.$rowNumber, $namaAnggotaTujuan);
            $sheet->setCellValue('G'.$rowNumber, $noRekeningTujuan);
            $sheet->setCellValue('H'.$rowNumber, $transaksi->jenis_transaksi);
            $sheet->setCellValue('I'.$rowNumber, $transaksi->jumlah);
            $sheet->setCellValue('J'.$rowNumber, Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y H:i'));
            $sheet->setCellValue('K'.$rowNumber, $transaksi->keterangan ?? '-');
            $rowNumber++;
        }

        foreach (range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $filename = 'Transaksi_Anggota_' . date('d-m-Y_H-i-s') . '.xls';
        $writer = new Xls($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    /**
     * Print transactions
     */
    public function print(Request $request)
    {
        $query = TransaksiAnggotaModel::with([
            'anggota',
            'rekeningSimpananTujuan.anggota',
            'pinjamanTujuan.anggota'
        ]);

        if ($request->filled('ids')) {
            $ids = explode(',', $request->ids);
            $query->whereIn('id', $ids);
        } else {
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay();
                $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
            }

            if ($request->filled('jenis_transaksi')) {
                $query->where('jenis_transaksi', $request->jenis_transaksi);
            }
        }

        $transaksis = $query->orderBy('tanggal_transaksi', 'desc')->get();

        return view('admin.print_transaksi_anggota', compact('transaksis'));
    }

    /**
     * Delete a transaction (ADMIN only)
     */
    public function destroy($id)
    {
        if (Session::get('role_petugas') != 'ADMIN') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus transaksi');
        }

        $transaksi = TransaksiAnggotaModel::findOrFail($id);
        $transaksi->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus');
    }

    /**
     * Scan QR Code to search member transactions
     */
    public function scanQr(Request $request)
    {
        $request->validate([
            'qr_code' => 'required'
        ]);

        // Try to find member by QR code (could be no_anggota or encrypted ID)
        $anggota = AnggotaModel::where('no_anggota', $request->qr_code)->first();

        if (!$anggota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anggota tidak ditemukan'
            ], 404);
        }

        // Get transactions for this member
        $transaksis = TransaksiAnggotaModel::where('id_anggota', $anggota->id)
            ->orderBy('tanggal_transaksi', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'anggota' => $anggota,
                'transaksis' => $transaksis
            ]
        ]);
    }
}
