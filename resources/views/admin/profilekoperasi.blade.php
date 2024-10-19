@extends('layout.main')

@section('content')
    <div id="content" style="height: 100vh">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <form action="{{ route('profile_koperasi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold" style="color: #08786B">Profile Koperasi</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="short_koperasi_name">Nama Koperasi*</label>
                                            <input type="text" class="form-control" name="short_koperasi_name"
                                                id="short_koperasi_name" placeholder="Nama Koperasi"
                                                value="{{ $dataProfile->nama_koperasi ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="long_koperasi_name">Nama Panjang Koperasi</label>
                                            <input type="text" class="form-control" name="long_koperasi_name"
                                                id="long_koperasi_name" placeholder="Nama Panjang Koperasi"
                                                value="{{ $dataProfile->nama_koperasi_lengkap ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="phone_koperasi">No Telpon Koperasi</label>
                                            <input type="text" class="form-control" name="phone_koperasi"
                                                id="phone_koperasi" placeholder="No Telpon Koperasi"
                                                value="{{ $dataProfile->phone_koperasi ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="address_koperasi">Alamat Koperasi</label>
                                            <textarea class="form-control" name="address_koperasi" id="address_koperasi">{{ $dataProfile->alamat_koperasi ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="bannerKoperasi">Logo Koperasi</label>
                                            <div class="form-group">
                                                <img id="bannerKoperasiPreview"
                                                    src="{{ $dataProfile && $dataProfile->logo_koperasi ? asset('storage/' . $dataProfile->logo_koperasi) : '' }}"
                                                    alt="Preview Banner"
                                                    style="max-width: 300px; max-height: 300px; display: {{ $dataProfile && $dataProfile->logo_koperasi ? 'block' : 'none' }}">
                                            </div>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="bannerKoperasi"
                                                        name="bannerKoperasi">
                                                    <label class="custom-file-label" for="bannerKoperasi">Choose
                                                        file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="bannerKoperasiIndonesia">Logo Koperasi Indonesia</label>
                                            <div class="form-group">
                                                <img id="bannerKoperasiIndonesiaPreview"
                                                    src="{{ $dataProfile && $dataProfile->logo_koperasi_indonesia ? asset('storage/' . $dataProfile->logo_koperasi_indonesia) : '' }}"
                                                    alt="Preview Banner"
                                                    style="max-width: 300px; max-height: 300px; display: {{ $dataProfile && $dataProfile->logo_koperasi_indonesia ? 'block' : 'none' }}">
                                            </div>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input"
                                                        id="bannerKoperasiIndonesia" name="bannerKoperasiIndonesia">
                                                    <label class="custom-file-label" for="bannerKoperasiIndonesia">Choose
                                                        file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="id_spreadsheet">ID Spreadsheet SHU Anggota</label>
                                            <input type="text" class="form-control id_spreadsheet" name="id_spreadsheet"
                                                id="id_spreadsheet" placeholder="ID Spreadsheet SHU Anggota"
                                                value="{{ $dataProfile->id_spreadsheet_shu ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="id_api_spreadsheet">ID API Spreadsheet SHU Anggota</label>
                                            <input type="text" class="form-control id_api_spreadsheet"
                                                name="id_api_spreadsheet" id="id_api_spreadsheet"
                                                placeholder="ID API Spreadsheet SHU Anggota"
                                                value="{{ $dataProfile->id_api_spreadsheet_shu ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="link_market">Link Market BMT</label>
                                            <input type="text" class="form-control link_market" name="link_market"
                                                id="link_market" placeholder="Link Market BMT"
                                                value="{{ $dataProfile->link_market ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="link_baitul_mal">Link Baitul Mal</label>
                                            <input type="text" class="form-control link_baitul_mal"
                                                name="link_baitul_mal" id="link_baitul_mal" placeholder="Link Baitul Mal"
                                                value="{{ $dataProfile->link_baitul_mal ?? '' }}">
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
    </div>

    <script>
        $(document).ready(function() {
            $('#bannerKoperasi').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#bannerKoperasiPreview').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(this.files[0]);
            });
            $('#bannerKoperasiIndonesia').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#bannerKoperasiIndonesiaPreview').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
@endsection
