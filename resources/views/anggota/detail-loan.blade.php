{{-- Content Name --}}
<div class="scrollable-content"> <!-- Make the section scrollable -->
    <div class="d-flex justify-content-center mb-1 mt-2">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">
            <div class="card border-0">
                <div class="card-body py-1">
                    <div class="row">
                        <div class="col-lg-12 col-12 align-content-center">
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
                    <div class="card card-orange-border-radius text-white" style="max-height: 150px">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-8 col-8">
                                    <h6 class="text-white">{{ $item['no_pinjaman'] }}</h6>
                                    <h5 class="text-white">{{ $item['nama_pembiayaan'] }}</h5>
                                    <h5 class="text-white">Rp
                                        {{ number_format($item['besar_pinjaman'], 0, ',', '.') }}
                                    </h5>
                                </div>
                                <div class="col-lg-4 col-4">
                                    <img class="img-saldo img-fluid"
                                        src="{{ asset('assets/img/card_pembiayaan.svg') }}" />
                                    <p class="text-white mb-0">{{ $item['status_pinjaman'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-6">
                            <span class="text-secondary">Margin</span>
                            <br>
                            <span class="text-secondary">
                                Rp {{ number_format($item['besar_margin'], 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="col-6">
                            <span class="text-secondary">Jangka Waktu</span>
                            <br>
                            <span class="text-secondary">
                                {{ $item['lama_pinjaman'] . ' ' . $item['kondisi_pinjaman'] }}
                            </span>
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
