{{-- Content Name --}}
<div class="d-flex justify-content-center mb-2 mt-2">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">
        <div class="row">
            <div class="col-lg-3 col-3 pr-0">
                <img class="img-profile img-fluid" loading="lazy"
                    src="{{ $dataProfile && $dataProfile->logo_koperasi_indonesia ? asset('storage/' . $dataProfile->logo_koperasi_indonesia) : '' }}" />
            </div>
            <div class="col-lg-9 col-9 align-content-center pl-0">
                <span class="fs-1">Assalamualaikum,</span>
                <h4 class="font-weight-bold">{{ Session::get('nama_user') }}</h4>
            </div>
        </div>
    </div>
</div>
{{-- Content Name End --}}
{{-- Content Saldo --}}
<div class="d-flex justify-content-center mb-3">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">
        <div class="card text-white card-border-radius">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-lg-12 col-12">
                        <h6 class="text-white">Saldo Simpanan</h6>
                        <h4 class="text-white font-weight-bold mb-2">
                            Rp <span id="saldo-simpanan">{{ number_format($saldoSimpanan, 0, ',', '.') }}</span>
                        </h4>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-8 col-8 align-content-center">
                        <h5 class="text-white font-weight-bold mb-0">
                            {{ Session::get('no_user') }}
                        </h5>
                    </div>
                    <div class="col-lg-4 col-4">
                        <img class="img-saldo" src="{{ asset('assets/img/wallet.svg') }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Content Saldo End --}}
{{-- Content Date --}}
<div class="d-flex justify-content-center mb-3">
    <div class="row text-center">
        <div class="col-lg-12 col-12">
            <h6 class="font-weight-bold">
                {{ $dataProfile && $dataProfile->nama_koperasi_lengkap ? $dataProfile->nama_koperasi_lengkap : $dataProfile->nama_koperasi }}
            </h6>
            <span class="menu-text">{{ $gregorianDate }} - {{ $hijriDate }}</span>
        </div>
    </div>
</div>
{{-- Content Date End --}}
{{-- Content Menu --}}
<div class="d-flex justify-content-center">
    <div class="container-fluid">
        <div class="row">
            <div class="col-4 mb-3">
                <a href="{{ route('detail_saving') }}"
                    class="btn btn-outline-dark d-flex flex-column align-items-center justify-content-center btn-home">
                    <img class="img-button img-fluid" src="{{ asset('assets/img/wallet_button.svg') }}" />
                    <span class="menu-text">Simpanan</span>
                </a>
            </div>
            <div class="col-4 mb-3">
                <a href="{{ route('detail_loan') }}"
                    class="btn btn-outline-dark d-flex flex-column align-items-center justify-content-center btn-home">
                    <img class="img-button img-fluid" src="{{ asset('assets/img/card.svg') }}" />
                    <span class="menu-text">Pembiayaan</span>
                </a>
            </div>
            <div class="col-4 mb-3">
                <a href="{{ route('shu') }}"
                    class="btn btn-outline-dark d-flex flex-column align-items-center justify-content-center btn-home">
                    <img class="img-button img-fluid" src="{{ asset('assets/img/chart.svg') }}" />
                    <span class="menu-text">SHU</span>
                </a>
            </div>
            <div class="col-4 mb-3">
                <a href="{{ route('market') }}"
                    class="btn btn-outline-dark d-flex flex-column align-items-center justify-content-center btn-home">
                    <img class="img-button img-fluid" src="{{ asset('assets/img/market.svg') }}" />
                    <span class="menu-text">Toko</span>
                </a>
            </div>
            <div class="col-4 mb-3">
                <a href="{{ route('tamwil') }}"
                    class="btn btn-outline-dark flex-column align-items-center justify-content-center btn-home">
                    <img class="img-button img-fluid" src="{{ asset('assets/img/link.svg') }}" />
                    <span class="menu-text">Transaksi</span>
                </a>
            </div>
            <div class="col-4 mb-3">
                <a href="{{ route('message') }}"
                    class="btn btn-outline-dark flex-column align-items-center justify-content-center btn-home">
                    <img class="img-button img-fluid" src="{{ asset('assets/img/info.svg') }}" />
                    <span class="menu-text">Informasi</span>
                </a>
            </div>
        </div>
    </div>
</div>
{{-- Content Menu End --}}
<script>
    $(document).ready(function() {
        // Handle click events for navigation links
        $('.btn-home').on('click', function(event) {
            event.preventDefault();
            var url = $(this).attr('href');
            // Load the content via AJAX
            loadContent(url);
        });
    });
</script>
