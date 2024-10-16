{{-- Content Name --}}
<div class="scrollable-content"> <!-- Make the section scrollable -->
    <div class="d-flex justify-content-center mb-1 mt-2">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">
            <div class="card border-0">
                <div class="card-body py-1">
                    <div class="row">
                        <div class="col-lg-12 col-12 align-content-center">
                            <h5 class="m-0">Transaksi</h5>
                            <h3 class="font-weight-bold m-0">Pembiayaan Anggota</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Content Name End --}}

    {{-- Content Saldo --}}
    <div class="d-flex justify-content-center mb-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-12 col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="transaksiPinjamanTable" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Keterangan</th>
                                            <th>Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transaksiPinjaman as $item)
                                            <tr>
                                                <td>{!! $item['keterangan'] !!}</td>
                                                <td>{{ $item['nominal'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{ $transaksiPinjamanData->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Content Saldo End --}}
