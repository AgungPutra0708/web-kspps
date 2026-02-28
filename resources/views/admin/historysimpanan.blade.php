@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: #08786B">History Transaksi Simpanan</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="historyTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal Transaksi</th>
                                                <th>Metode Transaksi</th>
                                                <th>Jumlah</th>
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
                ajax: "{{ route('history_simpanan.data', Crypt::encrypt($id_rekening_simpanan)) }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'tanggal_transaksi', name: 'tanggal_transaksi' },
                    { data: 'metode_transaksi', name: 'metode_transaksi' },
                    { data: 'jumlah_setoran', name: 'jumlah_setoran', className: 'text-right' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
                ],
                order: [[1, 'desc']]
            });
            
            // Function to format a number as Rupiah (without "Rp" and using dots for thousands, commas for decimals)
            function formatRupiah(number) {
                return number.toLocaleString('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                }).replace(/,/g, ',').replace(/\./g, '.');
            }

            // Loop through all elements with class 'rupiah' and format the text content
            $('.rupiah').each(function() {
                var value = parseFloat($(this).text());
                $(this).text(formatRupiah(value));
            });
        });
    </script>
@endsection
