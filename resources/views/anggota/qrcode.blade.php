<div class="qr-container">

    <div class="scan-line"></div>

    <!-- CAMERA -->
    <div id="reader"></div>

    <!-- BOTTOM MENU -->
    <div class="qr-bottom">

        <!-- HANDLE -->
        <div class="drag-handle"></div>

        <!-- MENU -->
        <div class="menu-grid">
            <div class="menu-item btn-qr" data-url-base="{{ url('/anggota/qr') }}" data-default-type="pokok">
                <div class="icon-box">
                    <svg viewBox="0 0 24 24" width="26">
                        <path fill="#333" d="M3 9l1-5h16l1 5H3zm0 2h18v9H3v-9zm4 2v5h2v-5H7zm4 0v5h2v-5h-2z" />
                    </svg>
                </div>
                <p>Tampilkan QRIS</p>
            </div>

            <div class="menu-item" onclick="$('#uploadQR').click()">
                <div class="icon-box">
                    <svg viewBox="0 0 24 24" width="26">
                        <path fill="#333" d="M5 20h14v-2H5v2zM12 2l5 5h-3v6h-4V7H7l5-5z" />
                    </svg>
                </div>
                <p>Upload QR</p>
            </div>
        </div>

        <input type="file" id="uploadQR" hidden>

    </div>

</div>

