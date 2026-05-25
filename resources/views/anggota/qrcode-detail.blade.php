{{-- SECTION HEADER --}}
<div class="px-3 pt-3">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body text-center py-3 bg-white">
            <h4 class="font-weight-bold mb-1 text-dark">
                Detail Bayar
            </h4>

            <div class="mx-auto"
                style="
                    width: 70px;
                    height: 4px;
                    border-radius: 20px;
                    background: linear-gradient(90deg, #0f9d8a, #3b82f6);
                ">
            </div>
        </div>
    </div>
</div>

{{-- CONTENT --}}
<div class="px-3 mt-3 payment-content mb-5">
    <div class="card border-0 shadow rounded-4"
        style="
            min-height: 72vh;
            background: #ffffff;
        ">

        <div class="card-body d-flex flex-column justify-content-center">

            {{-- ICON --}}
            <div class="text-center mb-2">
                <div class="mx-auto d-flex align-items-center justify-content-center"
                    style="
                        width: 75px;
                        height: 75px;
                        border-radius: 50%;
                        background: rgba(15,157,138,.12);
                    ">
                    <i class="fa fa-university"
                        style="
                            font-size: 32px;
                            color: #0f9d8a;
                        ">
                    </i>
                </div>
            </div>

            {{-- REKENING --}}
            <div class="text-center">
                <small class="text-muted d-block mb-1">
                    Rekening Tujuan
                </small>

                <h2 class="font-weight-bold mb-2 text-dark">
                    {{ $dataTransaksi['no_rekening'] }}
                </h2>
            </div>

            {{-- PENERIMA --}}
            <div class="text-center">
                <small class="text-muted d-block mb-1">
                    Nama Penerima
                </small>

                <h4 class="font-weight-bold text-uppercase text-dark mb-2">
                    {{ $dataTransaksi['nama_penerima'] }}
                </h4>
            </div>

            <input type="hidden" id="jenis_transaksi" value="{{ $dataTransaksi['jenis_transaksi'] }}">
            <input type="hidden" id="no_rekening" value="{{ $dataTransaksi['no_rekening'] }}">

            {{-- REKENING ASAL --}}
            <div class="mt-1">
                <label class="font-weight-bold text-dark mb-2">
                    Rekening Asal
                </label>

                <select id="paymentSimpananSelect" class="form-control">
                    <option value="" selected disabled>Pilih simpanan</option>
                </select>
            </div>

            {{-- NOMINAL --}}
            <div class="mt-1">
                <label class="font-weight-bold text-dark mb-2">
                    Jumlah Transfer
                </label>

                <div class="input-group input-group-lg">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-right-0 rounded-left-pill">
                            Rp
                        </span>
                    </div>

                    <input type="text"
                        id="nominal"
                        class="form-control border-left-0 rounded-right-pill text-right font-weight-bold"
                        placeholder="0"
                        value="{{ $dataTransaksi['total_angsur'] }}">

                </div>
            </div>

            {{-- KETERANGAN --}}
            <div class="mt-1">
                <label class="font-weight-bold text-dark mb-2">
                    Keterangan (opsional)
                </label>

                <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Keterangan"></textarea>
            </div>

            {{-- BUTTON --}}
            <button class="btn btn-lg btn-block mt-4 text-white font-weight-bold rounded-pill shadow-sm"
                onclick="prosesPembayaran()"
                style="
                    background: linear-gradient(90deg, #0f9d8a, #16c2a5);
                    border: none;
                    height: 52px;
                ">
                Lanjutkan Pembayaran
            </button>

        </div>
    </div>
</div>

<style>
    .payment-content{
        padding-bottom: 50px;
    }

    #nominal {
        height: 55px;
        font-size: 22px;
        letter-spacing: 1px;
    }

    #nominal:focus {
        box-shadow: none;
        border-color: #0f9d8a;
    }

    .card {
        transition: .2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
    }
</style>

<script>
    $(document).ready(function() {
        loadSimpananOptions();
    });

    // FORMAT RUPIAH
    $('#nominal').on('input', function() {
        let value = this.value.replace(/\D/g, '');
        this.value = new Intl.NumberFormat('id-ID').format(value);
    });

    function loadSimpananOptions(defaultType = '') {
        const select = $('#paymentSimpananSelect');
        select.prop('disabled', true);
        select.html('<option value="" selected disabled>Memuat simpanan...</option>');

        $.ajax({
            url: "{{ route('qr.get_simpanan') }}",
            method: 'GET',
            dataType: 'json'
        }).done(function(data) {
            select.empty();
            select.append('<option value="" selected disabled>Pilih simpanan</option>');

            if (Array.isArray(data) && data.length) {

                data.forEach(function(item) {
                    const value = item.id_rekening_simpanan;
                    const label = item.nama_simpanan || '';
                    if (!value) {
                        return;
                    }
                    select.append(`<option value="${value}" data-url="{{ url('/qr/generate-qr-simpanan/') }}/${item.id_rekening_simpanan}">${label}</option>`);
                });
            } else {
                select.append('<option value="" disabled>Tidak ada simpanan</option>');
            }

            if (defaultType) {
                select.val(defaultType);
            }

            select.prop('disabled', false);

            if (select.val()) {
                updateSimpananQr();
            } else {
                $('#qrContainer').html('<div class="text-muted">Pilih simpanan untuk menampilkan QR Code</div>');
            }
        }).fail(function() {
            select.empty();
            select.append('<option value="" disabled>Tidak dapat memuat simpanan</option>');
            select.prop('disabled', true);
            $('#qrContainer').html('<div class="text-danger">Gagal mengambil daftar simpanan</div>');
        });
    }

    // SUBMIT PEMBAYARAN
    function prosesPembayaran() {

        let nominal = $('#nominal').val().replace(/\./g, '');
        let jenis_transaksi = $('#jenis_transaksi').val();
        let no_rekening = $('#no_rekening').val();
        let rekening_asal = $('#paymentSimpananSelect').find('option:selected').val();
        let keterangan = $('#keterangan').val();

        if (!nominal) {
            Swal.fire({
                title: "Nominal Tidak Boleh Kosong",
                text: "Silakan masukkan jumlah nominal transfer.",
                icon: "warning",
                confirmButtonText: "OK"
            });
            return;
        }

        $.ajax({
            url: "{{ route('qr.process') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                nominal: nominal,
                jenis_transaksi: jenis_transaksi,
                no_rekening: no_rekening,
                rekening_asal: rekening_asal,
                keterangan: keterangan
            },
            success: function(res) {
                Swal.fire({
                    title: "Pembayaran Berhasil",
                    text: res.message,
                    icon: "success",
                    confirmButtonText: "OK"
                }).then(() => {
                    $('#main-content').load("{{ route('qr.index') }}");
                });
            },
            error: function(error) {
                Swal.fire({
                    title: "Transaksi Gagal",
                    text: error.responseJSON?.message || "Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        });
    }
</script>