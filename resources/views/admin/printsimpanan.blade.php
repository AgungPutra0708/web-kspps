<!DOCTYPE html>
<html>

<head>
    <title>Cetak Simpanan</title>
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
                <td>No Rek</td>
                <td>:</td>
                <td>{{ $transaksi->rekeningSimpanan->no_rekening_simpanan }}</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $transaksi->rekeningSimpanan->anggota->nama_anggota }}</td>
            </tr>
            <tr>
                <td>Jenis</td>
                <td>:</td>
                <td>{{ $transaksi->simpanan->nama_simpanan }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y H:i') }}</td>
            </tr>
            <tr>
                <td>{{ $transaksi->metode_transaksi === "+" ? "Setoran" : "Penarikan" }}</td>
                <td>:</td>
                <td>Rp {{ number_format($transaksi->jumlah_setoran, 0, ',', '.') }}</td>
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