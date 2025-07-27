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
                    <div class="mt-1">
                        <form id="setoranForm" enctype="multipart/form-data" method="POST" action="">
                            <div class="form-group">
                                <label for="nomorAnggotaSetoran">No. Anggota</label>
                                <input type="text" id="nomorAnggotaSetoran" name="nomorAnggotaSetoran" maxlength="7"
                                    placeholder="101xxxx" required class="form-control"
                                    value="{{ Session::get('no_user') }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="namaLengkapSetoran">Nama Anggota</label>
                                <input type="text" id="namaLengkapSetoran" name="namaLengkapSetoran"
                                    placeholder="Masukkan nama lengkap" required class="form-control"
                                    value="{{ Session::get('nama_user') }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="nomorRekeningSetoran">Nomor Rekening Anggota (5 Karakter)</label>
                                <select name="nomorRekeningSetoran" id="nomorRekeningSetoran" class="form-control"
                                    required>
                                    <option value="" disabled selected>Pilih Rekening</option>
                                    @foreach ($dataRekeningSimpanan as $item)
                                        <option value="{{ $item->no_rekening_simpanan }}">
                                            {{ $item->no_rekening_simpanan }} -
                                            {{ $item->simpanan->nama_simpanan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group amount-input">
                                <label for="jumlahSetoran">Nominal</label>
                                <input type="number" id="jumlahSetoran" name="jumlahSetoran" placeholder="10000"
                                    required class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="setoranMelalui">Setoran Melalui</label>
                                <select id="setoranMelalui" name="setoranMelalui" class="form-control" required>
                                    <option value="" disabled selected>Pilih metode setoran</option>
                                    <option value="Bank">Bank</option>
                                    <option value="QRIS">QRIS</option>
                                    <option value="Tunai">Tunai</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="buktiTransferSetoran">Upload Bukti Transfer</label>
                                <div class="custom-upload-wrapper">
                                    <label for="buktiTransferSetoran" class="custom-upload-label">
                                        <i class="fas fa-paperclip"></i> Upload Bukti Transfer
                                    </label>
                                    <input type="file" id="buktiTransferSetoran" name="buktiTransferSetoran"
                                        accept=".jpg,.png,.pdf" hidden>
                                    <p id="fileName" class="upload-file-name">Tidak ada file yang dipilih</p>
                                    <p class="upload-note">Format: JPG, PNG, atau PDF (Max 5MB)</p>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-block btn-primary">
                                <i class="fab fa-whatsapp whatsapp-icon"></i>
                                Kirim Konfirmasi via WhatsApp
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Penarikan Tab -->
                <!-- Penarikan Tab -->
                <div class="tab-pane fade" id="penarikan" role="tabpanel" aria-labelledby="penarikan-tab">
                    <div class="mt-1">
                        <form id="penarikanForm" enctype="multipart/form-data" method="POST" action="">
                            <div class="form-group">
                                <label for="nomorAnggotaPenarikan">No. Anggota</label>
                                <input type="text" id="nomorAnggotaPenarikan" name="nomorAnggotaPenarikan"
                                    maxlength="7" placeholder="101xxxx" required class="form-control"
                                    value="{{ Session::get('no_user') }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="namaLengkapPenarikan">Nama Anggota</label>
                                <input type="text" id="namaLengkapPenarikan" name="namaLengkapPenarikan"
                                    placeholder="Masukkan nama lengkap" required class="form-control"
                                    value="{{ Session::get('nama_user') }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="nomorRekeningPenarikan">Nomor Rekening Anggota</label>
                                <select name="nomorRekeningPenarikan" id="nomorRekeningPenarikan"
                                    class="form-control" required>
                                    <option value="" disabled selected>Pilih Rekening</option>
                                    @foreach ($dataRekeningSimpanan as $item)
                                        <option value="{{ $item->no_rekening_simpanan }}">
                                            {{ $item->no_rekening_simpanan }} -
                                            {{ $item->simpanan->nama_simpanan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group amount-input">
                                <label for="jumlahPenarikan">Nominal</label>
                                <input type="number" id="jumlahPenarikan" name="jumlahPenarikan"
                                    placeholder="10000" required class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="penarikanMelalui">Penarikan Melalui</label>
                                <select id="penarikanMelalui" name="penarikanMelalui" class="form-control" required>
                                    <option value="" disabled selected>Pilih metode penarikan</option>
                                    <option value="Tunai Langsung Kasir">Tunai Langsung Kasir</option>
                                    <option value="Transfer Ke Bank">Transfer Ke Bank</option>
                                    <option value="Transfer Ke E Wallet">Transfer Ke E Wallet</option>
                                </select>
                            </div>

                            <!-- Conditional Section for Tunai Langsung Kasir -->
                            <div id="jadwalSection" class="conditional-section hidden">
                                <h4 style="margin-bottom: 12px; color: #374151; font-size: 14px;">Jadwal Pengambilan
                                </h4>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="hariAmbil">Hari</label>
                                        <select id="hariAmbil" name="hariAmbil" class="form-control">
                                            <option value="" selected>Pilih hari</option>
                                            <option value="Senin">Senin</option>
                                            <option value="Selasa">Selasa</option>
                                            <option value="Rabu">Rabu</option>
                                            <option value="Kamis">Kamis</option>
                                            <option value="Jumat">Jumat</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="jamAmbil">Jam</label>
                                        <select id="jamAmbil" name="jamAmbil" class="form-control">
                                            <option value="" selected>Pilih jam</option>
                                            <option value="07:00">07:00</option>
                                            <option value="08:00">08:00</option>
                                            <option value="09:00">09:00</option>
                                            <option value="10:00">10:00</option>
                                            <option value="11:00">11:00</option>
                                            <option value="12:00">12:00</option>
                                            <option value="13:00">13:00</option>
                                            <option value="14:00">14:00</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Conditional Section for Transfer Ke Bank -->
                            <div id="bankSection" class="conditional-section hidden">
                                <h4 style="margin-bottom: 12px; color: #374151; font-size: 14px;">Data Bank Tujuan</h4>
                                <div class="form-group">
                                    <label for="namaBank">Nama Bank</label>
                                    <select id="namaBank" name="namaBank" class="form-control">
                                        <option value="" selected>Pilih bank</option>
                                        <option value="Bank BCA">Bank BCA</option>
                                        <option value="Bank BRI">Bank BRI</option>
                                        <option value="Bank BNI">Bank BNI</option>
                                        <option value="Bank Mandiri">Bank Mandiri</option>
                                        <option value="Bank BTN">Bank BTN</option>
                                        <option value="Bank Syariah Indonesia">Bank Syariah Indonesia</option>
                                        <option value="Bank Muamalat">Bank Muamalat</option>
                                        <option value="Bank Danamon">Bank Danamon</option>
                                        <option value="Bank CIMB Niaga">Bank CIMB Niaga</option>
                                        <option value="Bank Mega">Bank Mega</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nomorRekeningBank">Nomor Rekening Bank</label>
                                    <input type="text" id="nomorRekeningBank" name="nomorRekeningBank"
                                        class="form-control" placeholder="Masukkan nomor rekening">
                                </div>
                                <div class="form-group">
                                    <label for="atasNamaBank">Atas Nama</label>
                                    <input type="text" id="atasNamaBank" name="atasNamaBank" class="form-control"
                                        placeholder="Masukkan nama pemilik rekening">
                                </div>
                            </div>

                            <!-- Conditional Section for Transfer Ke E Wallet -->
                            <div id="ewalletSection" class="conditional-section hidden">
                                <h4 style="margin-bottom: 12px; color: #374151; font-size: 14px;">Data E-Wallet Tujuan
                                </h4>
                                <div class="form-group">
                                    <label for="jenisEwallet">Jenis E-Wallet</label>
                                    <select id="jenisEwallet" name="jenisEwallet" class="form-control">
                                        <option value="" selected>Pilih e-wallet</option>
                                        <option value="Dana">Dana</option>
                                        <option value="OVO">OVO</option>
                                        <option value="GoPay">GoPay</option>
                                        <option value="ShopeePay">ShopeePay</option>
                                        <option value="LinkAja">LinkAja</option>
                                        <option value="SeaBank">SeaBank</option>
                                        <option value="Jenius">Jenius</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nomorHandphone">Nomor Handphone</label>
                                    <input type="text" id="nomorHandphone" name="nomorHandphone"
                                        class="form-control" placeholder="Masukkan nomor handphone">
                                </div>
                                <div class="form-group">
                                    <label for="atasNamaEwallet">Atas Nama</label>
                                    <input type="text" id="atasNamaEwallet" name="atasNamaEwallet"
                                        class="form-control" placeholder="Masukkan nama pemilik e-wallet">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-block btn-primary">
                                <i class="fab fa-whatsapp whatsapp-icon"></i>
                                Kirim Konfirmasi via WhatsApp
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
{{-- End Content Tamwil --}}
<script>
    // Fungsi untuk menampilkan nama file
    document.getElementById('buktiTransferSetoran').addEventListener('change', function() {
        const fileName = this.files[0]?.name || "Tidak ada file yang dipilih";
        document.getElementById('fileName').textContent = fileName;
    });

    // Validasi Form Setoran
    document.getElementById('setoranForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Ambil semua nilai input
        const nomorAnggota = document.getElementById('nomorAnggotaSetoran').value;
        const namaLengkap = document.getElementById('namaLengkapSetoran').value;
        const nomorRekening = document.getElementById('nomorRekeningSetoran').value;
        const jumlahSetoran = document.getElementById('jumlahSetoran').value;
        const setoranMelalui = document.getElementById('setoranMelalui').value;
        const buktiTransfer = document.getElementById('buktiTransferSetoran').files[0];

        // Validasi manual
        let isValid = true;
        let errorMessage = "";

        if (!nomorAnggota) {
            isValid = false;
            errorMessage = "Nomor anggota harus diisi!";
        } else if (!nomorRekening) {
            isValid = false;
            errorMessage = "Nomor rekening harus dipilih!";
        } else if (!jumlahSetoran || jumlahSetoran < 1) {
            isValid = false;
            errorMessage = "Nominal setoran harus diisi dan minimal 1!";
        } else if (!setoranMelalui) {
            isValid = false;
            errorMessage = "Metode setoran harus dipilih!";
        } else if (!buktiTransfer) {
            isValid = false;
            errorMessage = "Bukti transfer wajib diupload!";
        }

        // Tampilkan error jika validasi gagal
        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal!',
                text: errorMessage,
                confirmButtonText: 'OK'
            });
            return;
        }

        // Kirim form jika validasi sukses
        const formData = new FormData(this);

        fetch("{{ route('kirim.whatsapp.setoran') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.whatsapp_url) {
                    window.open(data.whatsapp_url, '_blank');
                } else {
                    Swal.fire('Error', 'Gagal mengirim data', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Terjadi kesalahan', 'error');
            });
    });

    // Handler untuk perubahan metode penarikan
    document.getElementById('penarikanMelalui').addEventListener('change', function() {
        // Sembunyikan semua bagian kondisional
        document.querySelectorAll('.conditional-section').forEach(function(section) {
            section.classList.add('hidden');
        });

        // Tampilkan bagian yang sesuai
        if (this.value === 'Tunai Langsung Kasir') {
            document.getElementById('jadwalSection').classList.remove('hidden');
        } else if (this.value === 'Transfer Ke Bank') {
            document.getElementById('bankSection').classList.remove('hidden');
        } else if (this.value === 'Transfer Ke E Wallet') {
            document.getElementById('ewalletSection').classList.remove('hidden');
        }
    });

    // Validasi Form Penarikan
    document.getElementById('penarikanForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Ambil semua nilai input
        const nomorAnggota = document.getElementById('nomorAnggotaPenarikan').value;
        const namaLengkap = document.getElementById('namaLengkapPenarikan').value;
        const nomorRekening = document.getElementById('nomorRekeningPenarikan').value;
        const jumlahPenarikan = document.getElementById('jumlahPenarikan').value;
        const penarikanMelalui = document.getElementById('penarikanMelalui').value;

        // Validasi manual
        let isValid = true;
        let errorMessage = "";

        if (!nomorAnggota) {
            isValid = false;
            errorMessage = "Nomor anggota harus diisi!";
        } else if (!nomorRekening) {
            isValid = false;
            errorMessage = "Nomor rekening harus dipilih!";
        } else if (!jumlahPenarikan || jumlahPenarikan < 1) {
            isValid = false;
            errorMessage = "Nominal penarikan harus diisi dan minimal 1!";
        } else if (!penarikanMelalui) {
            isValid = false;
            errorMessage = "Metode penarikan harus dipilih!";
        }

        // Validasi bagian kondisional
        if (isValid) {
            if (penarikanMelalui === 'Tunai Langsung Kasir') {
                const hariAmbil = document.getElementById('hariAmbil').value;
                const jamAmbil = document.getElementById('jamAmbil').value;

                if (!hariAmbil || !jamAmbil) {
                    isValid = false;
                    errorMessage = "Hari dan jam pengambilan harus diisi!";
                }
            } else if (penarikanMelalui === 'Transfer Ke Bank') {
                const namaBank = document.getElementById('namaBank').value;
                const nomorRekeningBank = document.getElementById('nomorRekeningBank').value;
                const atasNamaBank = document.getElementById('atasNamaBank').value;

                if (!namaBank || !nomorRekeningBank || !atasNamaBank) {
                    isValid = false;
                    errorMessage = "Data bank tujuan harus diisi lengkap!";
                }
            } else if (penarikanMelalui === 'Transfer Ke E Wallet') {
                const jenisEwallet = document.getElementById('jenisEwallet').value;
                const nomorHandphone = document.getElementById('nomorHandphone').value;
                const atasNamaEwallet = document.getElementById('atasNamaEwallet').value;

                if (!jenisEwallet || !nomorHandphone || !atasNamaEwallet) {
                    isValid = false;
                    errorMessage = "Data e-wallet tujuan harus diisi lengkap!";
                }
            }
        }

        // Tampilkan error jika validasi gagal
        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal!',
                text: errorMessage,
                confirmButtonText: 'OK'
            });
            return;
        }

        // Kirim form jika validasi sukses
        const formData = new FormData(this);

        fetch("{{ route('kirim.whatsapp.penarikan') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.whatsapp_url) {
                    window.open(data.whatsapp_url, '_blank');
                } else {
                    Swal.fire('Error', 'Gagal mengirim data', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Terjadi kesalahan', 'error');
            });
    });
</script>
