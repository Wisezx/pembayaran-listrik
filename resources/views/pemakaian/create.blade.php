@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-white mb-6">Tambah Data Pemakaian</h1>

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

    <form action="{{ route('pemakaian.store') }}" method="POST" class="space-y-6 bg-white p-6 rounded-lg shadow" id="pemakaianForm">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Tahun</label>
            <input type="number" name="tahun" id="tahun" value="{{ old('tahun', $currentYear) }}" required readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
            <p class="text-xs text-gray-500 mt-1">Tahun ditentukan otomatis berdasarkan urutan pembayaran</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Bulan</label>
            <input type="number" name="bulan" id="bulan" value="{{ old('bulan') }}" required
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            <p class="text-xs text-gray-500 mt-1">Bulan dapat dipilih sesuai keinginan.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">No Kontrol</label>
            <select name="NoKontrol" id="NoKontrol" required
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Pilih No Kontrol --</option>
                @foreach($pelanggans as $pelanggan)
                    <option value="{{ $pelanggan->NoKontrol }}" {{ old('NoKontrol') == $pelanggan->NoKontrol ? 'selected' : '' }}>
                        {{ $pelanggan->NoKontrol }} - {{ $pelanggan->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jenis Pelanggan</label>
            <input type="text" id="jenisPelanggan" readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Biaya Beban</label>
            <input type="number" step="0.01" name="biayabebanpemakai" id="biayabebanpemakai" value="{{ old('biayabebanpemakai') }}" required readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
            <p class="text-xs text-gray-500 mt-1">Biaya beban ditentukan otomatis berdasarkan jenis pelanggan</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tarif per kWh</label>
            <input type="number" step="0.01" id="tarifkwh" readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
            <p class="text-xs text-gray-500 mt-1">Tarif per kWh ditentukan otomatis berdasarkan jenis pelanggan</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Meter Awal</label>
            <input type="number" name="meterawal" id="meterawal" value="{{ old('meterawal') }}" required readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
            <p class="text-xs text-gray-500 mt-1">Meter awal diambil dari meter akhir bulan sebelumnya</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Meter Akhir</label>
            <input type="number" name="meterakhir" id="meterakhir" value="{{ old('meterakhir') }}" required
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            <p id="error-meterakhir" class="text-sm text-red-600 mt-1 hidden">Meter akhir tidak boleh kurang dari meter awal.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jumlah Pakai (kWh)</label>
            <input type="number" name="jumlahpakai" id="jumlahpakai" value="{{ old('jumlahpakai') }}" required readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
            <p class="text-xs text-gray-500 mt-1">Jumlah pakai dihitung otomatis (Meter Akhir - Meter Awal)</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Biaya Pemakaian</label>
            <input type="number" step="0.01" name="biayapemakaian" id="biayapemakaian" value="{{ old('biayapemakaian') }}" required readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
            <p class="text-xs text-gray-500 mt-1">Biaya pemakaian dihitung otomatis (Jumlah Pakai × Tarif per kWh)</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jumlah Bayar</label>
            <input type="number" step="0.01" name="jumlahbayar" id="jumlahbayar" value="{{ old('jumlahbayar') }}" readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
            <p class="text-xs text-gray-500 mt-1">Jumlah bayar dihitung otomatis (Biaya Beban + Biaya Pemakaian)</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <input type="text" value="Belum Bayar" readonly
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm bg-gray-100">
            <input type="hidden" name="status" value="Belum Bayar">
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
                Create
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const meterAwal = document.getElementById('meterawal');
        const meterAkhir = document.getElementById('meterakhir');
        const errorMsg = document.getElementById('error-meterakhir');

        function validateMeter() {
            if (parseFloat(meterAkhir.value) < parseFloat(meterAwal.value)) {
                errorMsg.classList.remove('hidden');
                meterAkhir.classList.add('border-red-500');
            } else {
                errorMsg.classList.add('hidden');
                meterAkhir.classList.remove('border-red-500');
            }
        }

        meterAkhir.addEventListener('input', validateMeter);
        meterAwal.addEventListener('input', validateMeter);
    });

document.addEventListener('DOMContentLoaded', function() {
    const noKontrolSelect = document.getElementById('NoKontrol');
    const meterAkhirInput = document.getElementById('meterakhir');

    noKontrolSelect.addEventListener('change', function() {
        const noKontrol = this.value;
        if (!noKontrol) return;

        fetch(`/api/pelanggan-info?NoKontrol=${noKontrol}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                document.getElementById('jenisPelanggan').value = data.pelanggan.Jenis_Plg || '';
                document.getElementById('biayabebanpemakai').value = data.biayabeban || 0;
                document.getElementById('tarifkwh').value = data.tarifkwh || 0;
                document.getElementById('meterawal').value = data.meterawal || 0;
                document.getElementById('bulan').value = data.nextMonth || 1;
                document.getElementById('tahun').value = data.nextYear || new Date().getFullYear();

                if (meterAkhirInput.value) {
                    calculateUsage();
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                alert('Terjadi kesalahan saat mengambil data pelanggan.');
            });
    });

    meterAkhirInput.addEventListener('input', calculateUsage);

    function calculateUsage() {
        const meterAwal = parseFloat(document.getElementById('meterawal').value) || 0;
        const meterAkhir = parseFloat(meterAkhirInput.value) || 0;
        const tarifKwh = parseFloat(document.getElementById('tarifkwh').value) || 0;
        const biayaBeban = parseFloat(document.getElementById('biayabebanpemakai').value) || 0;

        const jumlahPakai = meterAkhir - meterAwal;
        document.getElementById('jumlahpakai').value = jumlahPakai;

        const biayaPemakaian = jumlahPakai * tarifKwh;
        document.getElementById('biayapemakaian').value = biayaPemakaian.toFixed(2);

        const jumlahBayar = biayaBeban + biayaPemakaian;
        document.getElementById('jumlahbayar').value = jumlahBayar.toFixed(2);
    }

    if (noKontrolSelect.value) {
        noKontrolSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
