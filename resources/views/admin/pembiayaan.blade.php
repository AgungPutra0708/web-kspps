@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content" style="height: 100vh">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <form action="{{ route('pembiayaan.store') }}" method="post">
                        @csrf
                        <div class="card shadow mb-4">
                            <!-- Card Header Anggota -->
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold" style="color: #08786B">Pembiayaan</h6>
                            </div>
                            <!-- Card Body Anggota -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="loan_number">Nomor Pembiayaan*</label>
                                            <div class="d-flex align-items-center">
                                                <input type="text" class="form-control loan_number" name="loan_number"
                                                    id="loan_number" placeholder="Nomor Pembiayaan" style="flex: 1;">
                                                <button class="btn btn-primary ml-2" type="button" id="showSearchMember"
                                                    style="height: 100%;"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="loan_name">Nama Pembiayaan*</label>
                                            <input type="text" class="form-control loan_name" name="loan_name"
                                                id="loan_name" placeholder="Nama Pembiayaan">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="loan_desc">Keterangan</label>
                                            <textarea class="form-control loan_desc" name="loan_desc" id="loan_desc"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary float-right ml-1">Simpan</button>
                                {{-- <button type="button" class="btn btn-success float-right">Cetak</button> --}}
                                <a href="{{ route('dashboard') }}" class="btn btn-danger float-left">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="loanModal" tabindex="-1" role="dialog" aria-labelledby="loanModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loanModalLabel">Edit Pembiayaan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editLoanForm" method="POST" action="">
                            @csrf
                            @method('POST')

                            <!-- Fields for Loan details -->
                            <div class="form-group">
                                <label for="select_loan_name">Pilih Pembiayaan*</label>
                                <select class="form-control select2 select_loan_name" style="width: 100%;"
                                    name="select_loan_name" id="select_loan_name">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_loan_number">Kode Pembiayaan*</label>
                                <input type="text" class="form-control edit_loan_number" name="edit_loan_number"
                                    id="edit_loan_number" placeholder="Nomor Pembiayaan">
                            </div>
                            <div class="form-group">
                                <label for="edit_loan_name">Nama Pembiayaan*</label>
                                <input type="text" class="form-control edit_loan_name" name="edit_loan_name"
                                    id="edit_loan_name" placeholder="Nama Pembiayaan">
                            </div>
                            <div class="form-group">
                                <label for="edit_loan_desc">Keterangan</label>
                                <textarea class="form-control edit_loan_desc" name="edit_loan_desc" id="edit_loan_desc"></textarea>
                            </div>
                        </form>

                        <!-- Delete form for deleting the Loan -->
                        <form id="deleteLoanForm" method="POST" action="">
                            @csrf
                            @method('POST') <!-- This sets the form method to DELETE -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="deleteLoanForm" class="btn btn-danger">Hapus</button>
                        <button type="submit" form="editLoanForm" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    <script>
        $(document).ready(function() {
            $('#showSearchMember').on('click', function() {
                $('#select_loan_name').val('').change();
                $('#edit_loan_number').val('').change();
                $('#edit_loan_name').val('').change();
                $('#edit_loan_desc').val('').change();
                $.ajax({
                    url: "{{ route('get_loan_data') }}",
                    method: 'GET',
                    success: function(response) {
                        $('#select_loan_name').empty().prepend('<option value=""></option>');

                        $.each(response.loan_data, function(index, item) {
                            $('#select_loan_name').append('<option value="' + item
                                .id +
                                '" data-no_pembiayaan="' + item.no_pembiayaan +
                                '" data-nama_pembiayaan="' + item.nama_pembiayaan +
                                '" data-keterangan_pembiayaan="' + item
                                .keterangan_pembiayaan +
                                '">(' + item.no_pembiayaan + ') ' + item
                                .nama_pembiayaan +
                                '</option>');
                        });

                        $('#select_loan_name').prepend('<option value=""></option>');
                        $('#loanModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("Terjadi kesalahan: " + error);
                    }
                });
            });
            $('#select_loan_name').change(function(e) {
                var selectedOption = $(this).find('option:selected');
                var loanId = selectedOption.val();
                var updateUrl = "{{ route('pembiayaan.update', ':id') }}";
                var deleteUrl = "{{ route('pembiayaan.destroy', ':id') }}";
                var no_pembiayaan = selectedOption.data('no_pembiayaan');
                var nama_pembiayaan = selectedOption.data('nama_pembiayaan');
                var keterangan_pembiayaan = selectedOption.data('keterangan_pembiayaan');

                updateUrl = updateUrl.replace(':id', loanId);
                deleteUrl = deleteUrl.replace(':id', loanId);

                $('#edit_loan_number').val(no_pembiayaan).change();
                $('#edit_loan_name').val(nama_pembiayaan).change();
                $('#edit_loan_desc').val(keterangan_pembiayaan).change();

                $('#editLoanForm').attr('action', updateUrl);
                $('#deleteLoanForm').attr('action', deleteUrl);
            });
        });
    </script>
@endsection
