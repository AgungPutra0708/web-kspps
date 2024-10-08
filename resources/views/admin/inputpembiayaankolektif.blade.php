@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content" style="height: 100vh">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <form action="{{ route('input_pembiayaan_kolektif.store') }}" method="post" id="pembiayaanKolektifForm">
                        @csrf
                        <div class="card shadow mb-4">
                            <!-- Card Header Anggota -->
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold" style="color: #08786B">Input Angsuran Anggota Kolektif</h6>
                            </div>
                            <!-- Card Body Anggota -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="loan_product">Pilih Produk Pembiayaan</label>
                                            <select class="form-control select2 loan_product" style="width: 100%;"
                                                name="loan_product" id="loan_product">
                                                <option></option>
                                                @foreach ($dataPembiayaan as $data)
                                                    <option value="{{ $data->id }}"
                                                        data-nama_pembiayaan="{{ $data->nama_pembiayaan }}">
                                                        ({{ $data->no_pembiayaan }})
                                                        {{ $data->nama_pembiayaan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="member_group">Pilih Rembug/Area/Kelompok*</label>
                                            <select class="form-control select2 member_group" style="width: 100%;"
                                                name="member_group" id="member_group">
                                                <option></option>
                                                @foreach ($dataKumpulan as $data)
                                                    <option value="{{ $data->id }}"
                                                        data-nama_rembug="{{ $data->nama_rembug }}">
                                                        ({{ $data->no_rembug }})
                                                        {{ $data->nama_rembug }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Anggota</th>
                                                    <th>Angsuran Pokok</th>
                                                    <th>Angsuran Margin</th>
                                                    <th>Sisa Angsuran</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="2">Total Setoran</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="pembiayaan_array" id="pembiayaan_array">
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary float-right ml-1">Simpan</button>
                                <a href="{{ route('dashboard') }}" class="btn btn-danger float-left">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    <script>
        $(document).ready(function() {
            let selectedLoanProduct = null;
            let selectedMemberGroup = null;
            let pembiayaanArray = []; // Array untuk menyimpan data pembiayaan per anggota
            let totalAngsurPinjaman = 0;
            let totalAngsurPinjamanSisa = 0;

            function checkSelections() {
                if (selectedLoanProduct && selectedMemberGroup) {
                    fetchLoanData(selectedLoanProduct, selectedMemberGroup);
                }
            }

            // Event listener untuk pilihan produk pembiayaan
            $('#loan_product').on('change', function() {
                selectedLoanProduct = $(this).val();
                checkSelections();
            });

            // Event listener untuk pilihan member group
            $('#member_group').on('change', function() {
                selectedMemberGroup = $(this).val();
                checkSelections();
            });

            // Fungsi untuk melakukan AJAX request
            function fetchLoanData(loanProduct, memberGroup) {
                $.ajax({
                    url: "{{ route('get_member_data_pembiayaan_kolektif') }}", // Sesuaikan route
                    method: 'GET',
                    data: {
                        id_pembiayaan: loanProduct,
                        id_rembug: memberGroup
                    },
                    success: function(response) {
                        let anggotaData = response.anggota_data;
                        let tbody = $('#dataTable tbody');
                        tbody.empty(); // Kosongkan tabel sebelum menambah data baru
                        pembiayaanArray = []; // Reset array pembiayaan
                        totalAngsurPinjaman = 0; // Reset total
                        totalAngsurMargin = 0; // Reset total
                        totalAngsurPinjamanSisa = 0; // Reset total

                        // Loop melalui data anggota dan tampilkan ke dalam tabel
                        anggotaData.forEach(function(anggota, index) {
                            let angsurPinjaman = parseFloat(anggota.angsur_pinjaman) || 0;
                            let angsurMargin = parseFloat(anggota.angsur_margin) || 0;
                            let angsurPinjamanSisa = parseFloat(anggota.sisa_besar_pinjaman) ||
                                0;

                            totalAngsurPinjaman += angsurPinjaman;
                            totalAngsurMargin += angsurMargin;
                            totalAngsurPinjamanSisa += angsurPinjamanSisa;

                            let row = `<tr>
                        <td>${index + 1}</td>
                        <td>${anggota.nama_anggota}</td>
                        <td><input type="number" name="nominal_setoran_pokok[]" class="form-control nominal-setoran-pokok" data-index="${index}" data-anggota_id="${anggota.id_anggota}" data-pembiayaan_id="${anggota.id_pembiayaan}" data-pinjaman_id="${anggota.id_pinjaman}" data-angsur_pinjaman="${angsurPinjaman}" data-sisa_pinjaman="${anggota.sisa_pinjaman}" value="${angsurPinjaman}"></td>
                        <td><input type="number" name="nominal_setoran_margin[]" class="form-control nominal-setoran-margin" data-index="${index}" data-anggota_id="${anggota.id_anggota}" data-pembiayaan_id="${anggota.id_pembiayaan}" data-pinjaman_id="${anggota.id_pinjaman}" data-angsur_pinjaman="${angsurPinjaman}" data-sisa_pinjaman="${anggota.sisa_pinjaman}" value="${angsurMargin}"></td>
                        <td>${angsurPinjamanSisa.toLocaleString()}</td>
                    </tr>`;

                            tbody.append(row);
                        });

                        // Display totals in the footer
                        $('tfoot th:eq(1)').text(totalAngsurPinjaman
                            .toLocaleString()); // Total Angsur Pinjaman
                        $('tfoot th:eq(2)').text(totalAngsurMargin
                            .toLocaleString()); // Total Angsur Margin
                        $('tfoot th:eq(3)').text(totalAngsurPinjamanSisa
                            .toLocaleString()); // Total Angsur Pinjaman * Sisa

                        // Total setoran di bagian footer
                        $('.nominal-setoran-pokok, .nominal-setoran-margin').on('input', function() {
                            updateTotalSetoran();
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Terjadi kesalahan: " + error);
                    }
                });
            }

            // Fungsi untuk menghitung total setoran
            function updateTotalSetoran() {
                let totalSetoranPokok = 0;
                let totalSetoranMargin = 0;
                $('.nominal-setoran-pokok').each(function() {
                    totalSetoranPokok += parseFloat($(this).val()) || 0;
                });
                $('.nominal-setoran-margin').each(function() {
                    totalSetoranMargin += parseFloat($(this).val()) || 0;
                });
                $('tfoot th:eq(1)').text(totalSetoranPokok.toLocaleString());
                $('tfoot th:eq(2)').text(totalSetoranMargin.toLocaleString());
            }

            // Saat form disubmit, kumpulkan data dari tabel dan masukkan ke dalam pembiayaanArray
            $('#pembiayaanKolektifForm').on('submit', function(event) {
                pembiayaanArray = []; // Reset array sebelum mengumpulkan data

                $('#dataTable tbody tr').each(function(index, row) {
                    let pinjamanId = $(row).find('.nominal-setoran-pokok').data('pinjaman_id');
                    let pembiayaanId = $(row).find('.nominal-setoran-pokok').data('pembiayaan_id');
                    let anggotaId = $(row).find('.nominal-setoran-pokok').data('anggota_id');
                    let angsurPinjaman = $(row).find('.nominal-setoran-pokok').data(
                        'angsur_pinjaman');
                    let sisaPinjaman = $(row).find('.nominal-setoran-pokok').data('sisa_pinjaman');
                    let nominalSetoranPokok = $(row).find('.nominal-setoran-pokok').val();
                    let nominalSetoranMargin = $(row).find('.nominal-setoran-margin').val();

                    // Tambahkan data ke pembiayaanArray
                    pembiayaanArray.push({
                        id_anggota: anggotaId,
                        id_pembiayaan: pembiayaanId,
                        id_pinjaman: pinjamanId,
                        angsur_pinjaman: nominalSetoranPokok,
                        angsur_margin: nominalSetoranMargin,
                        angsuran_ke: sisaPinjaman
                    });
                });

                // Serialize array menjadi JSON string dan masukkan ke input hidden
                $('#pembiayaan_array').val(JSON.stringify(pembiayaanArray));
            });
        });
    </script>
@endsection
