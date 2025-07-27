
{{-- Section Header --}}
<div class="d-flex justify-content-center mb-3 mt-3">
    <div class="col-lg-10 col-md-12">
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body py-2 text-center">
                <h4 class="font-weight-bold m-0 text-dark">Informasi Koperasi</h4>
                <hr class="mt-2 mb-0" style="border-top: 2px solid #007bff; width: 60px; margin: 0 auto;">
            </div>
        </div>
    </div>
</div>
{{-- End Section Header --}}
<div class="scrollable-content">
    {{-- Content Messages Loop for Info --}}
    @foreach ($infoData as $informasi)
        <div class="d-flex justify-content-center">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">
                <a href="{{ route('detail_message', ['id' => $informasi->id]) }}" class="nav-link detail-anggota">
                    <div class="card  message-anggota {{ $informasi->class }}">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-12 col-12">
                                    <h5 class="font-weight-bold mb-3">{{ $informasi->judul }}</h5>
                                    <p>{{ $informasi->keterangan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endforeach
    
    {{-- Section Header --}}
    <div class="d-flex justify-content-center mb-3 mt-3">
        <div class="col-lg-10 col-md-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body py-2 text-center">
                    <h4 class="font-weight-bold m-0 text-dark">Pesan Anggota</h4>
                    <hr class="mt-2 mb-0" style="border-top: 2px solid #007bff; width: 60px; margin: 0 auto;">
                </div>
            </div>
        </div>
    </div>
    {{-- End Section Header --}}

    {{-- Content Messages Loop for Pesan --}}
    @foreach ($pesanData as $informasi)
        <div class="d-flex justify-content-center">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">
                <a href="{{ route('detail_message', ['id' => $informasi->id]) }}" class="nav-link detail-anggota">
                    <div class="card  message-anggota {{ $informasi->class }}">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-12 col-12">
                                    <h5 class="font-weight-bold mb-3">{{ $informasi->judul }}</h5>
                                    <p>{{ $informasi->keterangan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endforeach
    {{-- End Content Messages Loop --}}
</div>
<script>
    $(document).ready(function() {
        // Handle click events for navigation links
        $('.detail-anggota').on('click', function(event) {
            event.preventDefault();
            var url = $(this).attr('href');

            // Load the content via AJAX
            loadContent(url);
        });
    });
</script>
