<!DOCTYPE html>
<html>

<head>
    <title>Cetak Angsuran</title>
    <style>
        body {
            font-family: monospace;
            width: 58mm;
            margin: 0;
            padding: 5px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .bold {
            font-weight: bold;
        }

        @media print {
            @page {
                size: 58mm auto;
                margin: 0;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="center bold">
        {{ Session::get('nama_koperasi') }}
    </div>
    @foreach($transaksis as $transaksi)

        <div class="line"></div>

        <table border="0">
            <tr>
                <td width="100">No Pinjaman</td>
                <td>:</td>
                <td>{{ $transaksi->pinjaman->no_pinjaman }}</td>
            </tr>
            <tr>
                <td>Nama Anggota</td>
                <td>:</td>
                <td>{{ $transaksi->anggota->nama_anggota }}</td>
            </tr>
            <tr>
                <td>Jenis</td>
                <td>:</td>
                <td>{{ $transaksi->pembiayaan->nama_pembiayaan }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td>Pokok</td>
                <td>:</td>
                <td>Rp {{ number_format($transaksi->angsur_pinjaman, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Margin</td>
                <td>:</td>
                <td>Rp {{ number_format($transaksi->angsur_margin, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Angsuran Ke</td>
                <td>:</td>
                <td>{{ $transaksi->angsuran_ke }}</td>
            </tr>
            <tr>
                <td>Oleh</td>
                <td>:</td>
                <td>{{ $transaksi->petugas->nama_petugas ?? 'Petugas' }}</td>
            </tr>
        </table>

        <div class="line"></div>

        @if(!$loop->last)
            <div style="margin-top:20px;"></div>
        @endif

    @endforeach
    <div class="center">
        Terima Kasih
    </div>

</body>

</html>