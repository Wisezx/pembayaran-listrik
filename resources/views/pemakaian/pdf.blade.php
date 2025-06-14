<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Pemakaian Listrik</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .subtitle {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
        .page-break {
            page-break-after: always;
        }
        .filter-info {
            text-align: center;
            font-size: 12px;
            margin-bottom: 15px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h1>LAPORAN DATA PEMAKAIAN LISTRIK</h1>
    

    <div class="filter-info">{{ $filterText }}</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tahun</th>
                <th>Bulan</th>
                <th>No Kontrol</th>
                <th>Meter Awal</th>
                <th>Meter Akhir</th>
                <th>Jumlah Pakai</th>
                <th>Biaya Beban</th>
                <th>Biaya Pemakaian</th>
                <th>Jumlah Bayar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @if(count($pemakaians) > 0)
                @foreach($pemakaians as $index => $pemakaian)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pemakaian->tahun }}</td>
                        <td>
                            @php
                                $bulanNames = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ];
                            @endphp
                            {{ $bulanNames[$pemakaian->bulan] ?? $pemakaian->bulan }}
                        </td>
                        <td>{{ $pemakaian->NoKontrol }}</td>
                        <td>{{ $pemakaian->meterawal }}</td>
                        <td>{{ $pemakaian->meterakhir }}</td>
                        <td>{{ $pemakaian->jumlahpakai }}</td>
                        <td class="text-right">Rp{{ number_format($pemakaian->biayabebanpemakai, 2, ',', '.') }}</td>
                        <td class="text-right">Rp{{ number_format($pemakaian->biayapemakaian, 2, ',', '.') }}</td>
                        <td class="text-right">
                            {{ $pemakaian->jumlahbayar ? 'Rp' . number_format($pemakaian->jumlahbayar, 2, ',', '.') : '-' }}
                        </td>
                        <td>{{ $pemakaian->status }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="11" style="text-align: center;">Tidak ada data yang sesuai dengan filter</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>
