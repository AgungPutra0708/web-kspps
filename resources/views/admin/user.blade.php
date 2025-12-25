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

        <!-- Modal Ubah Status -->
        <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusModalLabel">Konfirmasi Ubah Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin mengubah status user ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="confirmChangeStatus">Ubah</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="qrModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">QR Code Anggota</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-center">
                        <div id="qrContainer">
                            <div class="text-muted">Loading QR Code...</div>
                        </div>

                        <div class="mt-3 d-flex justify-content-center gap-2">
                            <button class="btn btn-success mr-2" id="btnDownloadQR">Download</button>
                            <button class="btn btn-secondary" id="btnPrintQR">Print</button>
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
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'no_anggota',
                        name: 'no_anggota'
                    },
                    {
                        data: 'nama_anggota',
                        name: 'nama_anggota'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            let userId;
            let currentStatus;

            // Ketika tombol "Ubah Status" ditekan
            $(document).on('click', '.change-status', function() {
                userId = $(this).data('id');
                $('#statusModal').modal('show');
            });

            // Ketika tombol "Ubah" di modal diklik
            $('#confirmChangeStatus').click(function() {
                $.ajax({
                    url: "{{ route('management_user.changeStatus') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: userId,
                    },
                    success: function(response) {
                        $('#statusModal').modal('hide');
                        Swal.fire({
                            title: "Berhasil!",
                            text: response.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: "Error!",
                            text: "Gagal mengubah status.",
                            icon: "error"
                        });
                    }
                });
            });

            $(document).on('click', '.delete-user', function() {
                let url = $(this).data('url');
                if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}" // Pastikan CSRF token dikirim
                        },
                        success: function(response) {
                            alert(response.success);
                            $('#userMemberTable').DataTable().ajax
                                .reload(); // Refresh tabel setelah delete
                        },
                        error: function(xhr) {
                            alert('Terjadi kesalahan: ' + xhr.responseText);
                        }
                    });
                }
            });

            $(document).on('click', '.btn-qr', function() {
                const url = $(this).data('url');

                $('#qrContainer').html('<div class="text-muted">Loading QR Code...</div>');
                $('#qrModal').modal('show');

                fetch(url)
                    .then(res => res.text())
                    .then(svg => {
                        $('#qrContainer').html(svg);
                    })
                    .catch(() => {
                        $('#qrContainer').html('<div class="text-danger">Gagal load QR Code</div>');
                    });
            });

            $('#btnDownloadQR').on('click', function() {
                const svg = document.querySelector('#qrContainer svg');
                if (!svg) return;

                const serializer = new XMLSerializer();
                const svgStr = serializer.serializeToString(svg);

                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                const img = new Image();
                const svgBlob = new Blob([svgStr], {
                    type: 'image/svg+xml;charset=utf-8'
                });
                const url = URL.createObjectURL(svgBlob);

                img.onload = function() {
                    canvas.width = img.width;
                    canvas.height = img.height;

                    // background putih (biar gak transparan)
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);

                    ctx.drawImage(img, 0, 0);
                    URL.revokeObjectURL(url);

                    const pngUrl = canvas.toDataURL('image/png');

                    const a = document.createElement('a');
                    a.href = pngUrl;
                    a.download = 'qr-anggota.png';
                    a.click();
                };

                img.src = url;
            });

            $('#btnPrintQR').on('click', function() {
                const svgHtml = $('#qrContainer').html();
                if (!svgHtml) return;

                const win = window.open('', '_blank');
                win.document.write(`
                    <html>
                    <head>
                        <title>Print QR Code</title>
                        <style>
                            body {
                                display:flex;
                                justify-content:center;
                                align-items:center;
                                height:100vh;
                            }
                            svg {
                                width:300px;
                                height:300px;
                            }
                        </style>
                    </head>
                    <body>
                        ${svgHtml}
                        <script>
                            window.onload = function () {
                                window.print();
                                window.close();
                            }
                        <\/script>
                    </body>
                    </html>
                `);
            });
        });
    </script>
@endsection