<div class="modal fade" id="qrModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">QR Code Anggota</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <div class="form-group text-left mb-3">
                    <label for="paymentSimpananSelect" class="font-weight-bold">Pilih Simpanan untuk menerima</label>
                    <select id="paymentSimpananSelect" class="form-control">
                        <option value="" selected disabled>Pilih simpanan</option>
                    </select>
                </div>

                <div id="qrContainer" style="width: 100%;">
                    <div class="text-muted">Loading QR Code...</div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .qr-container {
        position: relative;
        height: 100vh;
        background: black;
        overflow: hidden;
    }

    #reader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 65vh;
        z-index: 1;
    }

    #reader video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;
    }

    /* TOP BAR */
    .qr-top-bar {
        position: absolute;
        top: 20px;
        left: 15px;
        right: 15px;
        display: flex;
        justify-content: space-between;
        z-index: 10;
    }

    .btn-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.8);
        border: none;
    }

    /* BOTTOM */
    .qr-bottom {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 35%;
        background: #f5f5f5;
        z-index: 20;

        padding: 10px 15px 25px;
        border-top-left-radius: 25px;
        border-top-right-radius: 25px;

        box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.2);
        padding-bottom: calc(20px + env(safe-area-inset-bottom));
    }

    /* HANDLE */
    .drag-handle {
        width: 50px;
        height: 5px;
        background: #ccc;
        border-radius: 10px;
        margin: 5px auto 15px;
    }

    .scan-line {
        position: fixed;

        /* posisinya harus match qrbox */
        width: 250px;
        height: 3px;

        left: 50%;
        transform: translateX(-50%);

        top: calc(32.5vh - 125px);
        /* start dari atas kotak */

        background: linear-gradient(to right, transparent, #00ffcc, transparent);
        box-shadow: 0 0 10px #00ffcc;

        z-index: 5;
        pointer-events: none;

        animation: scanMove 2s linear infinite;
    }

    @keyframes scanMove {
        0% {
            top: calc(32.5vh - 125px);
        }

        100% {
            top: calc(32.5vh + 125px);
        }
    }

    /* MENU */
    .menu-grid {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .menu-item {
        flex: 1;
        text-align: center;
        background: #fff;
        padding: 12px;
        border-radius: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .menu-item p {
        font-size: 12px;
        margin-top: 6px;
    }

    .icon-box {
        width: 55px;
        height: 55px;
        background: #eaeaea;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
        font-size: 22px;
    }

    /* ANIMASI MUNCUL DARI BAWAH */
    .qr-bottom {
        transform: translateY(100%);
        opacity: 0;
        animation: slideUp 0.4s ease forwards;
    }

    @keyframes slideUp {
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    #qrContainer {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 320px;
    }

    #qrContainer svg {
        width: 100%;
        max-width: 320px;
        height: auto;
    }
</style>

<script>
    window.qrResult = null;
    window.html5QrCode = null;

    $(document).ready(function() {
        // delay biar element reader sudah masuk DOM
        setTimeout(() => {
            if ($("#reader").length) {
                startScanner();
            }
        }, 100);

        $(document).on('click', '.btn-qr', function() {
            const baseUrl = $(this).data('url-base') || $(this).data('url') || '/anggota/qr';
            const defaultType = $(this).data('default-type') || '';
            window.qrBaseUrl = baseUrl;

            $('#qrContainer').html('<div class="text-muted">Loading QR Code...</div>');
            $('#qrModal').modal('show');
            loadSimpananOptions(defaultType);
        });

        $('#paymentSimpananSelect').on('change', function() {
            updateSimpananQr();
        });

        function loadSimpananOptions(defaultType = '') {
            const select = $('#paymentSimpananSelect');
            select.prop('disabled', true);
            select.html('<option value="" selected disabled>Memuat simpanan...</option>');

            $.ajax({
                url: "{{ route('qr.get_simpanan_terima') }}",
                method: 'GET',
                dataType: 'json'
            }).done(function(data) {
                select.empty();
                select.append('<option value="" selected disabled>Pilih simpanan</option>');

                if (Array.isArray(data) && data.length) {

                    console.log(data);
                    
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

        function updateSimpananQr() {
            const type = $('#paymentSimpananSelect').val();
            if (!type) {
                $('#qrContainer').html('<div class="text-muted">Pilih simpanan untuk menampilkan QR Code</div>');
                return;
            }

            const url = $('#paymentSimpananSelect').find('option:selected').data('url') || ``;
            $('#qrContainer').html('<div class="text-muted">Loading QR Code...</div>');

            fetch(url)
                .then(res => res.text())
                .then(svg => {
                    $('#qrContainer').html(svg);

                    $('#qrContainer svg').css({
                        width: '100%',
                        height: 'auto',
                        maxWidth: '320px',
                        display: 'block',
                        margin: '0 auto'
                    });
                })
                .catch(() => {
                    $('#qrContainer').html('<div class="text-danger">Gagal load QR Code</div>');
                });
        }

        $('#btnDownloadQR').on('click', function() {
            const svg = document.querySelector('#qrContainer svg');
            if (!svg) return;

            const serializer = new XMLSerializer();
            const svgStr = serializer.serializeToString(svg);

            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            const img = new Image();
            const svgBlob = new Blob([svgStr], {
                type: 'image/svg+xml;charset=utf-8'
            });
            const url = URL.createObjectURL(svgBlob);

            img.onload = function() {
                canvas.width = img.width;
                canvas.height = img.height;

                // background putih (biar gak transparan)
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                ctx.drawImage(img, 0, 0);
                URL.revokeObjectURL(url);

                const pngUrl = canvas.toDataURL('image/png');

                const a = document.createElement('a');
                a.href = pngUrl;
                a.download = 'qr-anggota.png';
                a.click();
            };

            img.src = url;
        });

        $('#btnPrintQR').on('click', function() {
            const svgHtml = $('#qrContainer').html();
            if (!svgHtml) return;

            const win = window.open('', '_blank');
            win.document.write(`
                    <html>
                    <head>
                        <title>Print QR Code</title>
                        <style>
                            body {
                                display:flex;
                                justify-content:center;
                                align-items:center;
                                height:100vh;
                            }
                            svg {
                                width:300px;
                                height:300px;
                            }
                        </style>
                    </head>
                    <body>
                        ${svgHtml}
                        <script>
                            window.onload = function () {
                                window.print();
                                window.close();
                            }
                        <\/script>
                    </body>
                    </html>
                `);
        });
    });

    // HANDLE APP BACKGROUND / BALIK LAGI
    document.addEventListener("visibilitychange", async () => {
        if (document.hidden) {
            // app ke background
            if (window.html5QrCode) {
                try {
                    await window.html5QrCode.stop();
                    await window.html5QrCode.clear();
                } catch (e) {}
                window.html5QrCode = null;
            }
        } else {
            // balik lagi
            restartScanner();
        }
    });

    // RESTART CAMERA
    async function restartScanner() {
        try {
            if (window.html5QrCode) {
                await window.html5QrCode.stop();
                await window.html5QrCode.clear();
                window.html5QrCode = null;
            }
        } catch (e) {}
        setTimeout(() => {
            if ($("#reader").length) {
                startScanner();
            }
        }, 200);
    }

    // START CAMERA
    function startScanner() {
        // cegah double scanner
        if (window.html5QrCode) {
            return;
        }
        // pastikan element ada
        if (!document.getElementById("reader")) {
            console.log("reader element tidak ditemukan");
            return;
        }
        window.html5QrCode = new Html5Qrcode("reader");
        window.html5QrCode.start({
                facingMode: "environment"
            }, {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                },
                aspectRatio: 1.777,
                disableFlip: true
            },
            // SUCCESS SCAN
            async (decodedText) => {
                    window.qrResult = decodedText;
                    try {
                        if (window.html5QrCode) {
                            await window.html5QrCode.stop();
                            await window.html5QrCode.clear();
                            window.html5QrCode = null;
                        }
                    } catch (e) {}
                    lanjutTransaksi();
                },
                // ERROR SCAN
                (errorMessage) => {
                    // optional
                    // console.log(errorMessage);
                }
        ).catch(err => {
            console.log("Camera start error:", err);
        });
    }

    // UPLOAD QR
    $('#uploadQR').on('change', async function(e) {

        const file = e.target.files[0];

        if (!file) {
            return;
        }

        try {

            // stop camera utama dulu
            if (window.html5QrCode) {

                await window.html5QrCode.stop();
                await window.html5QrCode.clear();

                window.html5QrCode = null;
            }

        } catch (e) {}

        const tempQr = new Html5Qrcode("reader");

        tempQr.scanFile(file, true)

            .then(async decodedText => {

                window.qrResult = decodedText;

                try {
                    await tempQr.clear();
                } catch (e) {}

                lanjutTransaksi();

            })

            .catch(async err => {

                try {
                    await tempQr.clear();
                } catch (e) {}

                Swal.fire({
                    title: "QR Tidak Valid",
                    text: error.responseJSON?.message || "Terjadi kesalahan. Silakan coba lagi.",
                    icon: "error",
                    confirmButtonText: "OK"
                });

                restartScanner();
            })

            .finally(() => {

                // reset input biar file sama bisa dipilih lagi
                $('#uploadQR').val('');

            });

    });

    // LANJUT TRANSAKSI
    async function lanjutTransaksi() {

        // matikan scanner sebelum pindah halaman
        try {

            if (window.html5QrCode) {

                await window.html5QrCode.stop();
                await window.html5QrCode.clear();

                window.html5QrCode = null;
            }

        } catch (e) {}

        $.ajax({
            url: "{{ route('qr.detail') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                qr_data: window.qrResult
            },

            success: function(data) {

                $('#main-content').html(data);

            },

            error: function(error) {
                Swal.fire({
                    title: "QR Tidak Valid",
                    text: error.responseJSON?.message || "Terjadi kesalahan. Silakan coba lagi.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        });
    }
</script>