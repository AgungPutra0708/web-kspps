
    {{-- Section Header --}}
    <div class="d-flex justify-content-center mb-3 mt-3">
        <div class="col-lg-10 col-md-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body py-2 text-center">
                        @if ($informasi->kondisi_informasi === 'info')
                            <h3 class="font-weight-bold m-0">Informasi Koperasi</h3>
                        @endif
                        @if ($informasi->kondisi_informasi === 'pesan')
                            <h3 class="font-weight-bold m-0">Pesan Anggota</h3>
                        @endif
                    <hr class="mt-2 mb-0" style="border-top: 2px solid #007bff; width: 60px; margin: 0 auto;">
                </div>
            </div>
        </div>
    </div>
    {{-- End Section Header --}}

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
