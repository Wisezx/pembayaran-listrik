<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pencarian Pemakaian Listrik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col items-center py-10">

    <div class="w-full max-w-4xl bg-white p-6 rounded-xl shadow-lg">
        <h1 class="text-2xl font-bold mb-6 text-center"> Cari Pemakaian Listrik</h1>

        <form action="{{ route('welcome') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-4 mb-8">
            <input type="text" name="NoKontrol" id="NoKontrol" value="{{ request('NoKontrol') }}"
                   placeholder="Masukkan No Kontrol"
                   class="flex-1 min-w-0 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

            <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition duration-150">
                Cari
            </button>
        </form>

        @if(request('NoKontrol'))
            @if(isset($pemakaians) && $pemakaians->count())
                <h2 class="text-xl font-semibold mb-4">Hasil Pencarian:</h2>
                <div class="overflow-x-auto rounded-lg shadow-md">
                    <table class="min-w-full bg-white text-sm border border-gray-200">
                        <thead class="bg-red-100 text-gray-800 uppercase text-xs tracking-wide">
                            <tr>
                                <th class="p-3 border">NoKontrol</th>
                                <th class="p-3 border">Tahun</th>
                                <th class="p-3 border">Bulan</th>
                                <th class="p-3 border">Meter Awal</th>
                                <th class="p-3 border">Meter Akhir</th>
                                <th class="p-3 border">Jumlah Pakai</th>
                                <th class="p-3 border">Biaya Beban</th>
                                <th class="p-3 border">Biaya Pemakaian</th>
                                <th class="p-3 border">Jumlah Bayar</th>
                                <th class="p-3 border">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 divide-y">
                            @foreach($pemakaians as $p)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 border">{{ $p->NoKontrol }}</td>
                                    <td class="p-3 border">{{ $p->tahun }}</td>
                                    <td class="p-3 border">{{ $p->bulan }}</td>
                                    <td class="p-3 border">{{ $p->meterawal }}</td>
                                    <td class="p-3 border">{{ $p->meterakhir }}</td>
                                    <td class="p-3 border">{{ $p->jumlahpakai }}</td>
                                    <td class="p-3 border">Rp {{ number_format($p->biayabebanpemakai, 0, ',', '.') }}</td>
                                    <td class="p-3 border">Rp {{ number_format($p->biayapemakaian, 0, ',', '.') }}</td>
                                    <td class="p-3 border font-semibold text-red-700">Rp {{ number_format($p->jumlahbayar, 0, ',', '.') }}</td>
                                    <td class="p-3 border">
                                        <span class="px-3 py-1 text-xs font-medium rounded-full
                                            {{ $p->status === 'sukses' ? 'bg-green-200 text-green-700' :
                                                ($p->status === 'pending' ? 'bg-yellow-200 text-yellow-700' :
                                                'bg-gray-200 text-gray-700') }}">
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-red-500 mt-6">Tidak ada data untuk NoKontrol: <strong>{{ request('NoKontrol') }}</strong></p>
            @endif
        @endif

    </div>
</body>
</html>
