@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content" style="height: 100vh">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <form action="{{ route('input_angsuran.store') }}" method="post" id="angsuranForm">
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
                                                        {{ $data->nama_pembiayaan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="member_group">Tanggal Transaksi*</label>
                                            <input type="date" class="form-control" name="tanggal_transaksi"
                                                id="tanggal_transaksi">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="member_group">Pilih Rembug/Area/Kelompok*</label>
                                            <select class="form-control select2 member_group" style="width: 100%;"
                                                name="member_group" id="member_group">
                                                <option></option>
                                                @foreach ($dataKumpulan as $data)
                                                    <option value="{{ $data->id }}" data-nama_rembug="{{ $data->nama_rembug }}">
                                                        ({{ $data->no_rembug }})
                                                        {{ $data->nama_rembug }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="member_name">Pilih Anggota*</label>
                                            <select class="form-control select2 member_name" style="width: 100%;"
                                                name="member_name" id="member_name">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="member_group">Angsuran Pokok*</label>
                                            <input type="text" class="form-control" name="angsuran_pokok"
                                                id="angsuran_pokok" value="0" min="0">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="member_group">Angsuran Margin*</label>
                                            <input type="text" class="form-control" name="angsuran_margin"
                                                id="angsuran_margin" value="0" min="0">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="member_group">Sisa Angsuran*</label>
                                            <input type="text" class="form-control" name="sisa_angsuran" id="sisa_angsuran"
                                                value="0" min="0" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        $(document).ready(function () {
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
            $('#loan_product').on('change', function () {
                selectedLoanProduct = $(this).val();
                checkSelections();
            });

            // Event listener untuk pilihan member group
            $('#member_group').on('change', function () {
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
                    success: function (response) {
                        let anggotaData = response.anggota_data;

                        // =========================
                        // ISI DROPDOWN MEMBER
                        // =========================
                        let memberSelect = $('#member_name');
                        memberSelect.empty();
                        memberSelect.append('<option></option>');

                        console.log(anggotaData);


                        anggotaData.forEach(function (anggota) {
                            let angsurPinjaman = parseFloat(anggota.angsur_pinjaman) || 0;
                            let angsurMargin = parseFloat(anggota.angsur_margin) || 0;
                            let angsurPinjamanSisa = parseFloat(anggota.sisa_besar_pinjaman) || 0;

                            memberSelect.append(
                                `<option value="${anggota.id_anggota}" data-id_pinjaman="${anggota.id_pinjaman}" data-id_pembiayaan="${anggota.id_pembiayaan}" data-anggota_id="${anggota.id_anggota}" data-angsur_pinjaman="${anggota.angsur_pinjaman}" data-sisa_pinjaman="${anggota.sisa_pinjaman}" data-angsur_pinjaman="${angsurPinjaman}" data-angsur_margin="${angsurMargin}" data-sisa_besar_pinjaman="${angsurPinjamanSisa}" data-angsuran_ke="${anggota.sisa_pinjaman}">
                                        ${anggota.nama_anggota}
                                    </option>`
                            );
                        });

                        memberSelect.trigger('change.select2');
                    },
                    error: function (xhr, status, error) {
                        console.error("Terjadi kesalahan: " + error);
                    }
                });
            }

            $('#member_name').on('change', function () {
                let selectedOption = $(this).find('option:selected');
                let angsurPinjaman = selectedOption.data('angsur_pinjaman') || 0;
                let angsurMargin = selectedOption.data('angsur_margin') || 0;
                let sisaBesarPinjaman = selectedOption.data('sisa_besar_pinjaman') || 0;

                console.log(angsurPinjaman, angsurMargin, sisaBesarPinjaman);

                $('#angsuran_pokok').val(greatFormatRupiah(angsurPinjaman)).change();
                $('#angsuran_margin').val(greatFormatRupiah(angsurMargin)).change();
                $('#sisa_angsuran').val(greatFormatRupiah(sisaBesarPinjaman)).change();
            });

            $('#angsuran_pokok, #angsuran_margin').on('focus', function () {
                if ($(this).val() === '0') {
                    $(this).val('');
                }
            });

            $('#angsuran_pokok, #angsuran_margin').on('blur', function () {
                if ($(this).val() === '') {
                    $(this).val('0');
                }
            });

            $('#angsuran_pokok, #angsuran_margin').on('keyup', function () {
                let value = $(this).val();

                // kalau kosong jangan diformat
                if (value === '') return;

                $(this).val(greatFormatRupiah(value));
            });

            $('#angsuranForm').on('submit', function (event) {
                event.preventDefault();

                let selectedOption = $('#member_name').find('option:selected');

                if (!selectedOption.val()) {
                    alert('Silakan pilih anggota terlebih dahulu');
                    return;
                }

                let form = $(this);

                // Hapus input hidden lama kalau ada
                form.find('.dynamic-input').remove();

                // Append semua data yang dibutuhkan
                form.append(`<input type="hidden" name="id_anggota" value="${selectedOption.data('anggota_id')}" class="dynamic-input">`);
                form.append(`<input type="hidden" name="id_pembiayaan" value="${selectedOption.data('id_pembiayaan')}" class="dynamic-input">`);
                form.append(`<input type="hidden" name="id_pinjaman" value="${selectedOption.data('id_pinjaman')}" class="dynamic-input">`);
                form.append(`<input type="hidden" name="angsuran_ke" value="${selectedOption.data('angsuran_ke')}" class="dynamic-input">`);

                $('#angsuran_pokok').val(destroyFormatRupiah($('#angsuran_pokok').val()));
                $('#angsuran_margin').val(destroyFormatRupiah($('#angsuran_margin').val()));

                // Submit ulang
                form.off('submit').submit();
            });
        });
    </script>
@endsection