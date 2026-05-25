{{-- Content Name --}}
<div class="scrollable-content">
    {{-- Section Header --}}
    <div class="d-flex justify-content-center mb-3 mt-3">
        <div class="col-lg-10 col-md-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body py-2 text-center">
                    <h4 class="font-weight-bold m-0 text-dark">Pembiayaan Anggota</h4>
                    <hr class="mt-2 mb-0" style="border-top: 2px solid #ffc107; width: 60px; margin: 0 auto;">
                </div>
            </div>
        </div>
    </div>
    {{-- End Section Header --}}
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
                                    <h5 class="text-white">Rp {{ number_format($item['sisa_besar_pinjaman'] + $item['sisa_besar_margin'], 0, ',', '.') }}</h5>
                                </div>
                                <div class="col-lg-4 col-4">
                                    <img class="img-saldo img-fluid" src="{{ asset('assets/img/card_pembiayaan.svg') }}" />
                                    <p class="text-white mb-0">{{ $item['status_pinjaman'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-6">
                            <span class="text-secondary">Angsuran</span><br>
                            <span class="text-secondary">Rp {{ number_format($item['angsur_pinjaman'] + $item['angsur_margin'], 0, ',', '.') }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-secondary">Jangka Waktu</span><br>
                            <span class="text-secondary">{{ $item['lama_pinjaman'] . ' ' . $item['kondisi_pinjaman'] }}</span>
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
        $('.transaksi-loan').on('click', function(event) {
            event.preventDefault();
            var url = $(this).attr('href');
            loadContent(url);
        });
    });
</script>
