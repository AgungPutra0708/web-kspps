@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content" style="height: 100vh">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <form action="{{ route('petugas.store') }}" method="post">
                        @csrf
                        <div class="card shadow mb-4">
                            <!-- Card Header Anggota -->
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold" style="color: #08786B">Tambah Data Petugas</h6>
                            </div>
                            <!-- Card Body Anggota -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="petugas_number">Nomor Petugas*</label>
                                            <div class="d-flex align-items-center">
                                                <input type="text" class="form-control petugas_number"
                                                    name="petugas_number" id="petugas_number" placeholder="Nomor Petugas"
                                                    readonly style="flex: 1;">
                                                <button class="btn btn-primary ml-2" type="button" id="showSearchMember"
                                                    style="height: 100%;"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="petugas_name">Nama Petugas*</label>
                                            <input type="text" class="form-control petugas_name" name="petugas_name"
                                                id="petugas_name" placeholder="Nama Petugas">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="petugas_username">Username Petugas</label>
                                            <input type="text" class="form-control petugas_username"
                                                name="petugas_username" id="petugas_username"
                                                placeholder="Username Petugas">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="petugas_password">Password Petugas</label>
                                            <input type="password" class="form-control petugas_password"
                                                name="petugas_password" id="petugas_password"
                                                placeholder="Password Petugas">
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
        <div class="modal fade" id="petugasModal" tabindex="-1" role="dialog" aria-labelledby="petugasModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="petugasModalLabel">Edit Petugas</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editPetugasForm" method="POST" action="">
                            @csrf
                            @method('POST')

                            <!-- Fields for Petugas details -->
                            <div class="form-group">
                                <label for="select_petugas_name">Pilih Petugas*</label>
                                <select class="form-control select2 select_petugas_name" style="width: 100%;"
                                    name="select_petugas_name" id="select_petugas_name">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_petugas_name">Nama Petugas*</label>
                                <input type="text" class="form-control edit_petugas_name" name="edit_petugas_name"
                                    id="edit_petugas_name" placeholder="Nama Petugas">
                            </div>
                            <div class="form-group">
                                <label for="edit_petugas_username">Username Petugas</label>
                                <input type="text" class="form-control edit_petugas_username"
                                    name="edit_petugas_username" id="edit_petugas_username" placeholder="Username Petugas">
                            </div>
                            <div class="form-group">
                                <label for="edit_petugas_password">Password Petugas</label>
                                <input type="password" class="form-control edit_petugas_password"
                                    name="edit_petugas_password" id="edit_petugas_password"
                                    placeholder="Password Petugas">
                            </div>
                        </form>

                        <!-- Delete form for deleting the Petugas -->
                        <form id="deletePetugasForm" method="POST" action="">
                            @csrf
                            @method('POST') <!-- This sets the form method to DELETE -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="deletePetugasForm" class="btn btn-danger">Hapus</button>
                        <button type="submit" form="editPetugasForm" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    <script>
        $(document).ready(function() {
            $('#showSearchMember').on('click', function() {
                $('#select_petugas_name').val('').change();
                $('#edit_petugas_name').val('').change();
                $('#edit_petugas_username').val('').change();
                $('#edit_petugas_password').val('').change();
                $.ajax({
                    url: "{{ route('get_data_petugas') }}",
                    method: 'GET',
                    success: function(response) {
                        $('#select_petugas_name').empty().prepend('<option value=""></option>');

                        $.each(response.petugas_data, function(index, item) {
                            $('#select_petugas_name').append('<option value="' + item
                                .id +
                                '" data-nama_petugas="' + item.nama_petugas +
                                '" data-username="' + item.data_user_petugas
                                .username +
                                '">(' + item.no_petugas + ') ' + item.nama_petugas +
                                '</option>');
                        });

                        $('#select_petugas_name').prepend('<option value=""></option>');
                        $('#petugasModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("Terjadi kesalahan: " + error);
                    }
                });
            });
            $('#select_petugas_name').change(function(e) {
                var selectedOption = $(this).find('option:selected');
                var petugasId = selectedOption.val();
                var updateUrl = "{{ route('petugas.update', ':id') }}";
                var deleteUrl = "{{ route('petugas.destroy', ':id') }}";
                var nama_petugas = selectedOption.data('nama_petugas');
                var username = selectedOption.data('username');

                updateUrl = updateUrl.replace(':id', petugasId);
                deleteUrl = deleteUrl.replace(':id', petugasId);

                $('#edit_petugas_name').val(nama_petugas).change();
                $('#edit_petugas_username').val(username).change();

                $('#editPetugasForm').attr('action', updateUrl);
                $('#deletePetugasForm').attr('action', deleteUrl);
            });
            // Fungsi untuk mendapatkan nomor post terbaru dengan format
            function getNextPostPetugasNumber() {
                $.ajax({
                    url: "{{ route('get_number_petugas') }}",
                    method: 'GET',
                    success: function(response) {
                        $('#petugas_number').val(response).change();
                    }
                });
            }

            // Panggil fungsi saat halaman dimuat
            getNextPostPetugasNumber();

            // Bisa juga dipanggil setiap beberapa detik jika diperlukan (misalnya 10 detik)
            setInterval(getNextPostPetugasNumber, 10000); // Update setiap 10 detik
        });
    </script>
@endsection
