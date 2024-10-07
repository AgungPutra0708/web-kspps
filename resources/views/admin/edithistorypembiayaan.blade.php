@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <form action="{{ route('loan.update', Crypt::encrypt($transaction->id)) }}" method="POST">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold" style="color: #08786B">Edit Transaksi</h6>
                            </div>
                            <div class="card-body">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="angsur_pinjaman">Angsuran Pokok</label>
                                    <input type="text" name="angsur_pinjaman" class="form-control"
                                        value="{{ $transaction->angsur_pinjaman }}">
                                </div>
                                <div class="form-group">
                                    <label for="angsur_margin">Angsuran Margin</label>
                                    <input type="text" name="angsur_margin" class="form-control"
                                        value="{{ $transaction->angsur_margin }}">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">Update</button>
                                <a href="{{ route('loan.history', Crypt::encrypt($transaction->id_pinjaman)) }}"
                                    class="btn btn-danger">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
