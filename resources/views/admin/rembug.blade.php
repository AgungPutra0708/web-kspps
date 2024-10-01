@extends('layout.main')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <form action="{{ route('kumpulan.store') }}" method="POST">
                    <div class="card shadow mb-4">
                        @csrf
                        <!-- Card Header Anggota -->
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: #08786B">Rembug/Area/Kumpulan</h6>
                        </div>
                        <!-- Card Body Anggota -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="group_number">Nomor Rembug/Area/Kumpulan*</label>
                                        <input type="text" class="form-control group_number" name="group_number"
                                            id="group_number" placeholder="Nomor Rembug/Area/Kumpulan" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="group_name">Nama Rembug/Area/Kumpulan*</label>
                                        <input type="text" class="form-control group_name" name="group_name"
                                            id="group_name" placeholder="Nama Rembug/Area/Kumpulan">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="group_address">Alamat</label>
                                        <textarea class="form-control group_address" name="group_address" id="group_address"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary float-right ml-1">Simpan</button>
                            <a href="{{ route('home') }}" class="btn btn-danger float-left">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    <script>
        $(document).ready(function() {
            // Fungsi untuk mendapatkan nomor post terbaru dengan format
            function getNextPostRembugNumber() {
                $.ajax({
                    url: "{{ route('get_number_kumpulan') }}",
                    method: 'GET',
                    success: function(response) {
                        $('#group_number').val(response).change();
                    }
                });
            }

            // Panggil fungsi saat halaman dimuat
            getNextPostRembugNumber();

            // Bisa juga dipanggil setiap beberapa detik jika diperlukan (misalnya 10 detik)
            setInterval(getNextPostRembugNumber, 10000); // Update setiap 10 detik
        });
    </script>
@endsection
