@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content" style="height: 100vh">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <form action="{{ route('kumpulan.store') }}" method="POST">
                        <div class="card shadow mb-4">
                            @csrf
                            <!-- Card Header Anggota -->
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold" style="color: #08786B">Rembug/Area/Kumpulan</h6>
                            </div>
                            <!-- Card Body Anggota -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="group_number">Nomor Rembug/Area/Kumpulan*</label>
                                            <div class="d-flex align-items-center">
                                                <input type="text" class="form-control group_number" name="group_number"
                                                    id="group_number" placeholder="Nomor Rembug/Area/Kumpulan" readonly
                                                    style="flex: 1;">
                                                <button class="btn btn-primary ml-2" type="button" id="showSearchMember"
                                                    style="height: 100%;"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="group_name">Nama Rembug/Area/Kumpulan*</label>
                                            <input type="text" class="form-control group_name" name="group_name"
                                                id="group_name" placeholder="Nama Rembug/Area/Kumpulan">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="group_address">Alamat</label>
                                            <textarea class="form-control group_address" name="group_address" id="group_address"></textarea>
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
        <div class="modal fade" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="groupModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="groupModalLabel">Edit Rembug/Area/Kumpulan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editGroupForm" method="POST" action="">
                            @csrf
                            @method('POST')

                            <!-- Fields for Group details -->
                            <div class="form-group">
                                <label for="select_group_name">Pilih Rembug/Area/Kumpulan*</label>
                                <select class="form-control select2 select_group_name" style="width: 100%;"
                                    name="select_group_name" id="select_group_name">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_group_name">Nama Rembug/Area/Kumpulan*</label>
                                <input type="text" class="form-control edit_group_name" name="edit_group_name"
                                    id="edit_group_name" placeholder="Nama Rembug/Area/Kumpulan">
                            </div>
                            <div class="form-group">
                                <label for="edit_group_address">Alamat</label>
                                <textarea class="form-control edit_group_address" name="edit_group_address" id="edit_group_address"></textarea>
                            </div>
                        </form>

                        <!-- Delete form for deleting the Group -->
                        <form id="deleteGroupForm" method="POST" action="">
                            @csrf
                            @method('POST') <!-- This sets the form method to DELETE -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="deleteGroupForm" class="btn btn-danger">Hapus</button>
                        <button type="submit" form="editGroupForm" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    <script>
        $(document).ready(function() {
            $('#showSearchMember').on('click', function() {
                $('#select_group_name').val('').change();
                $('#edit_group_name').val('').change();
                $('#edit_group_address').val('').change();
                $.ajax({
                    url: "{{ route('get_member_and_rembug_data') }}",
                    method: 'GET',
                    success: function(response) {
                        $('#select_group_name').empty().prepend('<option value=""></option>');

                        $.each(response.rembug_data, function(index, item) {
                            $('#select_group_name').append('<option value="' + item.id +
                                '" data-nama_rembug="' + item.nama_rembug +
                                '" data-alamat_rembug="' + item.alamat_rembug +
                                '">(' + item.no_rembug + ') ' + item.nama_rembug +
                                '</option>');
                        });

                        $('#select_group_name').prepend('<option value=""></option>');
                        $('#groupModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("Terjadi kesalahan: " + error);
                    }
                });
            });
            $('#select_group_name').change(function(e) {
                var selectedOption = $(this).find('option:selected');
                var groupId = selectedOption.val();
                var updateUrl = "{{ route('kumpulan.update', ':id') }}";
                var deleteUrl = "{{ route('kumpulan.destroy', ':id') }}";
                var nama_rembug = selectedOption.data('nama_rembug');
                var alamat_rembug = selectedOption.data('alamat_rembug');

                updateUrl = updateUrl.replace(':id', groupId);
                deleteUrl = deleteUrl.replace(':id', groupId);

                $('#edit_group_name').val(nama_rembug).change();
                $('#edit_group_address').val(alamat_rembug).change();

                $('#editGroupForm').attr('action', updateUrl);
                $('#deleteGroupForm').attr('action', deleteUrl);
            });
            // Fungsi untuk mendapatkan nomor post terbaru dengan format
            function getNextPostRembugNumber() {
                $.ajax({
                    url: "{{ route('get_number_kumpulan') }}",
                    method: 'GET',
                    success: function(response) {
                        $('#group_number').val(response).change();
                    }
                });
            }

            // Panggil fungsi saat halaman dimuat
            getNextPostRembugNumber();

            // Bisa juga dipanggil setiap beberapa detik jika diperlukan (misalnya 10 detik)
            setInterval(getNextPostRembugNumber, 10000); // Update setiap 10 detik
        });
    </script>
@endsection
