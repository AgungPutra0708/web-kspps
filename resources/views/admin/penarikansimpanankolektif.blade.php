@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content" style="height: 100vh">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <form action="{{ route('input_simpanan_kolektif.store') }}" method="post" id="simpananKolektifForm">
                        @csrf
                        <div class="card shadow mb-4">
                            <!-- Card Header Anggota -->
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold" style="color: #08786B">Input Penarikan Simpanan Anggota
                                    Kolektif
                                </h6>
                            </div>
                            <!-- Card Body Anggota -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="saving_product">Pilih Produk Simpanan</label>
                                            <select class="form-control select2 saving_product" style="width: 100%;"
                                                name="saving_product" id="saving_product">
                                                <option></option>
                                                @foreach ($dataSimpanan as $data)
                                                    <option value="{{ $data->id }}"
                                                        data-nama_simpanan="{{ $data->nama_simpanan }}">
                                                        ({{ $data->no_simpanan }})
                                                        {{ $data->nama_simpanan }}</option>
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
                                                    <th>Saldo Akhir</th>
                                                    <th>Nominal Penarikan</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">Total Setoran</th>
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
                            <input type="hidden" name="simpanan_array" id="simpanan_array">
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
            let selectedSavingProduct = null;
            let selectedMemberGroup = null;
            let simpananArray = []; // Array untuk menyimpan data simpanan per anggota

            function checkSelections() {
                if (selectedSavingProduct && selectedMemberGroup) {
                    fetchSavingData(selectedSavingProduct, selectedMemberGroup);
                }
            }

            // Event listener untuk pilihan produk simpanan
            $('#saving_product').on('change', function() {
                selectedSavingProduct = $(this).val();
                checkSelections();
            });

            // Event listener untuk pilihan member group
            $('#member_group').on('change', function() {
                selectedMemberGroup = $(this).val();
                checkSelections();
            });

            // Fungsi untuk melakukan AJAX request
            function fetchSavingData(savingProduct, memberGroup) {
                $.ajax({
                    url: "{{ route('get_member_data_simpanan_kolektif') }}", // Sesuaikan route
                    method: 'GET',
                    data: {
                        id_simpanan: savingProduct,
                        id_rembug: memberGroup
                    },
                    success: function(response) {
                        let anggotaData = response.anggota_data;
                        let tbody = $('#dataTable tbody');
                        tbody.empty(); // Kosongkan tabel sebelum menambah data baru
                        simpananArray = []; // Reset array simpanan

                        // Loop melalui data anggota dan tampilkan ke dalam tabel
                        anggotaData.forEach(function(anggota, index) {
                            let row = `<tr>
                        <td>${index + 1}</td>
                        <td>${anggota.nama_anggota}</td>
                        <td>${anggota.saldo_akhir}</td>
                        <td><input type="number" name="nominal_setoran[]" class="form-control nominal-setoran" data-index="${index}" data-anggota_id="${anggota.id_anggota}" data-simpanan_id="${anggota.id_simpanan}" data-saldo="${anggota.saldo_akhir}"></td>
                        <td><input type="text" name="keterangan[]" class="form-control keterangan" data-index="${index}" data-anggota_id="${anggota.id_anggota}" data-simpanan_id="${anggota.id_simpanan}"></td>
                    </tr>`;

                            tbody.append(row);
                        });

                        // Total setoran di bagian footer
                        $('.nominal-setoran').on('input', function() {
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
                let totalSetoran = 0;
                $('.nominal-setoran').each(function() {
                    totalSetoran += parseFloat($(this).val()) || 0;
                });
                $('tfoot th:eq(1)').text(totalSetoran.toLocaleString());
            }

            // Saat form disubmit, kumpulkan data dari tabel dan masukkan ke dalam simpananArray
            $('#simpananKolektifForm').on('submit', function(event) {
                simpananArray = []; // Reset array sebelum mengumpulkan data

                $('#dataTable tbody tr').each(function(index, row) {
                    let nominalSetoran = $(row).find('.nominal-setoran').val();
                    let keterangan = $(row).find('.keterangan').val();
                    let anggotaId = $(row).find('.nominal-setoran').data('anggota_id');
                    let simpananId = $(row).find('.nominal-setoran').data('simpanan_id');

                    // Tambahkan data ke simpananArray
                    simpananArray.push({
                        id_anggota: anggotaId,
                        id_simpanan: simpananId,
                        metode_transaksi: '-',
                        jumlah_setoran: nominalSetoran,
                        keterangan: keterangan
                    });
                });

                // Serialize array menjadi JSON string dan masukkan ke input hidden
                $('#simpanan_array').val(JSON.stringify(simpananArray));
            });
        });
    </script>
@endsection
