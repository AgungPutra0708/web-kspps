{{-- Message Detail --}}
<div class="d-flex justify-content-center">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-2 text-center">
                @if ($informasi->banner)
                    <img class="img-fluid rounded" src="{{ asset('storage/' . $informasi->banner) }}" alt="Banner Image">
                @endif
                <h4 class="m-2">{{ $informasi->judul }}</h4>
                <p>{!! $informasi->keterangan !!}</p>
            </div>
        </div>
    </div>
</div>
{{-- Message Detail End --}}
