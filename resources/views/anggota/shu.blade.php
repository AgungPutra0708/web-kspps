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
                        <table class="table table-bordered table-striped table-responsive">
                            <tbody>
                                @foreach ($filteredData as $row)
                                    <tr>
                                        <td><strong>No User:</strong></td> <!-- No User -->
                                        <td>{{ $row[0] }}</td> <!-- No User -->
                                    </tr>
                                    <tr>
                                        <td><strong>Nama:</strong></td> <!-- Nama -->
                                        <td>{{ $row[1] }}</td> <!-- Nama -->
                                    </tr>
                                    <tr>
                                        <td><strong>SHU (MODAL + JASA):</strong></td> <!-- SHU -->
                                        <td>{{ $row[2] }}</td> <!-- SHU -->
                                    </tr>
                                    <tr>
                                        <td><strong>BAITUL MAAL:</strong></td> <!-- Baitul Maal -->
                                        <td>{{ $row[3] }}</td> <!-- Baitul Maal -->
                                    </tr>
                                    <tr>
                                        <td><strong>TOTAL BAGI HASIL:</strong></td>
                                        <td>{{ $row[4] }}</td>
                                        <!-- Total Bagi Hasil -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Data tidak ditemukan untuk pengguna ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
{{-- End Content SHU --}}
