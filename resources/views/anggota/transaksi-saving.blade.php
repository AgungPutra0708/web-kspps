{{-- Content Transaksi Simpanan --}}
<div class="scrollable-content">
    {{-- Section Header --}}
    <div class="d-flex justify-content-center mb-3 mt-3">
        <div class="col-lg-10 col-md-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body py-2 text-center">
                    <h4 class="font-weight-bold m-0 text-dark">Transaksi Simpanan</h4>
                    <hr class="mt-2 mb-0" style="border-top: 2px solid #007bff; width: 60px; margin: 0 auto;">
                </div>
            </div>
        </div>
    </div>
    {{-- End Section Header --}}

    {{-- Tabel Transaksi --}}
    <div class="d-flex justify-content-center mb-4">
        <div class="col-lg-10 col-md-12">
            <div class="card shadow-sm" style="max-height: 80vh; overflow-y: auto;">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover table-sm" id="transaksiSimpananTable">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Keterangan</th>
                                    <th>Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksiSimpanan as $item)
                                <tr class="{{ $item['metode_transaksi'] == '-' ? 'text-danger font-weight-bold' : '' }}">
                                    <td class="align-middle">{!! $item['keterangan'] !!}</td>
                                    <td class="align-middle text-right">{{ $item['nominal'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if (count($transaksiSimpanan) == 0)
                        <p class="text-center text-muted mt-3">Tidak ada data yang tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- End Tabel Transaksi --}}
</div>
{{-- End Content --}}

{{-- DataTables Init --}}
<script>
    $(document).ready(function() {
        $('#transaksiSimpananTable').DataTable({
            paging: true,
            pageLength: 5,
            searching: false,
            lengthChange: false,
            ordering: false,
            language: {
                emptyTable: "Tidak ada data yang tersedia",
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                infoEmpty: "Menampilkan 0 hingga 0 dari 0 entri",
                infoFiltered: "(disaring dari _MAX_ total entri)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    });
</script>