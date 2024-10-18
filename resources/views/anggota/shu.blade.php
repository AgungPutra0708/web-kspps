{{-- Content SHU --}}
<div class="scrollable-content"> <!-- Make the section scrollable -->
    {{-- Content Header --}}
    <div class="d-flex justify-content-center mb-1 mt-2">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">
            <div class="card border-0">
                <div class="card-body py-1">
                    <div class="row">
                        <div class="col-lg-12 col-12 align-content-center">
                            <h3 class="font-weight-bold m-0">SHU Anggota</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Content Header End --}}

    <div class="d-flex justify-content-center mb-1 mt-2">
        <div class="col-12">
            <div class="card" style="max-height: 80vh">
                <div class="card-body">
                    @if (!empty($filteredData))
                        <table class="table table-bordered table-striped">
                            <thead class="table-primary">
                                <tr>
                                    <th>SHU (MODAL + JASA)</th>
                                    <th>BAITUL MAAL</th>
                                    <th>TOTAL BAGI HASIL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($filteredData as $index => $row)
                                    <tr>
                                        <td>{{ $row[2] }}</td> <!-- SHU -->
                                        <td>{{ $row[3] }}</td> <!-- Baitul Maal -->
                                        <td>{{ $row[4] }}</td> <!-- Total Bagi Hasil -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Data tidak ditemukan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
{{-- End Content SHU --}}
