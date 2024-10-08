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
                                            @foreach ($historyData as $index => $transaction)
                                                <tr
                                                    class="{{ $transaction->metode_transaksi == '-' ? 'text-danger' : '' }}">
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $transaction->tanggal_transaksi }}</td>
                                                    <td>{{ $transaction->metode_transaksi }}</td>
                                                    <td class="rupiah">{{ $transaction->jumlah_setoran }}</td>
                                                    <td class="text-center">
                                                        <a href="{{ route('edit_transaction', Crypt::encrypt($transaction->id)) }}"
                                                            class="btn btn-warning"><i class="fas fa-edit"></i></a>
                                                        <form action="{{ route('delete_transaction', $transaction->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this transaction?');"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
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
