<div class="d-flex justify-content-center mb-5 mt-5">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">
        <div class="position-relative">

            <!-- Card content -->
            <div class="card shadow bg-light text-secondary mt-5">
                <!-- Image positioned at the top center of the card -->
                <div class="text-center img-profil">
                    <img class="img-fluid rounded-circle" src="{{ asset('assets/img/account.svg') }}"
                        style="width: 80px; height: 80px;" />
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-12 col-12">
                            <h5 class="font-weight-bold mb-3">{{ Session::get('no_user') }}</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-12">
                            <h5 class="font-weight-bold">{{ Session::get('nama_user') }}</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-6 ml-0">
                            <span>{{ $dataAnggota->rembug->nama_rembug }}</span>
                        </div>
                        <div class="col-lg-6 col-6 text-right">
                            <span>{{ $dataAnggota->phone_anggota }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mb-1 mt-2">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">
        <!-- Section for Layanan Anggota -->
        <h5 class="font-weight-bold">Layanan Anggota</h5>
        <a href="{{ $dataProfile && $dataProfile->phone_koperasi ? 'https://wa.me/' . $dataProfile->phone_koperasi : '#' }}"
            target="_blank" class="btn btn-success btn-lg btn-block mb-2">
            <span class="text">WHATSAPP BMT</span>
        </a>
        <a href="#" class="btn btn-warning btn-lg btn-block" data-toggle="modal" data-target="#profileModal">
            <span class="text">GANTI PASSWORD</span>
        </a>
        <a href="#" class="btn btn-danger btn-lg btn-block" data-toggle="modal" data-target="#logoutModal">
            <span class="text">LOG OUT</span>
        </a>
    </div>
</div>
