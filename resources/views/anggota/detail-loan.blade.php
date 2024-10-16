{{-- Content Name --}}
<div class="scrollable-content"> <!-- Make the section scrollable -->
    <div class="d-flex justify-content-center mb-1 mt-2">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">
            <div class="card border-0">
                <div class="card-body py-1">
                    <div class="row">
                        <div class="col-lg-12 col-12 align-content-center">
                            <h5 class="m-0">Saldo</h5>
                            <h3 class="font-weight-bold m-0">Pembiayaan Anggota</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Content Name End --}}

    {{-- Content Saldo --}}
    @foreach ($dataPinjaman as $item)
        <div class="d-flex justify-content-center mb-2">
            <div class="col-12">
                <a href="{{ route('transaksi_loan', ['id' => $item['id_pinjaman']]) }}" class="nav-link transaksi-loan">
                    <div class="card bg-primary text-white" style="max-height: 120px">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-12 col-12">
                                    <h6 class="text-white">{{ $item['no_pinjaman'] }}</h6>
                                    <h5 class="text-white">{{ $item['nama_pembiayaan'] }}</h5>
                                    <div class="row">
                                        <div class="col-8">
                                            <h5 class="text-white">Rp
                                                {{ number_format($item['besar_pinjaman'], 2, ',', '.') }}
                                        </div>
                                        <div class="col-4">
                                            <h5 class="text-white">
                                                {{ $item['lama_pinjaman'] . ' ' . $item['kondisi_pinjaman'] }}
                                        </div>
                                    </div>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endforeach
</div>
{{-- Content Saldo End --}}
<script>
    $(document).ready(function() {
        // Handle click events for navigation links
        $('.transaksi-loan').on('click', function(event) {
            event.preventDefault();
            var url = $(this).attr('href');

            // Load the content via AJAX
            loadContent(url);
        });
    });
</script>
