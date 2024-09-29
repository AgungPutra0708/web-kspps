@extends('layout.main')

@section('content')
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
                                        <input type="text" class="form-control member_username" name="member_username"
                                            id="member_username" placeholder="Username Anggota">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="member_password">Password Anggota</label>
                                        <input type="password" class="form-control member_password" name="member_password"
                                            id="member_password" placeholder="Password Anggota">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary float-right ml-1">Simpan</button>
                            <a href="{{ route('home') }}" class="btn btn-danger float-left">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    <script>
        $(document).ready(function() {
            function fetchMemberData() {
                $.ajax({
                    url: "{{ route('get_member_data') }}",
                    method: 'GET',
                    success: function(response) {

                        // Simpan pilihan yang sedang dipilih di select member_group
                        var selectedMember = $('#member_name').val();

                        // Kosongkan opsi member_group (tetapi tidak menghapus pilihan yang sedang dipilih)
                        $('#member_name').empty().prepend(
                            '<option value=""></option>');

                        // Tambahkan opsi baru berdasarkan data yang didapat dari response
                        $.each(response.anggota_data, function(index, item) {
                            $('#member_name').append('<option value="' + item.id +
                                '" data-rembug="' + item.nama_rembug + '">(' + item
                                .no_anggota + ') ' + item.nama_anggota + '</option>');
                        });

                        // Pilih kembali opsi yang sebelumnya dipilih, jika ada
                        if (selectedMember) {
                            $('#member_name').val(selectedMember).change();
                        } else {
                            // Jika tidak ada pilihan sebelumnya, tambahkan opsi kosong sebagai placeholder
                            $('#member_name').prepend(
                                '<option value=""></option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Terjadi kesalahan: " + error);
                    }
                });
            }

            $('#member_name').change(function(e) {
                var rembugName = $(this).find('option:selected').data("rembug");
                $('#member_group').val(rembugName).change();
            });

            // Panggil fungsi saat halaman dimuat
            fetchMemberData();

            // Update setiap 10 detik
            setInterval(fetchMemberData, 10000);
        });
    </script>
@endsection
