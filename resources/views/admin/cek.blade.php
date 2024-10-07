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
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2">Total</th>
                                                <th id="totalSaldoSimpanan">0</th>
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
                    <td>${saving.saldo_akhir}</td>
                </tr>`;
                            tbodySimpanan.append(row);
                            totalSaldoSimpanan += parseFloat(saving
                                .saldo_akhir); // Tambahkan ke total
                        });
                        // Update total di tfoot
                        $('#totalSaldoSimpanan').text(totalSaldoSimpanan.toFixed(2));

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
                    <td>${loan.besar_pinjaman}</td>
                    <td>${loan.besar_margin}</td>
                    <td>${loan.lama_pinjaman}</td>
                    <td>${loan.status_pinjaman == "done" ? "Lunas" : "Berjalan"}</td>
                </tr>`;
                            tbodyPinjaman.append(row);
                            totalPokokPinjaman += parseFloat(loan
                                .besar_pinjaman); // Tambahkan ke total pokok pinjaman
                            totalMarginPinjaman += parseFloat(loan
                                .besar_margin); // Tambahkan ke total margin pinjaman
                        });
                        // Update total di tfoot
                        $('#totalPokokPinjaman').text(totalPokokPinjaman.toFixed(2));
                        $('#totalMarginPinjaman').text(totalMarginPinjaman.toFixed(2));
                    },
                    error: function(xhr, status, error) {
                        console.error("Terjadi kesalahan: " + error);
                    }
                });
            }
        });
    </script>
@endsection
