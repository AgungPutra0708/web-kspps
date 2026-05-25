@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <!-- Card Header transaksi simpanan -->
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: #08786B">Cek Simpanan & Pembiayaan Anggota</h6>
                        </div>
                        <!-- Card Body simpanan -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="member_name">Pilih Nama Anggota*</label>
                                        <select class="form-control select2 member_name" style="width: 100%;"
                                            name="member_name" id="member_name">
                                            <option></option>
                                            @foreach ($dataAnggota as $data)
                                                <option value="{{ Crypt::encrypt($data->id) }}"
                                                    data-nama_anggota="{{ $data->nama_anggota }}">
                                                    ({{ $data->no_anggota }})
                                                    {{ $data->nama_anggota }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <h5>Simpanan Anggota</h5>
                            <hr class="my-2">
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="simpananTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk Simpanan</th>
                                                <th>Saldo Simpanan</th>
                                                <th style="width: 150px"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2">Total</th>
                                                <th id="totalSaldoSimpanan">0</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <h5>Pembiayaan Anggota</h5>
                            <hr class="my-2">
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="pinjamanTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Rekening Pinjaman</th>
                                                <th>Pokok Pinjaman</th>
                                                <th>Margin Pinjaman</th>
                                                <th>Lama Pinjaman</th>
                                                <th>Status Pinjaman</th>
                                                <th style="width: 200px"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2">Total</th>
                                                <th id="totalPokokPinjaman">0</th>
                                                <th id="totalMarginPinjaman">0</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('dashboard') }}" class="btn btn-danger float-left">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
        <div class="modal fade" id="qrModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">QR Code Anggota</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-center">
                        <div id="qrContainer">
                            <div class="text-muted">Loading QR Code...</div>
                        </div>

                        <div class="mt-3 d-flex justify-content-center gap-2">
                            <button class="btn btn-success mr-2" id="btnDownloadQR">Download</button>
                            <button class="btn btn-secondary" id="btnPrintQR">Print</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- End of Main Content -->

    <script>
        $(document).ready(function() {
            let selectedMember = null;

            // Event listener untuk pilihan member group
            $('#member_name').on('change', function() {
                selectedMember = $(this).val();
                fetchData(selectedMember);
            });

            // Fungsi untuk melakukan AJAX request
            function fetchData(member) {
                $.ajax({
                    url: "{{ route('get_saving_loan_data') }}", // Sesuaikan route
                    method: 'GET',
                    data: {
                        id_anggota: member
                    },
                    success: function(response) {
                        let savingData = response.saving_data;
                        let loanData = response.loan_data;

                        // tabel simpanan
                        let tbodySimpanan = $('#simpananTable tbody');
                        tbodySimpanan.empty();
                        let totalSaldoSimpanan = 0; // Inisialisasi total simpanan
                        // Loop melalui data anggota dan tampilkan ke dalam tabel
                        savingData.forEach(function(saving, index) {
                            let row = `<tr>
                                            <td>${index + 1}</td>
                                            <td>${saving.nama_simpanan}</td>
                                            <td>${formatRupiah(parseFloat(saving.saldo_akhir))}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-info" onclick="window.location.href='{{ url('history/') }}/${saving.id_rekening_simpanan}'">
                                                    <i class="fas fa-history"></i>
                                                </button>
                                                <button class="btn btn-success btn-sm btn-qr" data-url="{{ url('/cek-simpanan/') }}/${saving.id_rekening_simpanan}/qrcode">
                                                    QR
                                                </button>
                                            </td>
                                        </tr>`;

                            tbodySimpanan.append(row);
                            totalSaldoSimpanan += parseFloat(saving
                                .saldo_akhir); // Tambahkan ke total
                        });
                        // Update total di tfoot
                        $('#totalSaldoSimpanan').text(formatRupiah(parseFloat(totalSaldoSimpanan)));

                        // tabel pinjaman
                        let tbodyPinjaman = $('#pinjamanTable tbody');
                        tbodyPinjaman.empty();
                        let totalPokokPinjaman = 0; // Inisialisasi total pokok pinjaman
                        let totalMarginPinjaman = 0; // Inisialisasi total margin pinjaman
                        // Loop melalui data anggota dan tampilkan ke dalam tabel
                        loanData.forEach(function(loan, index) {
                            let row = `<tr>
                                            <td>${index + 1}</td>
                                            <td>${loan.no_pinjaman}</td>
                                            <td>${formatRupiah(parseFloat(loan.besar_pinjaman))}</td>
                                            <td>${formatRupiah(parseFloat(loan.besar_margin))}</td>
                                            <td>${loan.lama_pinjaman}</td>
                                            <td>${loan.status_pinjaman == "done" ? "Lunas" : "Berjalan"}</td>
                                            <td class="text-center">
                                                <!-- Tombol Edit -->
                                                @if (Session::get('role_petugas') == 'ADMIN')
                                                    <a href="{{ route('pinjaman.edit', ':id') }}" class="btn btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                                <button type="button" class="btn btn-info" onclick="window.location.href='{{ url('loan-history/') }}/${loan.id_pinjaman}'">
                                                    <i class="fas fa-history"></i>
                                                </button>
                                                <button class="btn btn-success btn-sm btn-qr" data-url="{{ url('/cek-pembiayaan/') }}/${loan.id_pinjaman}/qrcode">
                                                    QR
                                                </button>
                                            </td>
                                        </tr>`;
                            row = row.replace(':id', `${loan.id_pinjaman}`);
                            tbodyPinjaman.append(row);
                            totalPokokPinjaman += parseFloat(loan
                                .besar_pinjaman); // Tambahkan ke total pokok pinjaman
                            totalMarginPinjaman += parseFloat(loan
                                .besar_margin); // Tambahkan ke total margin pinjaman
                        });
                        // Update total di tfoot
                        $('#totalPokokPinjaman').text(formatRupiah(parseFloat(totalPokokPinjaman)));
                        $('#totalMarginPinjaman').text(formatRupiah(parseFloat(totalMarginPinjaman)));
                    },
                    error: function(xhr, status, error) {
                        console.error("Terjadi kesalahan: " + error);
                    }
                });
            }

            // Function to format a number as Rupiah (without "Rp" and using dots for thousands, commas for decimals)
            function formatRupiah(number) {
                return number.toLocaleString('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                }).replace(/,/g, ',').replace(/\./g, '.');
            }

            $(document).on('click', '.btn-qr', function() {
                const url = $(this).data('url');

                $('#qrContainer').html('<div class="text-muted">Loading QR Code...</div>');
                $('#qrModal').modal('show');

                fetch(url)
                    .then(res => res.text())
                    .then(svg => {
                        $('#qrContainer').html(svg);
                    })
                    .catch(() => {
                        $('#qrContainer').html('<div class="text-danger">Gagal load QR Code</div>');
                    });
            });

            $('#btnDownloadQR').on('click', function() {
                const svg = document.querySelector('#qrContainer svg');
                if (!svg) return;

                const serializer = new XMLSerializer();
                const svgStr = serializer.serializeToString(svg);

                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                const img = new Image();
                const svgBlob = new Blob([svgStr], {
                    type: 'image/svg+xml;charset=utf-8'
                });
                const url = URL.createObjectURL(svgBlob);

                img.onload = function() {
                    canvas.width = img.width;
                    canvas.height = img.height;

                    // background putih (biar gak transparan)
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);

                    ctx.drawImage(img, 0, 0);
                    URL.revokeObjectURL(url);

                    const pngUrl = canvas.toDataURL('image/png');

                    const a = document.createElement('a');
                    a.href = pngUrl;
                    a.download = 'qr-anggota.png';
                    a.click();
                };

                img.src = url;
            });

            $('#btnPrintQR').on('click', function() {
                const svgHtml = $('#qrContainer').html();
                if (!svgHtml) return;

                const win = window.open('', '_blank');
                win.document.write(`
                    <html>
                    <head>
                        <title>Print QR Code</title>
                        <style>
                            body {
                                display:flex;
                                justify-content:center;
                                align-items:center;
                                height:100vh;
                            }
                            svg {
                                width:300px;
                                height:300px;
                            }
                        </style>
                    </head>
                    <body>
                        ${svgHtml}
                        <script>
                            window.onload = function () {
                                window.print();
                                window.close();
                            }
                        <\/script>
                    </body>
                    </html>
                `);
            });
        });
    </script>
@endsection
