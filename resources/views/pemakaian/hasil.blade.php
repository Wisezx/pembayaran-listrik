@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Hasil Pencarian Pemakaian</h2>

    @if ($data->isEmpty())
        <p>Tidak ada data ditemukan untuk No Kontrol tersebut.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tahun</th>
                    <th>Bulan</th>
                    <th>Meter Awal</th>
                    <th>Meter Akhir</th>
                    <th>Jumlah Pakai</th>
                    <th>Biaya Beban</th>
                    <th>Biaya Pemakaian</th>
                    <th>Status</th>
                    <th>Jumlah Bayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $pemakaian)
                    <tr>
                        <td>{{ $pemakaian->tahun }}</td>
                        <td>{{ $pemakaian->bulan }}</td>
                        <td>{{ $pemakaian->meterawal }}</td>
                        <td>{{ $pemakaian->meterakhir }}</td>
                        <td>{{ $pemakaian->jumlahpakai }}</td>
                        <td>{{ $pemakaian->biayabebanpemakai }}</td>
                        <td>{{ $pemakaian->biayapemakaian }}</td>
                        <td>{{ $pemakaian->status }}</td>
                        <td>{{ $pemakaian->jumlahbayar }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('pemakaian.form') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
