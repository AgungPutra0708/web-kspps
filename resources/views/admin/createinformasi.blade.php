@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content" style="height: 100vh">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <form action="{{ route('informasi_berita.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card shadow mb-4">
                            <!-- Card Header  -->
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold" style="color: #08786B">Tambah Informasi/Berita</h6>
                            </div>
                            <!-- Card Body  -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="judul_informasi">Judul Informasi/Berita*</label>
                                            <input type="text" class="form-control judul_informasi"
                                                name="judul_informasi" id="judul_informasi"
                                                placeholder="Judul Informasi/Berita">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="keterangan_informasi">Keterangan Informasi/Berita</label>
                                            <textarea class="form-control keterangan_informasi" name="keterangan_informasi" id="keterangan_informasi"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="banner">Banner/Foto</label>
                                            <div class="form-group">
                                                <img id="bannerPreview" src="" alt="Preview Banner"
                                                    style="max-width: 300px; max-height: 300px; display: none">
                                            </div>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input banner" id="banner"
                                                        name="banner">
                                                    <label class="custom-file-label" for="banner">Choose file</label>
                                                </div>
                                            </div>
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
    </div>
    <!-- /.container-fluid -->
    <script>
        $(document).ready(function() {
            $('#banner').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#bannerPreview').attr('src', e.target.result);
                    $('#bannerPreview').css("display", "");
                }
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
@endsection
