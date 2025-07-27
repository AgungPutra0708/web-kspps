{{-- Content SHU --}}
<div class="scrollable-content">
    {{-- Content Header --}}
    <div class="d-flex justify-content-center mb-2 mt-3">
        <div class="col-lg-10 col-md-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body py-2 text-center">
                    <h3 class="font-weight-bold m-0 text-primary">
                        SHU Anggota
                    </h3>
                </div>
            </div>
        </div>
    </div>
    {{-- Content Header End --}}

    <div class="d-flex justify-content-center mb-4">
        <div class="col-lg-10 col-md-12">
            <div class="card shadow-sm" style="max-height: 80vh; overflow-y: auto;">
                <div class="card-body">
                    @if (!empty($filteredData))
                        @foreach ($filteredData as $row)
                            <div class="border rounded p-3 mb-4 bg-white">
                                <div class="row mb-2">
                                    <div class="col-md-5 font-weight-bold">No User</div>
                                    <div class="col-md-7">{{ $row[0] }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-5 font-weight-bold">Nama</div>
                                    <div class="col-md-7">{{ $row[1] }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-5 font-weight-bold">SHU (Modal + Jasa)</div>
                                    <div class="col-md-7">
                                        <span class="badge badge-success p-2">{{ $row[2] }}</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-5 font-weight-bold">Baitul Maal</div>
                                    <div class="col-md-7">
                                        <span class="badge badge-info p-2">{{ $row[3] }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5 font-weight-bold">Total Bagi Hasil</div>
                                    <div class="col-md-7">
                                        <span class="badge badge-primary p-2">{{ $row[4] }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-danger">
                            <p class="mb-0">Data tidak ditemukan untuk pengguna ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
{{-- End Content SHU --}}
