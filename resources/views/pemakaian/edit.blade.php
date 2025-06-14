
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-white mb-6">Edit Data Pemakaian</h1>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pemakaian.update', $pemakaian->id) }}" method="POST" class="space-y-6 bg-white p-6 rounded-lg shadow" id="pemakaianForm">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">Tahun</label>
            <input type="number" name="tahun" value="{{ old('tahun', $pemakaian->tahun) }}" readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Bulan</label>
            <input type="number" name="bulan" value="{{ old('bulan', $pemakaian->bulan) }}" readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">No Kontrol</label>
            <select name="NoKontrol" required
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500" disabled>
                @foreach($pelanggans as $pelanggan)
                    <option value="{{ $pelanggan->NoKontrol }}" {{ $pemakaian->NoKontrol == $pelanggan->NoKontrol ? 'selected' : '' }}>
                        {{ $pelanggan->NoKontrol }} - {{ $pelanggan->nama }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="NoKontrol" value="{{ $pemakaian->NoKontrol }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jenis Pelanggan</label>
            <input type="text" id="jenisPelanggan" value="{{ $jenisPelanggan ?? '' }}" readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Biaya Beban</label>
            <input type="number" step="0.01" name="biayabebanpemakai" value="{{ old('biayabebanpemakai', $pemakaian->biayabebanpemakai) }}" readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tarif per kWh</label>
            <input type="number" step="0.01" id="tarifkwh" value="{{ $tarifkwh ?? '' }}" readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Meter Awal</label>
            <input type="number" name="meterawal" value="{{ old('meterawal', $pemakaian->meterawal) }}" readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Meter Akhir</label>
            <input type="number" name="meterakhir" id="meterakhir" value="{{ old('meterakhir', $pemakaian->meterakhir) }}"
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jumlah Pakai (kWh)</label>
            <input type="number" name="jumlahpakai" id="jumlahpakai" value="{{ old('jumlahpakai', $pemakaian->jumlahpakai) }}" readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Biaya Pemakaian</label>
            <input type="number" step="0.01" name="biayapemakaian" id="biayapemakaian" value="{{ old('biayapemakaian', $pemakaian->biayapemakaian) }}" readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jumlah Bayar</label>
            <input type="number" step="0.01" name="jumlahbayar" id="jumlahbayar" value="{{ old('jumlahbayar', $pemakaian->jumlahbayar) }}" readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <input type="text" value="{{ $pemakaian->status }}" readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
            <input type="hidden" name="status" value="{{ $pemakaian->status }}">
            <p class="text-xs text-gray-500 mt-1">Status tidak dapat diubah secara manual</p>
        </div>


        <div class="flex justify-end gap-2 mt-6">
            {{-- Tombol Batal --}}
            <a href="{{ route('pemakaian.index') }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md shadow hover:bg-gray-300 transition duration-150">
                Batal
            </a>

            {{-- Tombol Update --}}
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md shadow hover:bg-green-700 transition duration-150">
                Update
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('meterakhir').addEventListener('input', function () {
    const meterAwal = parseFloat(document.getElementById('meterawal').value) || 0;
    const meterAkhir = parseFloat(this.value) || 0;
    const tarifKwh = parseFloat(document.getElementById('tarifkwh').value) || 0;
    const biayaBeban = parseFloat(document.querySelector('input[name="biayabebanpemakai"]').value) || 0;

    if (meterAkhir < meterAwal) {
        alert('Meter akhir tidak boleh lebih kecil dari meter awal!');
        this.value = meterAwal;
        return;
    }

    const jumlahPakai = meterAkhir - meterAwal;
    document.getElementById('jumlahpakai').value = jumlahPakai;
    const biayaPemakaian = jumlahPakai * tarifKwh;
    document.getElementById('biayapemakaian').value = biayaPemakaian.toFixed(2);
    document.getElementById('jumlahbayar').value = (biayaPemakaian + biayaBeban).toFixed(2);
});
</script>
@endsection
