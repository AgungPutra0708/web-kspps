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
                                            @foreach ($historyData as $index => $history)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $history->tanggal_transaksi }}</td>
                                                    <td>{{ $history->angsur_pinjaman }}</td>
                                                    <td>{{ $history->angsur_margin }}</td>
                                                    <td>{{ $history->angsuran_ke }}</td>
                                                    <td>
                                                        <a href="{{ route('loan.edit', Crypt::encrypt($history->id)) }}"
                                                            class="btn btn-warning"><i class="fas fa-edit"></i>
                                                        </a>
                                                        <form
                                                            action="{{ route('loan.destroy', Crypt::encrypt($history->id)) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger"><i
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
@endsection
