@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content" style="height: 100vh">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <form action="{{ route('anggota.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card shadow mb-4">
                            <!-- Card Header Anggota -->
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold" style="color: #08786B">Anggota</h6>
                            </div>
                            <!-- Card Body Anggota -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="member_number">Nomor Anggota*</label>
                                            <input type="text" class="form-control member_number" name="member_number"
                                                id="member_number" placeholder="Nomor Anggota" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="member_name">Nama Anggota*</label>
                                            <input type="text" class="form-control member_name" name="member_name"
                                                id="member_name" placeholder="Nama Anggota">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="member_group">Rembug/Area/Kelompok*</label>
                                            <select class="form-control select2 member_group" style="width: 100%;"
                                                name="member_group" id="member_group">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="member_phone">Nomor Handphone Anggota</label>
                                            <input type="text" class="form-control member_phone" name="member_phone"
                                                id="member_phone" placeholder="Nomor Handphone Anggota">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="member_card">Foto KTP</label>
                                            <div class="form-group">
                                                <img id="ktpPreview" src="" alt="Preview KTP"
                                                    style="max-width: 300px; max-height: 300px; display: none">
                                            </div>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input member_card"
                                                        id="member_card" name="member_card">
                                                    <label class="custom-file-label" for="member_card">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary float-right ml-1">Simpan</button>
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
            function fetchMemberAndRembugData() {
                $.ajax({
                    url: "{{ route('get_member_and_rembug_data') }}",
                    method: 'GET',
                    success: function(response) {
                        // Update nomor anggota
                        $('#member_number').val(response.member_number).change();

                        // Simpan pilihan yang sedang dipilih di select member_group
                        var selectedGroup = $('#member_group').val();

                        // Kosongkan opsi member_group (tetapi tidak menghapus pilihan yang sedang dipilih)
                        $('#member_group').empty().prepend(
                            '<option value=""></option>');

                        // Tambahkan opsi baru berdasarkan data yang didapat dari response
                        $.each(response.rembug_data, function(index, item) {
                            $('#member_group').append('<option value="' + item.id + '">(' + item
                                .no_rembug + ') ' + item.nama_rembug + '</option>');
                        });

                        // Pilih kembali opsi yang sebelumnya dipilih, jika ada
                        if (selectedGroup) {
                            $('#member_group').val(selectedGroup).change();
                        } else {
                            // Jika tidak ada pilihan sebelumnya, tambahkan opsi kosong sebagai placeholder
                            $('#member_group').prepend(
                                '<option value=""></option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Terjadi kesalahan: " + error);
                    }
                });
            }

            $('#member_card').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#ktpPreview').attr('src', e.target.result);
                    $('#ktpPreview').css("display", "");
                }
                reader.readAsDataURL(this.files[0]);
            });

            // Panggil fungsi saat halaman dimuat
            fetchMemberAndRembugData();

            // Update setiap 10 detik
            setInterval(fetchMemberAndRembugData, 10000);
        });
    </script>
@endsection
