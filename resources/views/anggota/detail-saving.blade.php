{{-- Content Name --}}
<div class="scrollable-content"> <!-- Make the section scrollable -->
    {{-- Content Header --}}
    <div class="d-flex justify-content-center mb-1 mt-2">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">
            <div class="card border-0">
                <div class="card-body py-1">
                    <div class="row">
                        <div class="col-lg-12 col-12 align-content-center">
                            <h3 class="font-weight-bold m-0">Modal Anggota</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Content Header End --}}

    {{-- Content Saldo --}}
    @foreach ($dataSimpanan as $item)
        @if ($item['utama'] == 'true')
            <div class="d-flex justify-content-center mb-2">
                <div class="col-12">
                    <a href="{{ route('transaksi_saving', ['id' => $item['id_simpanan']]) }}"
                        class="nav-link transaksi-saving p-0">
                        <div class="card card-primary-border-radius text-white shadow" style="max-height: 120px">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-lg-12 col-12">
                                        <h6 class="text-white">{{ $item['no_rekening_simpanan'] }}</h6>
                                        <h5 class="text-white font-weight-bold">{{ $item['nama_simpanan'] }}</h5>
                                        <h5 class="text-white">Rp {{ number_format($item['saldo_akhir'], 2, ',', '.') }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @endif
    @endforeach

    {{-- Content Header --}}
    <div class="d-flex justify-content-center mb-1 mt-2">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">
            <div class="card border-0">
                <div class="card-body py-1">
                    <div class="row">
                        <div class="col-lg-12 col-12 align-content-center">
                            <h3 class="font-weight-bold m-0">Simpanan Anggota</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Content Header End --}}

    <div class="d-flex justify-content-center mb-2">
        <div class="row w-100">
            @foreach ($dataSimpanan as $item)
                @if ($item['utama'] == 'false')
                    <div class="col-6 mb-2 {{ $loop->iteration % 2 == 0 ? 'pl-1' : 'pr-1' }}">
                        <a href="{{ route('transaksi_saving', ['id' => $item['id_simpanan']]) }}"
                            class="nav-link transaksi-saving p-0">
                            <div class="card" style="max-height: 120px; min-height: 120px">
                                <div class="card-body pt-1">
                                    <div class="row">
                                        <div class="col-12 pt-0">
                                            <h6 class="text-secondary">{{ $item['no_rekening_simpanan'] }}</h6>
                                            <h6 class="text-secondary font-weight-bold">{{ $item['nama_simpanan'] }}
                                            </h6>
                                            <h6 class="text-secondary">Rp
                                                {{ number_format($item['saldo_akhir'], 2, ',', '.') }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
{{-- Content Saldo End --}}
<script>
    $(document).ready(function() {
        // Handle click events for navigation links
        $('.transaksi-saving').on('click', function(event) {
            event.preventDefault();
            var url = $(this).attr('href');

            // Load the content via AJAX
            loadContent(url);
        });
    });
</script>
