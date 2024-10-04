@extends('layout.main')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <form action="{{ route('input_pembiayaan.store') }}" method="post" id="pembiayaanForm">
                    @csrf
                    <div class="card shadow mb-4">
                        <!-- Card Header Anggota -->
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: #08786B">Input Pembiayaan Anggota</h6>
                        </div>
                        <!-- Card Body Anggota -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
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
                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="member_name">Pilih Nama Anggota*</label>
                                        <select class="form-control select2 member_name" style="width: 100%;"
                                            name="member_name" id="member_name">
                                            <option></option>
                                            @foreach ($dataAnggota as $data)
                                                <option value="{{ $data->id }}"
                                                    data-nama_anggota="{{ $data->nama_anggota }}">({{ $data->no_anggota }})
                                                    {{ $data->nama_anggota }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="nominal_pinjaman">Nominal Pinjaman</label>
                                        <input type="text" class="form-control nominal_pinjaman" name="nominal_pinjaman"
                                            id="nominal_pinjaman" placeholder="Nominal Pinjaman">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="nominal_margin">Nominal Margin</label>
                                        <input type="text" class="form-control nominal_margin" name="nominal_margin"
                                            id="nominal_margin" placeholder="Nominal Margin">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="lama_pinjaman">Lama Pinjaman</label>
                                        <div class="row">
                                            <div class="col-auto pr-0">
                                                <select class="kondisi_pinjaman form-control" name="kondisi_pinjaman"
                                                    id="kondisi_pinjaman">
                                                    <option value="hari">Hari</option>
                                                    <option value="pekan">Pekan</option>
                                                    <option value="bulan">Bulan</option>
                                                </select>
                                            </div>
                                            <div class="col pl-1">
                                                <input type="text" class="form-control lama_pinjaman"
                                                    name="lama_pinjaman" id="lama_pinjaman" placeholder="Lama Pinjaman">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="loan_desc">Keterangan</label>
                                        <textarea class="form-control loan_desc" name="loan_desc" id="loan_desc"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-end mb-2">
                                <button type="button" class="btn btn-success">Tambah</button>
                            </div>
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Anggota</th>
                                                <th>Produk Pembiayaan</th>
                                                <th>Nominal Pinjaman</th>
                                                <th>Nominal Margin</th>
                                                <th>Lama Pinjaman</th>
                                                <th>Angsuran Pinjaman</th>
                                                <th>Angsuran Margin</th>
                                                <th>Keterangan</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3">Total</th>
                                                <th><span class="amount_pinjaman"></span></th>
                                                <th><span class="amount_margin"></span></th>
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
    <!-- /.container-fluid -->
    <script>
        $(document).ready(function() {
            let pembiayaanArray = []; // Array untuk menyimpan data pembiayaan
            let firstMemberID = null; // Variabel untuk menyimpan member ID pertama yang diinput

            // Event handler untuk tombol "Tambah"
            $('.btn-success').on('click', function() {
                // Ambil data dari form
                let memberID = $('#member_name').val();
                let memberName = $('#member_name').find('option:selected').data("nama_anggota");
                let loanID = $('#loan_product').val();
                let loanProductName = $('#loan_product').find('option:selected').data(
                    "nama_pembiayaan");
                let nominalPinjaman = $('#nominal_pinjaman').val();
                let nominalMargin = $('#nominal_margin').val();
                let lamaPinjaman = $('#lama_pinjaman').val();
                let kondisiPinjaman = $('#kondisi_pinjaman').find('option:selected').val();
                let loanDesc = $('#loan_desc').val();

                // Validasi input (opsional)
                if (!memberID || !loanID || !nominalPinjaman || !nominalMargin || !lamaPinjaman || !
                    kondisiPinjaman) {
                    Swal.fire('Error!', 'Semua kolom harus diisi!', 'error');
                    return;
                }

                // Cek apakah ini data anggota pertama yang diinput
                if (pembiayaanArray.length === 0) {
                    firstMemberID = memberID; // Set member ID pertama
                } else {
                    // Jika member ID berbeda dengan yang pertama, tampilkan pesan error
                    if (memberID !== firstMemberID) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Member ID berbeda! Hanya member yang sama yang bisa diinput.',
                        });
                        return;
                    }
                }

                // Buat objek pembiayaan baru
                let pembiayaan = {
                    id_anggota: memberID,
                    id_pembiayaan: loanID,
                    nama_anggota: memberName,
                    produk_pembiayaan: loanProductName,
                    nominal_pinjaman: nominalPinjaman,
                    nominal_margin: nominalMargin,
                    angsur_pinjaman: (parseFloat(nominalPinjaman) / parseFloat(lamaPinjaman)).toFixed(
                        2),
                    angsur_margin: (parseFloat(nominalMargin) / parseFloat(lamaPinjaman)).toFixed(2),
                    lama_pinjaman: lamaPinjaman,
                    kondisi_pinjaman: kondisiPinjaman,
                    keterangan_pinjaman: loanDesc
                };

                // Masukkan data ke array
                pembiayaanArray.push(pembiayaan);

                // Tambahkan baris ke tabel
                updateTable();
            });

            // Fungsi untuk memperbarui tabel
            function updateTable() {
                let tableBody = $('#dataTable tbody');
                tableBody.empty(); // Kosongkan isi tabel sebelum menambahkan data baru

                let totalPinjaman = 0;
                let totalMargin = 0;

                // Loop melalui array dan tambahkan baris ke tabel
                $.each(pembiayaanArray, function(index, pembiayaan) {
                    totalPinjaman += parseFloat(pembiayaan.nominal_pinjaman.replace(/[^\d.-]/g, ''));
                    totalMargin += parseFloat(pembiayaan.nominal_margin.replace(/[^\d.-]/g, ''));

                    tableBody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${pembiayaan.nama_anggota}</td>
                        <td>${pembiayaan.produk_pembiayaan}</td>
                        <td>${parseFloat(pembiayaan.nominal_pinjaman.replace(/[^\d.-]/g, '')).toLocaleString()}</td>
                        <td>${parseFloat(pembiayaan.nominal_margin.replace(/[^\d.-]/g, '')).toLocaleString()}</td>
                        <td>${pembiayaan.lama_pinjaman} ${pembiayaan.kondisi_pinjaman.toUpperCase()}</td>
                        <td>${parseFloat(pembiayaan.angsur_pinjaman).toLocaleString()}</td>
                        <td>${parseFloat(pembiayaan.angsur_margin).toLocaleString()}</td>
                        <td>${pembiayaan.keterangan_pinjaman}</td>
                        <td><button class="btn btn-danger btn-sm" onclick="removeRow(${index})">Hapus</button></td>
                    </tr>
                `);
                });

                // Perbarui total setoran di footer tabel
                $('.amount_pinjaman').text(totalPinjaman.toLocaleString());
                $('.amount_margin').text(totalMargin.toLocaleString());
            }

            // Fungsi untuk menghapus baris dari tabel dan array
            window.removeRow = function(index) {
                pembiayaanArray.splice(index, 1); // Hapus elemen dari array
                updateTable(); // Perbarui tabel setelah penghapusan
            }

            // Saat form disubmit, simpan array ke dalam input hidden
            $('#pembiayaanForm').on('submit', function() {
                // Serialize array menjadi JSON string dan masukkan ke input hidden
                $('#pembiayaan_array').val(JSON.stringify(pembiayaanArray));
            });
        });
    </script>
@endsection
