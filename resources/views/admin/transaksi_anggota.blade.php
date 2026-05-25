@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 font-weight-bold" style="color: #08786B">
                                        <i class="fas fa-exchange-alt"></i> Transaksi Anggota
                                    </h6>
                                </div>
                                <div class="col text-right">
                                    <button class="btn btn-primary btn-sm" id="printBtn" title="Cetak">
                                        <i class="fas fa-print"></i> Cetak
                                    </button>
                                    <button class="btn btn-success btn-sm" id="exportBtn" title="Export Excel">
                                        <i class="fas fa-download"></i> Export Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- QR Scanner Section (Hidden by default) -->
                            <div id="qrScannerContainer" class="mb-3" style="display:none;">
                                <div class="alert alert-info">
                                    <p>Arahkan kamera ke QR Code anggota untuk scanning</p>
                                </div>
                                <input type="text" id="qrInput" class="form-control" placeholder="QR Code akan muncul di sini..." autofocus>
                                <button class="btn btn-secondary btn-sm mt-2" id="closeScannerBtn">
                                    <i class="fas fa-times"></i> Tutup Scanner
                                </button>
                            </div>

                            <!-- Filter Section -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Tanggal Mulai:</label>
                                    <input type="date" id="startDate" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3">
                                    <label>Tanggal Akhir:</label>
                                    <input type="date" id="endDate" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3">
                                    <label>Jenis Transaksi:</label>
                                    <select id="jenisTransaksi" class="form-control form-control-sm">
                                        <option value="">Semua</option>
                                        <option value="simpanan">Simpanan</option>
                                        <option value="pinjaman">Pinjaman</option>
                                        <option value="transfer">Transfer</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <button class="btn btn-primary btn-sm btn-block" id="filterBtn">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </div>
                            </div>

                            <!-- DataTable -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="transaksiAnggotaTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>No Anggota Asal</th>
                                                    <th>Nama Anggota Asal</th>
                                                    <th>No Rekening Asal</th>
                                                    <th>No Anggota Tujuan</th>
                                                    <th>Nama Anggota Tujuan</th>
                                                    <th>No Rekening Tujuan</th>
                                                    <th>Jenis Transaksi</th>
                                                    <th>Jumlah</th>
                                                    <th>Tanggal Transaksi</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('dashboard') }}" class="btn btn-danger">
                                <i class="fas fa-times"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Scanner Modal -->
    <div class="modal fade" id="qrScannerModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Scan QR Code Anggota</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="qr-scanner"></div>
                    <input type="hidden" id="scannedQrValue">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let transaksiTable;

        $(document).ready(function() {
            // Initialize DataTable
            transaksiTable = $('#transaksiAnggotaTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: {
                    url: "{{ route('transaksi_anggota.data') }}",
                    data: function (d) {
                        d.start_date = $('#startDate').val();
                        d.end_date = $('#endDate').val();
                        d.jenis_transaksi = $('#jenisTransaksi').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'no_anggota_asal', name: 'no_anggota_asal', orderable: false, searchable: true },
                    { data: 'nama_anggota_asal', name: 'nama_anggota_asal', orderable: false, searchable: true },
                    { data: 'no_rekening_asal', name: 'no_rekening_asal', orderable: false, searchable: true },
                    { data: 'no_anggota_tujuan', name: 'no_anggota_tujuan', orderable: false, searchable: true },
                    { data: 'nama_anggota_tujuan', name: 'nama_anggota_tujuan', orderable: false, searchable: true },
                    { data: 'no_rekening_tujuan', name: 'no_rekening_tujuan', orderable: false, searchable: true },
                    { data: 'jenis_transaksi_display', name: 'jenis_transaksi', orderable: false, searchable: false },
                    { data: 'jumlah', name: 'jumlah', className: 'text-right', orderable: false, searchable: false },
                    { data: 'tanggal_transaksi', name: 'tanggal_transaksi', orderable: false, searchable: false },
                    { data: 'keterangan', name: 'keterangan', orderable: false, searchable: true }
                ],
                order: [[9, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Indonesian.json'
                }
            });

            // Filter button
            $('#filterBtn').click(function() {
                transaksiTable.draw();
            });

            // Scan QR button
            $('#scanQrBtn').click(function() {
                $('#qrScannerContainer').toggle();
                $('#qrInput').focus();
            });

            // Close scanner button
            $('#closeScannerBtn').click(function() {
                $('#qrScannerContainer').hide();
            });

            // QR Input handler
            $('#qrInput').on('keypress', function(e) {
                if (e.which == 13) {
                    const qrCode = $(this).val();
                    scanQrCode(qrCode);
                    $(this).val('');
                }
            });

            // Export button
            $('#exportBtn').click(function() {
                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();
                const jenisTransaksi = $('#jenisTransaksi').val();

                let url = "{{ route('transaksi_anggota.export') }}?";
                if (startDate) url += "start_date=" + startDate + "&";
                if (endDate) url += "end_date=" + endDate + "&";
                if (jenisTransaksi) url += "jenis_transaksi=" + jenisTransaksi;

                window.location.href = url;
            });

            // Print button
            $('#printBtn').click(function() {
                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();
                const jenisTransaksi = $('#jenisTransaksi').val();

                let url = "{{ route('transaksi_anggota.print') }}?";
                if (startDate) url += "start_date=" + startDate + "&";
                if (endDate) url += "end_date=" + endDate + "&";
                if (jenisTransaksi) url += "jenis_transaksi=" + jenisTransaksi;

                window.open(url, '_blank');
            });

        });

        // Format currency
        function formatCurrency(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value);
        }
    </script>

    <style>
        #qrScannerContainer {
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            background-color: #f8f9fa;
        }

        .badge {
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
        }

        .table th {
            background-color: #08786B;
            color: white;
            font-weight: 600;
        }

        .btn-group {
            display: flex;
            gap: 5px;
        }
    </style>
@endsection
