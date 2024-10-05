@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content" style="height: 100vh">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <form action="{{ route('simpanan.store') }}" method="post">
                        @csrf
                        <div class="card shadow mb-4">
                            <!-- Card Header Anggota -->
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold" style="color: #08786B">Simpanan</h6>
                            </div>
                            <!-- Card Body Anggota -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="saving_number">Kode Simpanan*</label>
                                            <div class="d-flex align-items-center">
                                                <input type="text" class="form-control saving_number"
                                                    name="saving_number" id="saving_number" placeholder="Nomor Simpanan"
                                                    style="flex: 1;">
                                                <button class="btn btn-primary ml-2" type="button" id="showSearchMember"
                                                    style="height: 100%;"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="saving_name">Nama Simpanan*</label>
                                            <input type="text" class="form-control saving_name" name="saving_name"
                                                id="saving_name" placeholder="Nama Simpanan">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="saving_desc">Keterangan</label>
                                            <textarea class="form-control saving_desc" name="saving_desc" id="saving_desc"></textarea>
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
        <!-- Modal -->
        <div class="modal fade" id="savingModal" tabindex="-1" role="dialog" aria-labelledby="savingModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="savingModalLabel">Edit Rembug/Area/Kumpulan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editSavingForm" method="POST" action="">
                            @csrf
                            @method('POST')

                            <!-- Fields for Saving details -->
                            <div class="form-group">
                                <label for="select_saving_name">Pilih Simpanan*</label>
                                <select class="form-control select2 select_saving_name" style="width: 100%;"
                                    name="select_saving_name" id="select_saving_name">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_saving_number">Kode Simpanan*</label>
                                <input type="text" class="form-control edit_saving_number" name="edit_saving_number"
                                    id="edit_saving_number" placeholder="Nomor Simpanan">
                            </div>
                            <div class="form-group">
                                <label for="edit_saving_name">Nama Simpanan*</label>
                                <input type="text" class="form-control edit_saving_name" name="edit_saving_name"
                                    id="edit_saving_name" placeholder="Nama Simpanan">
                            </div>
                            <div class="form-group">
                                <label for="edit_saving_desc">Keterangan</label>
                                <textarea class="form-control edit_saving_desc" name="edit_saving_desc" id="edit_saving_desc"></textarea>
                            </div>
                        </form>

                        <!-- Delete form for deleting the Saving -->
                        <form id="deleteSavingForm" method="POST" action="">
                            @csrf
                            @method('POST') <!-- This sets the form method to DELETE -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="deleteSavingForm" class="btn btn-danger">Hapus</button>
                        <button type="submit" form="editSavingForm" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    <script>
        $(document).ready(function() {
            $('#showSearchMember').on('click', function() {
                $('#select_saving_name').val('').change();
                $('#edit_saving_number').val('').change();
                $('#edit_saving_name').val('').change();
                $('#edit_saving_desc').val('').change();
                $.ajax({
                    url: "{{ route('get_saving_data') }}",
                    method: 'GET',
                    success: function(response) {
                        $('#select_saving_name').empty().prepend('<option value=""></option>');

                        $.each(response.saving_data, function(index, item) {
                            $('#select_saving_name').append('<option value="' + item
                                .id +
                                '" data-no_simpanan="' + item.no_simpanan +
                                '" data-nama_simpanan="' + item.nama_simpanan +
                                '" data-keterangan_simpanan="' + item
                                .keterangan_simpanan +
                                '">(' + item.no_simpanan + ') ' + item
                                .nama_simpanan +
                                '</option>');
                        });

                        $('#select_saving_name').prepend('<option value=""></option>');
                        $('#savingModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("Terjadi kesalahan: " + error);
                    }
                });
            });
            $('#select_saving_name').change(function(e) {
                var selectedOption = $(this).find('option:selected');
                var savingId = selectedOption.val();
                var updateUrl = "{{ route('simpanan.update', ':id') }}";
                var deleteUrl = "{{ route('simpanan.destroy', ':id') }}";
                var no_simpanan = selectedOption.data('no_simpanan');
                var nama_simpanan = selectedOption.data('nama_simpanan');
                var keterangan_simpanan = selectedOption.data('keterangan_simpanan');

                updateUrl = updateUrl.replace(':id', savingId);
                deleteUrl = deleteUrl.replace(':id', savingId);

                $('#edit_saving_number').val(no_simpanan).change();
                $('#edit_saving_name').val(nama_simpanan).change();
                $('#edit_saving_desc').val(keterangan_simpanan).change();

                $('#editSavingForm').attr('action', updateUrl);
                $('#deleteSavingForm').attr('action', deleteUrl);
            });
        });
    </script>
@endsection
