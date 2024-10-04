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
                            <h6 class="m-0 font-weight-bold" style="color: #08786B">Informasi & Berita</h6>
                        </div>
                        <!-- Card Body Anggota -->
                        <div class="card-body">
                            <div class="row justify-content-end mb-2">
                                <a href="{{ route('informasi_berita.create') }}" class="btn btn-success"><i
                                        class="fas fa-plus mr-1"></i>Buat</a>
                            </div>
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="informasiTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Judul Informasi/Berita</th>
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
    </div>
    <!-- /.container-fluid -->

    <script>
        $(document).ready(function() {
            $('#informasiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('informasi_berita') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
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
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endsection
