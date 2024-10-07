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
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group ">
                                                    <label for="member_number" class="mr-2">Nomor Anggota*</label>
                                                    <div class="d-flex align-items-center">
                                                        <input type="text" class="form-control member_number"
                                                            name="member_number" id="member_number"
                                                            placeholder="Nomor Anggota" readonly style="flex: 1;">
                                                        <button class="btn btn-primary ml-2" type="button"
                                                            id="showSearchMember" style="height: 100%;"><i
                                                                class="fas fa-search"></i></button>
                                                    </div>
                                                </div>
                                            </div>
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
                                {{-- <button type="button" class="btn btn-success float-right">Cetak</button> --}}
                                <a href="{{ route('dashboard') }}" class="btn btn-danger float-left">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="memberModalLabel">Edit Anggota</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editMemberForm" method="POST" action="">
                            @csrf
                            @method('POST') <!-- This sets the form method to PUT for updating -->

                            <!-- Fields for member details -->
                            <div class="form-group">
                                <label for="select_member_name">Pilih Anggota*</label>
                                <select class="form-control select2 select_member_name" style="width: 100%;"
                                    name="select_member_name" id="select_member_name">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_member_name">Nama Anggota*</label>
                                <input type="text" class="form-control" id="edit_member_name"
                                    name="edit_member_name">
                            </div>
                            <div class="form-group">
                                <label for="edit_member_group">Rembug/Area/Kelompok*</label>
                                <select class="form-control select2" id="edit_member_group" name="edit_member_group"
                                    style="width: 100%;">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_member_phone">Nomor Handphone Anggota</label>
                                <input type="text" class="form-control" id="edit_member_phone"
                                    name="edit_member_phone">
                            </div>
                            <div class="form-group">
                                <label for="edit_member_card">Foto KTP</label>
                                <div class="form-group">
                                    <img id="edit_ktpPreview" src="" alt="Preview KTP"
                                        style="max-width: 300px; max-height: 300px; display: none;">
                                </div>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="edit_member_card"
                                            name="edit_member_card">
                                        <label class="custom-file-label" for="edit_member_card">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Delete form for deleting the member -->
                        <form id="deleteMemberForm" method="POST" action="">
                            @csrf
                            @method('POST') <!-- This sets the form method to DELETE -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="deleteMemberForm" class="btn btn-danger">Hapus</button>
                        <button type="submit" form="editMemberForm" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    <script>
        $(document).ready(function() {
            // Trigger modal when the search button is clicked
            $('#showSearchMember').on('click', function() {
                $('#edit_member_name').val('').change();
                $('#edit_member_group').val('').change();
                $('#edit_member_phone').val('').change();
                $('#edit_member_card').val('').change();
                $('#edit_ktpPreview').attr('src', '');
                $('#edit_ktpPreview').css("display", "none");
                $.ajax({
                    url: "{{ route('get_member_data') }}",
                    method: 'GET',
                    success: function(response) {
                        $('#select_member_name').empty().prepend('<option value=""></option>');

                        // Tambahkan opsi baru berdasarkan data yang didapat dari response
                        $.each(response.anggota_data, function(index, item) {
                            $('#select_member_name').append('<option value="' + item
                                .id +
                                '" data-name="' + item.nama_anggota +
                                '" data-phone="' + item.phone_anggota +
                                '" data-rembug="' + item.id_rembug +
                                '" data-ktp-image="' + item.ktp_image + '">(' + item
                                .no_anggota +
                                ') ' + item.nama_anggota + '</option>'
                            );
                        });

                        $('#select_member_name').prepend('<option value=""></option>');
                        $('#memberModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("Terjadi kesalahan: " + error);
                    }
                });

                $.ajax({
                    url: "{{ route('get_member_and_rembug_data') }}",
                    method: 'GET',
                    success: function(response) {
                        $('#edit_member_group').empty().prepend('<option value=""></option>');

                        $.each(response.rembug_data, function(index, item) {
                            $('#edit_member_group').append('<option value="' + item.id +
                                '">(' + item.no_rembug + ') ' + item.nama_rembug +
                                '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Terjadi kesalahan: " + error);
                    }
                });
            });

            // Update image when a member is selected
            $('#select_member_name').change(function(e) {
                var selectedOption = $(this).find('option:selected');
                var memberId = selectedOption.val();
                var updateUrl = "{{ route('member.update', ':id') }}";
                var deleteUrl = "{{ route('member.destroy', ':id') }}"; // Route for deleting
                var ktpImageSrc = selectedOption.data('ktp-image');
                var rembug = selectedOption.data('rembug');
                var phone = selectedOption.data('phone');
                var name = selectedOption.data('name');

                updateUrl = updateUrl.replace(':id', memberId);
                deleteUrl = deleteUrl.replace(':id', memberId);

                $('#edit_member_group').val(rembug).change();
                $('#edit_member_phone').val(phone).change();
                $('#edit_member_name').val(name).change();
                if (ktpImageSrc) {
                    $('#edit_ktpPreview').attr('src', ktpImageSrc).show();
                } else {
                    $('#edit_ktpPreview').hide();
                }
                $('#editMemberForm').attr('action', updateUrl);
                $('#deleteMemberForm').attr('action', deleteUrl);
            });

            $('#edit_member_card').change(function(e) {
                if ($(this).val()) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#edit_ktpPreview').attr('src', e.target.result);
                        $('#edit_ktpPreview').css("display", "");
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

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
