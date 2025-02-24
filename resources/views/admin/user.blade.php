@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content" style="height: 100vh">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <!-- Card Header transaksi simpanan -->
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold" style="color: #08786B">Managemen User Anggota</h6>
                            <a href="{{ route('management_user.create') }}" class="btn btn-primary">Tambah</a>
                        </div>                        
                        <!-- Card Body transaksi simpanan -->
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="userMemberTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No. Anggota</th>
                                                <th>Nama Anggota</th>
                                                <th>Username</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    <script>
        $(document).ready(function() {
            let table = $('#userMemberTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('management_user') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'no_anggota', name: 'no_anggota' }, // Perbaiki: Tambahkan No. Anggota
                    { data: 'nama_anggota', name: 'nama_anggota' },
                    { data: 'username', name: 'username' }, // Perbaiki: Username seharusnya di sini
                    { data: 'status', name: 'status', orderable: false, searchable: false }, // Status dengan badge
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            $(document).on('click', '.delete-user', function() {
                let deleteUrl = $(this).data('url');

                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Data yang dihapus tidak bisa dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: "DELETE",
                            data: { _token: "{{ csrf_token() }}" },
                            success: function(response) {
                                Swal.fire({
                                    title: "User Deleted!",
                                    text: response.success,
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                // Reload DataTable
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Terjadi kesalahan saat menghapus data.",
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
