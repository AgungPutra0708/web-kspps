<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Anggota</title>

    <style>
        /* RESET */
        * {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #0f6b3d;

            display: flex;
            justify-content: center;
            align-items: flex-start;
            /* 🔥 penting */

            min-height: 100svh;
        }

        /* CARD FULL MOBILE */
        .card {
            width: 100%;
            max-width: 430px;

            /* FIX OPPO / ANDROID */
            min-height: calc(100svh - env(safe-area-inset-bottom));
            background: #ffffff;
            border-radius: 22px;
            overflow: hidden;

            display: flex;
            flex-direction: column;
            border: 0.5px solid #ffffff;
        }

        /* HEADER */
        .header {
            background: #0f6b3d;
            color: #fff;
            text-align: center;
            padding: 18px;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* CONTENT */
        .content {
            padding: 20px;
            flex: 1;
        }

        .center {
            text-align: center;
        }

        .badge {
            background: #0f6b3d;
            color: #fff;
            display: inline-block;
            padding: 6px 20px;
            border-radius: 20px;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .nama {
            font-size: 22px;
            font-weight: bold;
            margin: 8px 0 14px;
        }

        .divider {
            border-top: 2px dashed #0f6b3d;
            margin: 15px 0;
        }

        /* SECTION */
        .section {
            margin-bottom: 18px;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .blue {
            background: #3498db;
        }

        .yellow {
            background: #f1c40f;
        }

        .green {
            background: #0f6b3d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        table td:last-child {
            text-align: right;
            font-weight: bold;
        }

        .total-blue {
            background: #3498db;
            color: #fff;
            font-weight: bold;
        }

        .total-yellow {
            background: #f1c40f;
            color: #fff;
            font-weight: bold;
        }

        .total-green {
            background: #0f6b3d;
            color: #fff;
            font-weight: bold;
        }

        .note {
            font-size: 12px;
            color: #555;
            margin-top: 6px;
        }

        .quote {
            background: #fff3cd;
            padding: 14px;
            border-radius: 10px;
            font-style: italic;
            font-size: 13px;
            text-align: center;
            margin-bottom: 10px;
        }

        /* FOOTER */
        .footer {
            background: #0f6b3d;
            color: #fff;
            text-align: center;
            font-size: 12px;
            padding: 12px;

            /* SAFE AREA ANDROID / iOS */
            padding-bottom: calc(12px + env(safe-area-inset-bottom));
        }

        .footer-top {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            padding: 14px 14px 0 14px;
        }

        .footer-icon img {
            width: 24px;
            height: 24px;
        }

        .footer-text {
            flex: 1;
            font-size: 9px;
            line-height: 1.3;
            color: #0f6b3d;
            text-align: justify;
        }

        .footer-text .link {
            color: #0f6b3d;
            text-decoration: underline;
        }

        .link-icon {
            color: #0f6b3d;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="header">DATA ANGGOTA</div>

        <div class="content" id="content">
            Memuat...
        </div>

        <div class="footer">
            Copyright © 2025 - BMT Sarana Wiraswasta Muslim
            <br>Melayani dengan Amanah dan Profesional
        </div>
    </div>

    <script>
        const params = new URLSearchParams(window.location.search);
        const token = params.get('token');

        const rupiah = (val) => {
            val = Number(val) || 0;
            return 'Rp. ' + val.toLocaleString('id-ID');
        };

        // parse "Rp 2,900,000" → 2900000
        const parseRupiah = (val) => {
            if (!val) return 0;
            return Number(
                val.toString()
                .replace(/[^0-9]/g, '')
            );
        };

        if (!token) {
            document.getElementById('content').innerHTML =
                '<p class="center">Token tidak ditemukan</p>';
        } else {
            fetch(`/api/anggota/${token}`)
                .then(res => res.json())
                .then(res => {
                    if (res.status !== 'valid') {
                        document.getElementById('content').innerHTML =
                            `<p class="center">${res.message}</p>`;
                        return;
                    }

                    const d = res.data;

                    console.log('DATA API:', d);

                    /* ================= SIMPANAN ================= */
                    let simpananHtml = '';
                    let totalSimpanan = 0;

                    (d.simpanan || []).forEach(item => {
                        if (item.utama === 'false') {
                            const saldo = Number(item.saldo_akhir) || 0;

                            simpananHtml += `
                            <tr>
                                <td>${item.nama_simpanan}</td>
                                <td>${rupiah(saldo)}</td>
                            </tr>
                        `;
                            totalSimpanan += saldo;
                        }
                    });

                    simpananHtml += `
                    <tr class="total-blue">
                        <td>Total Simpanan</td>
                        <td>${rupiah(totalSimpanan)}</td>
                    </tr>
                `;

                    /* ================= PEMBIAYAAN ================= */
                    let pembiayaanHtml = '';
                    let totalPembiayaan = 0;

                    (d.pinjaman || []).forEach(p => {
                        if (p.status_pinjaman !== 'Lunas') {
                            const sisa =
                                Number(p.sisa_besar_pinjaman || 0);

                            pembiayaanHtml += `
                            <tr>
                                <td>${p.no_pinjaman}</td>
                                <td>${rupiah(sisa)}</td>
                            </tr>
                        `;

                            totalPembiayaan += sisa;
                        }
                    });

                    pembiayaanHtml += `
                    <tr class="total-yellow">
                        <td>Sisa Pembiayaan</td>
                        <td>${rupiah(totalPembiayaan)}</td>
                    </tr>
                `;

                    /* ================= SHU ================= */
                    const shu = d.shu?.length ?
                        parseRupiah(d.shu[0][4] || d.shu[0][1]) :
                        0;

                    const totalInvestasi = Number(d.total_simpanan_utama || 0) + shu;

                    // HITUNG PERSENTASE SHU
                    const modalUtama = Number(d.total_simpanan_utama || 0);
                    const persentaseSHU = modalUtama ? ((shu / modalUtama) * 100).toFixed(1) : 0;

                    /* ================= RENDER ================= */
                    document.getElementById('content').innerHTML = `
                    <div class="center">
                        <div class="badge">${d.no_anggota}</div>
                        <div class="nama">${d.nama}</div>
                    </div>

                    <div class="divider"></div>

                    <div class="section">
                        <div class="section-title">
                            <span class="dot blue"></span> Simpanan
                        </div>
                        <table>${simpananHtml}</table>
                    </div>

                    <div class="section">
                        <div class="section-title">
                            <span class="dot yellow"></span> Pembiayaan
                        </div>
                        <table>${pembiayaanHtml}</table>
                    </div>

                    <div class="section">
                        <div class="section-title">
                            <span class="dot green"></span> Investasi
                        </div>
                        <table>
                            <tr>
                                <td>Partisipasi Modal</td>
                                <td>${rupiah(modalUtama)}</td>
                            </tr>
                            <tr>
                                <td>SHU (Bagi Hasil)</td>
                                <td>
                                    <div style="font-size:10px;color:#555;margin-bottom:2px;">
                                        +${persentaseSHU}% per Tahun
                                    </div>
                                    ${rupiah(shu)}
                                </td>
                            </tr>
                            <tr class="total-green">
                                <td>Total Investasi</td>
                                <td>${rupiah(totalInvestasi)}</td>
                            </tr>
                        </table>
                        <div class="note">*SHU dibagikan saat RAT</div>
                    </div>

                    <div class="quote">
                        “Simpanlah sebagian dari hartamu untuk kebaikan masa depanmu,
                        karena itu lebih baik bagimu.”<br>
                        — HR. Bukhari
                    </div>

                    <div class="footer-top">
                        <div class="footer-icon">
                            <a href="https://www.bmtsarana.id" target="_blank" class="link-icon">
                                <img src="https://img.icons8.com/?size=100&id=VJz2Ob51dvZJ&format=png&color=000000" alt="Website">
                            </a>
                            <a href="https://wa.me/6281217283960" target="_blank" class="link-icon">
                                <img src="https://img.icons8.com/?size=100&id=30448&format=png&color=000000" alt="WhatsApp">
                            </a>
                        </div>
                        <div class="footer-text">
                            Jika terdapat kesalahan atau perbedaan dengan buku tabungan, silakan hubungi Admin CS BMT Sarana
                            Wiraswasta Muslim<br>
                            <a href="https://www.bmtsarana.id" target="_blank" class="link">www.bmtsarana.id</a> |
                            <a href="https://wa.me/6281217283960" target="_blank" class="link">0812 1728 3960</a>
                        </div>
                    </div>
                `;
                })
                .catch(err => {
                    console.error('FETCH ERROR:', err);
                    document.getElementById('content').innerHTML =
                        '<p class="center">Gagal memuat data</p>';
                });
        }
    </script>

</body>

</html>
