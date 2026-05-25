@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: #08786B">
                                Detail Transaksi Anggota
                            </h6>
                        </div>
                        <div class="card-body">
                            @if ($transaksi)
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label><strong>No. Anggota:</strong></label>
                                            <p>{{ $transaksi->anggota->no_anggota ?? '-' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label><strong>Nama Anggota:</strong></label>
                                            <p>{{ $transaksi->anggota->nama_anggota ?? '-' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label><strong>Jenis Transaksi:</strong></label>
                                            <p>
                                                @if ($transaksi->jenis_transaksi == 'simpanan')
                                                    <span class="badge badge-info">{{ $transaksi->jenis_transaksi }}</span>
                                                @elseif ($transaksi->jenis_transaksi == 'pinjaman')
                                                    <span class="badge badge-warning">{{ $transaksi->jenis_transaksi }}</span>
                                                @elseif ($transaksi->jenis_transaksi == 'transfer')
                                                    <span class="badge badge-success">{{ $transaksi->jenis_transaksi }}</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $transaksi->jenis_transaksi }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label><strong>Jumlah:</strong></label>
                                            <p>Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label><strong>Tanggal Transaksi:</strong></label>
                                            <p>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y H:i') }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label><strong>Keterangan:</strong></label>
                                            <p>{{ $transaksi->keterangan ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if ($transaksi->jenis_transaksi == 'simpanan')
                                    <hr>
                                    <h6 class="font-weight-bold">Detail Simpanan</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label><strong>Rekening Simpanan Asal:</strong></label>
                                                <p>{{ $transaksi->no_rekening_simpanan_asal ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label><strong>Rekening Simpanan Tujuan:</strong></label>
                                                <p>{{ $transaksi->no_rekening_simpanan_tujuan ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($transaksi->jenis_transaksi == 'pinjaman')
                                    <hr>
                                    <h6 class="font-weight-bold">Detail Pinjaman</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label><strong>No. Pinjaman Tujuan:</strong></label>
                                                <p>{{ $transaksi->no_pinjaman_tujuan ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-warning">
                                    Data transaksi tidak ditemukan
                                </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('transaksi_anggota') }}" class="btn btn-danger">
                                <i class="fas fa-times"></i> Kembali
                            </a>
                            <a href="{{ route('transaksi_anggota.print', ['ids' => $transaksi->id]) }}" class="btn btn-primary" target="_blank">
                                <i class="fas fa-print"></i> Print
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
