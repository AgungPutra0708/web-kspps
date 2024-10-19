{{-- Content Header --}}
<div class="d-flex justify-content-center mb-1 mt-2">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">
        <div class="card border-0">
            <div class="card-body py-1">
                <div class="row">
                    <div class="col-lg-12 col-12 align-content-center">
                        @if ($informasi->kondisi_informasi === 'info')
                            <h3 class="font-weight-bold m-0">Informasi Koperasi</h3>
                        @endif
                        @if ($informasi->kondisi_informasi === 'pesan')
                            <h3 class="font-weight-bold m-0">Pesan Anggota</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Content Header End --}}

{{-- Message Detail --}}
<div class="d-flex justify-content-center">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-2 text-center">
                <div class="card" style="min-height: 70vh">
                    <div class="card-body">
                        @if ($informasi->banner)
                            <img class="img-fluid rounded" src="{{ asset('storage/' . $informasi->banner) }}"
                                alt="Banner Image">
                        @endif
                        <h4 class="m-2 font-weight-bold">{{ $informasi->judul }}</h4>
                        <p>{!! $informasi->keterangan !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Message Detail End --}}
