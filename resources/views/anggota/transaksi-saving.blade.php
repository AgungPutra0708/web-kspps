{{-- Content Name --}}
<div class="scrollable-content"> <!-- Make the section scrollable -->
    <div class="d-flex justify-content-center mb-1 mt-2">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8">
            <div class="card border-0">
                <div class="card-body py-1">
                    <div class="row">
                        <div class="col-lg-12 col-12 align-content-center">
                            <h4 class="font-weight-bold m-0">Transaksi Simpanan</h4>
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
                                <table class="table table-bordered table-striped" id="transaksiSimpananTable"
                                    width="100%" cellspacing="0">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Keterangan</th>
                                            <th>Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transaksiSimpanan as $item)
                                            <tr class="{{ $item['metode_transaksi'] == '-' ? 'text-danger' : '' }}">
                                                <td>{!! $item['keterangan'] !!}</td>
                                                <td>{{ $item['nominal'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Content Saldo End --}}

<script>
    $(document).ready(function() {
        // Inisialisasi DataTables
        $('#transaksiSimpananTable').DataTable({
            "paging": true, // Mengaktifkan paging
            "pageLength": 5, // Menampilkan 5 data per halaman
            "searching": false, // Menonaktifkan pencarian
            "lengthChange": false, // Menonaktifkan pemilihan panjang halaman
            "ordering": true, // Mengaktifkan pengurutan
            "language": {
                "emptyTable": "Tidak ada data yang tersedia",
                "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 hingga 0 dari 0 entri",
                "infoFiltered": "(disaring dari _MAX_ total entri)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });
</script>
