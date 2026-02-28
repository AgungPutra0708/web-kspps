@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: #08786B">History Transaksi Pembiayaan</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="historyTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal Transaksi</th>
                                                <th>Angsuran Pokok</th>
                                                <th>Angsuran Margin</th>
                                                <th>Angsuran Ke</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('cek_saldo') }}" class="btn btn-danger float-left">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            $('#historyTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('loan.history.data', Crypt::encrypt($id_pinjaman)) }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'tanggal_transaksi', name: 'tanggal_transaksi' },
                    { data: 'angsur_pinjaman', name: 'angsur_pinjaman', className: 'text-right' },
                    { data: 'angsur_margin', name: 'angsur_margin', className: 'text-right' },
                    { data: 'angsuran_ke', name: 'angsuran_ke' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
                ],
                order: [[1, 'desc']]
            });

        });
    </script>
@endsection
