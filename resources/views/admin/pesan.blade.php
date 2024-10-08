@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content" style="height: 100vh">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <!-- Card Header Anggota -->
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: #08786B">Pesan Anggota</h6>
                        </div>
                        <!-- Card Body Anggota -->
                        <div class="card-body">
                            <div class="row justify-content-end mb-2">
                                <a href="{{ route('pesan_anggota.create') }}" class="btn btn-success"><i
                                        class="fas fa-plus mr-1"></i>Buat</a>
                            </div>
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="informasiTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Anggota</th>
                                                <th>Judul Pesan</th>
                                                <th>Tanggal Informasi/Berita</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Informasi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="edit_member_name">Pilih Nama Anggota*</label>
                                <select class="form-control select2 edit_member_name" style="width: 100%;"
                                    name="member_name" id="edit_member_name">
                                    <option></option>
                                </select>
                                <input type="hidden" class="form-control" id="id_member_name" name="id_member_name">
                            </div>
                            <div class="form-group">
                                <label for="edit_judul">Judul Informasi</label>
                                <input type="text" class="form-control" id="edit_judul" name="judul_informasi" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_keterangan">Keterangan</label>
                                <textarea class="form-control" id="edit_keterangan" name="keterangan_informasi"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="edit_banner">Banner (Optional)</label>
                                <input type="file" class="form-control" id="edit_banner" name="banner">
                            </div>
                            <input type="hidden" id="edit_id" name="id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form id="deleteForm" action="{{ route('informasi_berita.destroy') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin menghapus informasi ini?
                            <input type="hidden" id="delete_id" name="delete_id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

    <script>
        $(document).ready(function() {
            $('#informasiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('pesan_anggota') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_anggota',
                        name: 'nama_anggota'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: null, // For actions
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-warning btn-edit" data-id="${row.id}" data-id_anggota="${row.id_anggota}" data-judul="${row.judul}" data-keterangan="${row.keterangan}"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger btn-delete" data-toggle="modal" data-target="#deleteModal" data-id="${row.id}"><i class="fas fa-trash"></i></button>
                            `;
                        }
                    }
                ]
            });

            // Edit button click handler
            $(document).on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                const id_anggota = $(this).data('id_anggota');
                const judul = $(this).data('judul');
                const keterangan = $(this).data('keterangan');

                $.ajax({
                    url: "{{ route('get_member_data') }}",
                    method: 'GET',
                    success: function(response) {

                        // Simpan pilihan yang sedang dipilih di select member_group
                        var selectedMember = $('#edit_member_name').val();

                        // Kosongkan opsi member_group (tetapi tidak menghapus pilihan yang sedang dipilih)
                        $('#edit_member_name').empty().prepend('<option value=""></option>');

                        // Tambahkan opsi baru berdasarkan data yang didapat dari response
                        $.each(response.anggota_data, function(index, item) {
                            // Cek apakah item.id sama dengan id_anggota, jika ya, tambahkan atribut 'selected'
                            let selected = item.id == id_anggota ?
                                'selected="selected"' : '';
                            $('#edit_member_name').append(
                                '<option value="' + item.id + '" data-rembug="' +
                                item.nama_rembug + '" ' + selected + '>(' + item
                                .no_anggota + ') ' + item.nama_anggota + '</option>'
                            );
                        });

                        $('#edit_member_name').prepend('<option value=""></option>');
                    },
                    error: function(xhr, status, error) {
                        console.error("Terjadi kesalahan: " + error);
                    }
                });

                $('#edit_id').val(id);
                $('#edit_judul').val(judul);
                $('#id_member_name').val(id_anggota);
                $('#edit_keterangan').val(keterangan);
                $('#editForm').attr('action', `/pesan-anggota/${id}`);
                $('#edit_member_name').prop('disabled', true);
                $('#editModal').modal('show');
            });

            // Delete button click handler
            $(document).on('click', '.btn-delete', function() {
                const id = $(this).data('id');

                $('#delete_id').val(id);
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endsection
