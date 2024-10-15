{{-- Content Name --}}
<div class="d-flex justify-content-center mb-2 mt-2">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">
        <div class="card shadow card-border-radius">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8 col-8 align-content-center">
                        <h5 class="font-weight-bold">{{ Session::get('no_user') }}</h5>
                        <h3 class="font-weight-bold">{{ Session::get('nama_user') }}</h3>
                    </div>
                    <div class="col-lg-4 col-4 text-right">
                        <img class="img-profile rounded-circle img-fluid" style="max-width: 75px"
                            src="{{ asset('assets/img/undraw_profile.svg') }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Content Name End --}}

{{-- Content Saldo --}}
<div class="d-flex justify-content-center mb-2">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">
        <div class="card bg-primary text-white shadow card-border-radius">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-lg-10 col-8">
                        <h5 class="text-white font-weight-bold mb-3">BMT SARANA</h5>
                        <h4 class="text-white font-weight-bold">Saldo Total</h4>
                        <h4 class="text-white font-weight-bold">Rp <span
                                id="saldo-simpanan">{{ number_format($saldoSimpanan, 2, ',', '.') }}</span>
                        </h4>
                    </div>
                    <div class="col-lg-2 col-4 align-content-center">
                        <i class="fas fa-wallet fa-4x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Content Saldo End --}}

{{-- Content Menu --}}
<div class="d-flex justify-content-center">
    <div class="container-fluid">
        <div class="row">
            <div class="col-4 mb-3">
                <a href="#" class="btn btn-success d-flex flex-column align-items-center justify-content-center"
                    style="width: 100%; height: 100%;">
                    <i class="fas fa-wallet fa-2x mb-2"></i>
                    <span class="menu-text">Simpanan</span>
                </a>
            </div>
            <div class="col-4 mb-3">
                <a href="#" class="btn btn-success d-flex flex-column align-items-center justify-content-center"
                    style="width: 100%; height: 100%;">
                    <i class="fas fa-dollar-sign fa-2x mb-2"></i>
                    <span class="menu-text">Deposito</span>
                </a>
            </div>
            <div class="col-4 mb-3">
                <a href="#" class="btn btn-success d-flex flex-column align-items-center justify-content-center"
                    style="width: 100%; height: 100%;">
                    <i class="fa fa-money-bill fa-2x mb-2"></i>
                    <span class="menu-text">Pembiayaan</span>
                </a>
            </div>
            <div class="col-4 mb-3">
                <a href="#" class="btn btn-success d-flex flex-column align-items-center justify-content-center"
                    style="width: 100%; height: 100%;">
                    <i class="fas fa-handshake fa-2x mb-2"></i>
                    <span class="menu-text">SHU</span>
                </a>
            </div>
            <div class="col-4 mb-3">
                <a href="#" class="btn btn-success d-flex flex-column align-items-center justify-content-center"
                    style="width: 100%; height: 100%;">
                    <i class="fas fa-store fa-2x mb-2"></i>
                    <span class="menu-text">Toko</span>
                </a>
            </div>
            <div class="col-4 mb-3">
                <a href="#" class="btn btn-success d-flex flex-column align-items-center justify-content-center"
                    style="width: 100%; height: 100%;">
                    <i class="fas fa-mosque fa-2x mb-2"></i>
                    <span class="menu-text">Baitul Mal</span>
                </a>
            </div>
        </div>
    </div>
</div>
{{-- Content Menu End --}}
