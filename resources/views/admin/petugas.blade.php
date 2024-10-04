@extends('layout.main')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <form action="{{ route('petugas.store') }}" method="post">
                    @csrf
                    <div class="card shadow mb-4">
                        <!-- Card Header Anggota -->
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: #08786B">Tambah Data Petugas</h6>
                        </div>
                        <!-- Card Body Anggota -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="petugas_number">Nomor Petugas*</label>
                                        <input type="text" class="form-control petugas_number" name="petugas_number"
                                            id="petugas_number" placeholder="Nomor Petugas" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="petugas_name">Nama Petugas*</label>
                                        <input type="text" class="form-control petugas_name" name="petugas_name"
                                            id="petugas_name" placeholder="Nama Petugas">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="petugas_username">Username Petugas</label>
                                        <input type="text" class="form-control petugas_username" name="petugas_username"
                                            id="petugas_username" placeholder="Username Petugas">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="petugas_password">Password Petugas</label>
                                        <input type="password" class="form-control petugas_password" name="petugas_password"
                                            id="petugas_password" placeholder="Password Petugas">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary float-right ml-1">Simpan</button>
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
            // Fungsi untuk mendapatkan nomor post terbaru dengan format
            function getNextPostPetugasNumber() {
                $.ajax({
                    url: "{{ route('get_number_petugas') }}",
                    method: 'GET',
                    success: function(response) {
                        $('#petugas_number').val(response).change();
                    }
                });
            }

            // Panggil fungsi saat halaman dimuat
            getNextPostPetugasNumber();

            // Bisa juga dipanggil setiap beberapa detik jika diperlukan (misalnya 10 detik)
            setInterval(getNextPostPetugasNumber, 10000); // Update setiap 10 detik
        });
    </script>
@endsection
