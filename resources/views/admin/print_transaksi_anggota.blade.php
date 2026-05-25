<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Transaksi Anggota</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }

        .container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #08786B;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #08786B;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 12px;
            color: #666;
        }

        .print-date {
            text-align: right;
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }

        .transaction-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .transaction-header {
            background-color: #08786B;
            color: white;
            padding: 10px 15px;
            border-radius: 4px 4px 0 0;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .transaction-content {
            border: 1px solid #ddd;
            border-radius: 0 0 4px 4px;
            padding: 15px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-label {
            font-weight: bold;
            color: #08786B;
            width: 35%;
        }

        .info-value {
            width: 65%;
            text-align: left;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }

        .badge-info {
            background-color: #17a2b8;
        }

        .badge-warning {
            background-color: #ffc107;
            color: black;
        }

        .badge-success {
            background-color: #28a745;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            border: 1px solid #000;
        }

        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .table th {
            font-weight: bold;
        }

        .amount {
            text-align: right;
            font-weight: bold;
        }

        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #08786B;
            font-weight: bold;
            font-size: 16px;
            color: #08786B;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
            color: #666;
        }

        @media print {
            body {
                background-color: white;
            }

            .container {
                box-shadow: none;
                padding: 0;
            }

            .transaction-section {
                page-break-inside: avoid;
            }

            .footer {
                margin-top: 50px;
            }
        }

        @page {
            margin: 20mm;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Laporan Transaksi Anggota</h1>
        </div>

        <div class="print-date">
            Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}
        </div>

        <!-- Transactions -->
        @if($transaksis->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 4%">No</th>
                        <th style="width: 10%">No Anggota Asal</th>
                        <th style="width: 16%">Nama Anggota Asal</th>
                        <th style="width: 14%">No Rekening Asal</th>
                        <th style="width: 10%">No Anggota Tujuan</th>
                        <th style="width: 16%">Nama Anggota Tujuan</th>
                        <th style="width: 14%">No Rekening Tujuan</th>
                        <th style="width: 8%">Jenis Transaksi</th>
                        <th style="width: 10%">Jumlah</th>
                        <th style="width: 12%">Tanggal Transaksi</th>
                        <th style="width: 14%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksis as $index => $transaksi)
                        @php
                            $noAnggotaTujuan = '-';
                            $namaAnggotaTujuan = '-';
                            $noRekeningTujuan = $transaksi->no_rekening_simpanan_tujuan ?: $transaksi->no_pinjaman_tujuan ?: '-';

                            if ($transaksi->rekeningSimpananTujuan && $transaksi->rekeningSimpananTujuan->anggota) {
                                $noAnggotaTujuan = $transaksi->rekeningSimpananTujuan->anggota->no_anggota;
                                $namaAnggotaTujuan = $transaksi->rekeningSimpananTujuan->anggota->nama_anggota;
                            } elseif ($transaksi->pinjamanTujuan && $transaksi->pinjamanTujuan->anggota) {
                                $noAnggotaTujuan = $transaksi->pinjamanTujuan->anggota->no_anggota;
                                $namaAnggotaTujuan = $transaksi->pinjamanTujuan->anggota->nama_anggota;
                            }
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $transaksi->anggota->no_anggota ?? '-' }}</td>
                            <td>{{ $transaksi->anggota->nama_anggota ?? '-' }}</td>
                            <td>{{ $transaksi->no_rekening_simpanan_asal ?? '-' }}</td>
                            <td>{{ $noAnggotaTujuan }}</td>
                            <td>{{ $namaAnggotaTujuan }}</td>
                            <td>{{ $noRekeningTujuan }}</td>
                            <td>{{ $transaksi->jenis_transaksi }}</td>
                            <td class="amount">Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y H:i') }}</td>
                            <td>{{ $transaksi->keterangan ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="summary">
                <div class="summary-row">
                    <span>Total Transaksi:</span>
                    <span>{{ $transaksis->count() }}</span>
                </div>
                <div class="summary-row">
                    <span>Total Jumlah:</span>
                    <span class="amount">Rp {{ number_format($transaksis->sum('jumlah'), 0, ',', '.') }}</span>
                </div>
            </div>
        @else
            <div style="padding: 20px; background-color: #f8f9fa; border: 1px solid #ddd; border-radius: 4px; text-align: center; color: #666;">
                Tidak ada data transaksi
            </div>
        @endif
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
