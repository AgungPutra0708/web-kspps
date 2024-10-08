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
                                                <option value="{{ $data->id }}"
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
                                                <th style="width: 50px"></th>
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
                                                <th style="width: 125px"></th>
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
                                            <td class="text-center"><button type="button" class="btn btn-info" onclick="window.location.href='{{ url('history/') }}/${saving.id_simpanan}'"><i class="fas fa-history"></i></button></td>
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
                                                <a href="{{ route('pinjaman.edit', ':id') }}" class="btn btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-info" onclick="window.location.href='{{ url('loan-history/') }}/${loan.id_pinjaman}'">
                                                    <i class="fas fa-history"></i>
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
        });
    </script>
@endsection
