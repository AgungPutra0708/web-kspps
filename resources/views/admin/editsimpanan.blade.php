@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <form action="{{ route('update_transaction', Crypt::encrypt($transaction->id)) }}" method="POST">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold" style="color: #08786B">Edit Transaksi</h6>
                            </div>
                            <div class="card-body">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="jumlah">Jumlah</label>
                                    <input type="number" name="jumlah_setoran" class="form-control" id="jumlah"
                                        value="{{ $transaction->jumlah_setoran }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="metode">Metode Transaksi</label>
                                    <select name="metode_transaksi" class="form-control" id="metode" required>
                                        <option value="+"
                                            {{ $transaction->metode_transaksi == '+' ? 'selected' : '' }}>Setoran
                                        </option>
                                        <option value="-"
                                            {{ $transaction->metode_transaksi == '-' ? 'selected' : '' }}>Penarikan
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">Update</button>
                                <a href="{{ route('history', Crypt::encrypt($transaction->id_simpanan)) }}"
                                    class="btn btn-danger">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
