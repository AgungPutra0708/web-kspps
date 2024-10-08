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
                                                        placeholder="Nominal Setoran" onchange="formatLocalString(this)">
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
                                {{-- <button type="button" class="btn btn-success float-right">Cetak</button> --}}
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
            let simpananArray = []; // Array to store savings data
            let firstMemberID = null; // Variable to store the first member ID input

            // Event handler for the "Tambah" button
            $('.btn-success').on('click', function() {
                // Get data from the form
                let memberID = $('#member_name').val();
                let memberName = $('#member_name').find('option:selected').data("nama_anggota");
                let savingID = $('#saving_product').val();
                let savingProductName = $('#saving_product').find('option:selected').data("nama_simpanan");
                let amountSaving = $('#amount_saving').val();
                let savingDesc = $('#saving_desc').val();
                let kondisiSetoran = $('#kondisi_setoran').val();

                // Validation
                if (!memberID || !savingID || !amountSaving) {
                    Swal.fire('Error!', 'Semua kolom harus diisi!', 'error');
                    return;
                }

                // Check if it's the first member input
                if (simpananArray.length === 0) {
                    firstMemberID = memberID;
                } else {
                    if (memberID !== firstMemberID) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Member ID berbeda! Hanya member yang sama yang bisa diinput.',
                        });
                        return;
                    }
                }

                // Create new savings object
                let simpanan = {
                    id_anggota: memberID,
                    nama_anggota: memberName,
                    id_simpanan: savingID,
                    produk_simpanan: savingProductName,
                    metode_transaksi: kondisiSetoran,
                    nominal_setoran: amountSaving,
                    keterangan: savingDesc
                };

                // Add the data to the array
                simpananArray.push(simpanan);

                // Update the table with the new data
                updateTable();
            });

            // Function to update the table with the savings data
            function updateTable() {
                let tableBody = $('#dataTable tbody');
                tableBody.empty(); // Clear the table before adding new data

                let totalSetoran = 0;

                // Loop through the array and add rows to the table
                $.each(simpananArray, function(index, simpanan) {
                    // Remove the formatting (dots and commas) for calculation
                    let cleanAmount = parseFloat(simpanan.nominal_setoran.replace(/\./g, '').replace(/,/g,
                        '.'));

                    if (simpanan.metode_transaksi == "+") {
                        totalSetoran += cleanAmount;
                    } else {
                        totalSetoran -= cleanAmount;
                    }

                    tableBody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${simpanan.nama_anggota}</td>
                    <td>${simpanan.produk_simpanan}</td>
                    <td>${simpanan.metode_transaksi}${formatRupiah(cleanAmount)}</td>
                    <td>${simpanan.keterangan}</td>
                    <td><button class="btn btn-danger btn-sm" onclick="removeRow(${index})">Hapus</button></td>
                </tr>
            `);
                });

                // Update total setoran in the table footer
                $('.amount_setoran').text(formatRupiah(totalSetoran));
            }

            // Function to remove a row from the table and array
            window.removeRow = function(index) {
                simpananArray.splice(index, 1); // Remove the element from the array
                updateTable(); // Update the table after deletion
            }

            // Submit form handler
            $('#simpananForm').on('submit', function() {
                $('#simpanan_array').val(JSON.stringify(simpananArray)); // Serialize array to JSON string
            });

            // Function to format a number as Rupiah (without "Rp" and using dots for thousands, commas for decimals)
            function formatRupiah(number) {
                return number.toLocaleString('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                }).replace(/,/g, ',').replace(/\./g, '.');
            }
        });

        // Function to format input as a localized string with dots for thousands and commas for decimals
        function formatLocalString(input) {
            let value = input.value.replace(/\./g, '').replace(/,/g, '.'); // Remove formatting for parsing
            let number = parseFloat(value);

            if (!isNaN(number)) {
                // Reapply formatting
                input.value = number.toLocaleString('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                }).replace(/,/g, ',').replace(/\./g, '.');
            } else {
                input.value = ''; // Clear if not a valid number
            }
        }
    </script>
@endsection
