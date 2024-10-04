@extends('layout.main')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <form action="{{ route('simpanan.store') }}" method="post">
                    @csrf
                    <div class="card shadow mb-4">
                        <!-- Card Header Anggota -->
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: #08786B">Simpanan</h6>
                        </div>
                        <!-- Card Body Anggota -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="saving_number">Nomor Simpanan*</label>
                                        <input type="text" class="form-control saving_number" name="saving_number"
                                            id="saving_number" placeholder="Nomor Simpanan" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="saving_name">Nama Simpanan*</label>
                                        <input type="text" class="form-control saving_name" name="saving_name"
                                            id="saving_name" placeholder="Nama Simpanan">
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
            function fetchSavingData() {
                $.ajax({
                    url: "{{ route('get_saving_data') }}",
                    method: 'GET',
                    success: function(response) {
                        // Update nomor anggota
                        $('#saving_number').val(response.saving_number).change();
                    },
                    error: function(xhr, status, error) {
                        console.error("Terjadi kesalahan: " + error);
                    }
                });
            }

            // Panggil fungsi saat halaman dimuat
            fetchSavingData();

            // Update setiap 10 detik
            setInterval(fetchSavingData, 10000);
        });
    </script>
@endsection
