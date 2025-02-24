@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content" style="height: 100vh">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <form action="{{ route('management_user.store') }}" method="post">
                        @csrf
                        <div class="card shadow mb-4">
                            <!-- Card Header Anggota -->
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold" style="color: #08786B">Manajemen User Anggota</h6>
                            </div>
                            <!-- Card Body Anggota -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="member_name">Nama Anggota*</label>
                                            <select class="form-control select2 member_name" style="width: 100%;"
                                                name="member_name" id="member_name">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="member_group">Rembug/Area/Kelompok</label>
                                            <input type="text" class="form-control member_group" name="member_group"
                                                id="member_group" placeholder="Rembug/Area/Kelompok" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="member_username">Username Anggota</label>
                                            <input type="text" class="form-control member_username"
                                                name="member_username" id="member_username" placeholder="Username Anggota">
                                            <input type="hidden" class="form-control id_user" name="id_user" id="id_user"
                                                placeholder="id_user">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="member_password">Password Anggota</label>
                                            <input type="password" class="form-control member_password"
                                                name="member_password" id="member_password" placeholder="Password Anggota">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary float-right ml-1">Simpan</button>
                                <a href="{{ route('management_user') }}" class="btn btn-danger float-left">Batal</a>
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
            function fetchMemberData(selectedId = null) {
                $.ajax({
                    url: "{{ route('get_member_data') }}",
                    method: 'GET',
                    success: function(response) {
                        $('#member_name').empty().prepend('<option value="">Pilih Anggota</option>');

                        $.each(response.anggota_data, function(index, item) {
                            $('#member_name').append('<option value="' + item.id + '" data-rembug="' + item.nama_rembug + '" data-username="' + item.username + '" data-id_user="' + item.id_user + '">(' + item.no_anggota + ') ' + item.nama_anggota + '</option>');
                        });

                        if (selectedId) {
                            $('#member_name').val(selectedId).change();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Terjadi kesalahan: " + error);
                    }
                });
            }

            $('#member_name').change(function() {
                var selectedOption = $(this).find(':selected');
                $('#member_group').val(selectedOption.data('rembug')).change();
                $('#member_username').val(selectedOption.data('username')).change();
                $('#id_user').val(selectedOption.data('id_user')).change();

                if (selectedOption.data('username')) {
                    $('#member_username').attr('readonly', true);
                } else {
                    $('#member_username').attr('readonly', false);
                }
            });

            // Jika dalam mode edit, isi data berdasarkan ID yang dipilih
            var editId = "{{ $data->id_anggota ?? '' }}";
            console.log(editId);
            
            if (editId != '') {
                fetchMemberData(editId);
            } else {
                fetchMemberData();
            }
        });
    </script>
@endsection
