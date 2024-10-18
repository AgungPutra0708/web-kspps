{{-- Content Name --}}
<div class="scrollable-content"> <!-- Make the section scrollable -->
    <div class="d-flex justify-content-center mb-1 mt-2">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">
            <div class="card border-0">
                <div class="card-body py-1">
                    <div class="row">
                        <div class="col-lg-12 col-12 align-content-center">
                            <h4 class="font-weight-bold m-0">Transaksi Pembiayaan</h4>
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
            <div class="card" style="max-height: 80vh">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-12 col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="transaksiPinjamanTable"
                                    width="100%" cellspacing="0">
                                    <thead class="table-warning">
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
