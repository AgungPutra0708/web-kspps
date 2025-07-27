{{-- Content Name --}}

<div class="scrollable-content"> 
    {{-- Section Header --}}
    <div class="d-flex justify-content-center mb-3 mt-3">
        <div class="col-lg-10 col-md-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body py-2 text-center">
                    <h4 class="font-weight-bold m-0 text-dark">Modal Anggota</h4>
                    <hr class="mt-2 mb-0" style="border-top: 2px solid #007bff; width: 60px; margin: 0 auto;">
                </div>
            </div>
        </div>
    </div>
    {{-- End Section Header --}}



    {{-- Content Saldo Utama --}}

    @if ($totalSaldoUtama > 0)

        <div class="d-flex justify-content-center mb-2">

            <div class="col-12">

                <a href="{{ route('transaksi_saving', ['id' => Crypt::encrypt($idSimpananUtama)]) }}"

                    class="nav-link transaksi-saving p-0">

                    <div class="card card-primary-border-radius text-white shadow" style="max-height: 120px">

                        <div class="card-body">

                            <div class="row mb-3">

                                <div class="col-lg-12 col-12">

                                    <h6 class="text-white">Modal Pokok Wajib</h6>

                                    <h5 class="text-white">Rp {{ number_format($totalSaldoUtama, 0, ',', '.') }}</h5>

                                    <span style="font-size:10px; line-height:1; display:inline-block; transform:scaleY(0.9);">
                                        Modal Anggota Hanya Bisa Ditarik Saat RAT atau Keluar dari Keanggotaan Koperasi/BMT
                                    </span>

                                </div>
                                
                            </div>

                        </div>

                    </div>

                </a>

            </div>

        </div>

    @endif

    {{-- Content Saldo Utama End --}}
    {{-- Section Header --}}
    <div class="d-flex justify-content-center mb-3 mt-3">
        <div class="col-lg-10 col-md-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body py-2 text-center">
                    <h4 class="font-weight-bold m-0 text-dark">Simpanan Anggota</h4>
                    <hr class="mt-2 mb-0" style="border-top: 2px solid #007bff; width: 60px; margin: 0 auto;">
                </div>
            </div>
        </div>
    </div>
    {{-- End Section Header --}}



    <div class="d-flex justify-content-center mb-2">

        <div class="row w-100">

            @php

                $counter = 0; // Inisialisasi counter

            @endphp

            @foreach ($dataSimpanan as $item)

                @if ($item['utama'] == 'false')

                    <div class="col-6 mb-2 {{ $counter % 2 == 0 ? 'pl-1' : 'pr-1' }}">

                        <a href="{{ route('transaksi_saving', ['id' => $item['id_simpanan']]) }}"

                            class="nav-link transaksi-saving p-0">

                            <div class="card" style="max-height: 120px; min-height: 120px">

                                <div class="card-body pt-2">

                                    <div class="row">

                                        <div class="col-12 pt-0">

                                            <h6 class="text-secondary">{{ $item['no_rekening_simpanan'] }}</h6>

                                            <h6 class="text-secondary font-weight-bold">{{ $item['nama_simpanan'] }}

                                            </h6>

                                            <h6 class="text-secondary">Rp

                                                {{ number_format($item['saldo_akhir'], 0, ',', '.') }}

                                            </h6>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </a>

                    </div>

                    @php

                        $counter++; // Increment counter

                    @endphp

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

