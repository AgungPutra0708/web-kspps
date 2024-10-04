@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content" style="height: 100vh">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <form action="{{ route('input_simpanan.store') }}" method="post" id="simpananForm">
                        @csrf
                        <div class="card shadow mb-4">
                            <!-- Card Header Anggota -->
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold" style="color: #08786B">Input Simpanan Anggota</h6>
                            </div>
                            <!-- Card Body Anggota -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6">
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
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="amount_saving">Nominal Setoran</label>
                                            <div class="row">
                                                <div class="col-auto pr-0">
                                                    <select class="kondisi_setoran form-control" name="kondisi_setoran"
                                                        id="kondisi_setoran">
                                                        <option value="+">+</option>
                                                        <option value="-">-</option>
                                                    </select>
                                                </div>
                                                <div class="col pl-1">
                                                    <input type="text" class="form-control amount_saving"
                                                        name="amount_saving" id="amount_saving"
                                                        placeholder="Nominal Setoran">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="saving_desc">Keterangan</label>
                                            <textarea class="form-control saving_desc" name="saving_desc" id="saving_desc"></textarea>
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
                                                    <th>Produk Simpanan</th>
                                                    <th>Nominal Setoran</th>
                                                    <th>Keterangan</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">Total Setoran</th>
                                                    <th><span class="amount_setoran"></span></th>
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
                                <button type="submit" class="btn btn-primary float-right ml-1">Setor</button>
                                <button type="button" class="btn btn-success float-right">Cetak</button>
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
            let simpananArray = []; // Array untuk menyimpan data simpanan
            let firstMemberID = null; // Variabel untuk menyimpan member ID pertama yang diinput

            // Event handler untuk tombol "Tambah"
            $('.btn-success').on('click', function() {
                // Ambil data dari form
                let memberID = $('#member_name').val();
                let memberName = $('#member_name').find('option:selected').data("nama_anggota");
                let savingID = $('#saving_product').val();
                let savingProductName = $('#saving_product').find('option:selected').data("nama_simpanan");
                let amountSaving = $('#amount_saving').val();
                let savingDesc = $('#saving_desc').val();
                let kondisiSetoran = $('#kondisi_setoran').val();

                // Validasi input (opsional)
                if (!memberID || !savingID || !amountSaving) {
                    Swal.fire('Error!', 'Semua kolom harus diisi!', 'error');
                    return;
                }

                // Cek apakah ini data anggota pertama yang diinput
                if (simpananArray.length === 0) {
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

                // Buat objek simpanan baru
                let simpanan = {
                    id_anggota: memberID,
                    nama_anggota: memberName,
                    id_simpanan: savingID,
                    produk_simpanan: savingProductName,
                    metode_transaksi: kondisiSetoran,
                    nominal_setoran: amountSaving,
                    keterangan: savingDesc
                };

                // Masukkan data ke array
                simpananArray.push(simpanan);

                // Tambahkan baris ke tabel
                updateTable();
            });

            // Fungsi untuk memperbarui tabel
            function updateTable() {
                let tableBody = $('#dataTable tbody');
                tableBody.empty(); // Kosongkan isi tabel sebelum menambahkan data baru

                let totalSetoran = 0;

                // Loop melalui array dan tambahkan baris ke tabel
                $.each(simpananArray, function(index, simpanan) {

                    if (simpanan.metode_transaksi == "+") {
                        totalSetoran += parseFloat(simpanan.nominal_setoran.replace(/[^\d.-]/g, ''));
                    } else {
                        totalSetoran -= parseFloat(simpanan.nominal_setoran.replace(/[^\d.-]/g, ''));
                    }

                    tableBody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${simpanan.nama_anggota}</td>
                        <td>${simpanan.produk_simpanan}</td>
                        <td>${simpanan.metode_transaksi}${simpanan.nominal_setoran}</td>
                        <td>${simpanan.keterangan}</td>
                        <td><button class="btn btn-danger btn-sm" onclick="removeRow(${index})">Hapus</button></td>
                    </tr>
                `);
                });

                // Perbarui total setoran di footer tabel
                $('.amount_setoran').text(totalSetoran.toFixed(2));
            }

            // Fungsi untuk menghapus baris dari tabel dan array
            window.removeRow = function(index) {
                simpananArray.splice(index, 1); // Hapus elemen dari array
                updateTable(); // Perbarui tabel setelah penghapusan
            }

            // Saat form disubmit, simpan array ke dalam input hidden
            $('#simpananForm').on('submit', function() {
                // Serialize array menjadi JSON string dan masukkan ke input hidden
                $('#simpanan_array').val(JSON.stringify(simpananArray));
            });
        });
    </script>
@endsection
