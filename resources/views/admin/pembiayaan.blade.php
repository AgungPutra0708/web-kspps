@extends('layout.main')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <form action="{{ route('pembiayaan.store') }}" method="post">
                    @csrf
                    <div class="card shadow mb-4">
                        <!-- Card Header Anggota -->
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: #08786B">Pembiayaan</h6>
                        </div>
                        <!-- Card Body Anggota -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="loan_number">Nomor Pembiayaan*</label>
                                        <input type="text" class="form-control loan_number" name="loan_number"
                                            id="loan_number" placeholder="Nomor Pembiayaan" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="loan_name">Nama Pembiayaan*</label>
                                        <input type="text" class="form-control loan_name" name="loan_name" id="loan_name"
                                            placeholder="Nama Pembiayaan">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="saving_desc">Keterangan</label>
                                        <textarea class="form-control saving_desc" name="saving_desc" id="saving_desc"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary float-right ml-1">Simpan</button>
                            <button type="button" class="btn btn-success float-right">Cetak</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-danger float-left">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    <script>
        $(document).ready(function() {
            function fetchLoanData() {
                $.ajax({
                    url: "{{ route('get_loan_data') }}",
                    method: 'GET',
                    success: function(response) {
                        // Update nomor anggota
                        $('#loan_number').val(response.loan_number).change();
                    },
                    error: function(xhr, status, error) {
                        console.error("Terjadi kesalahan: " + error);
                    }
                });
            }

            // Panggil fungsi saat halaman dimuat
            fetchLoanData();

            // Update setiap 10 detik
            setInterval(fetchLoanData, 10000);
        });
    </script>
@endsection
