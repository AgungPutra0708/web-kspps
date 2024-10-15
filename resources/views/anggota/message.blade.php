{{-- Content Messages Loop --}}
@foreach ($dataInformasi as $informasi)
    <div class="d-flex justify-content-center mb-1 mt-2">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">
            <a href="{{ route('detail_message', ['id' => $informasi->id]) }}" class="nav-link detail-anggota">
                <div class="card shadow message-anggota {{ $informasi->class }}">
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
