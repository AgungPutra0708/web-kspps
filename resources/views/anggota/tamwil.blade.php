{{-- Content Tamwil --}}
<div class="scrollable-content">
    {{-- Content Header --}}
    <div class="d-flex justify-content-center mb-2 mt-3">
        <div class="col-lg-10 col-md-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body py-2 text-center">
                    <h3 class="font-weight-bold m-0 text-primary">
                        Transaksi
                    </h3>
                </div>
            </div>
        </div>
    </div>
    {{-- Content Header End --}}

    <div class="d-flex justify-content-center mb-1 mt-2">
        <div class="col-12">
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs mb-3" id="transaksiTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="setoran-tab" data-toggle="tab" href="#setoran" role="tab"
                        aria-controls="setoran" aria-selected="true">Setoran</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="penarikan-tab" data-toggle="tab" href="#penarikan" role="tab"
                        aria-controls="penarikan" aria-selected="false">Penarikan</a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="transaksiTabContent">
                <!-- Setoran Tab -->
                <div class="tab-pane fade show active" id="setoran" role="tabpanel" aria-labelledby="setoran-tab">
                    <div class="mt-3">
                        Isi Setoran
                    </div>
                </div>

                <!-- Penarikan Tab -->
                <div class="tab-pane fade" id="penarikan" role="tabpanel" aria-labelledby="penarikan-tab">
                    <div class="mt-3">
                        Isi Penarikan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- End Content Tamwil --}}
