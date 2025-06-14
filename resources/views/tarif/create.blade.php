@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-white mb-6">Tambah Data Tarif</h1>

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-800 border border-green-300 rounded-lg shadow-sm">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Validasi Error --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 text-red-700 border border-red-300 rounded-lg shadow-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tarif.store') }}" method="POST" class="bg-white p-8 rounded-xl shadow-lg space-y-6 ring-1 ring-gray-200">
        @csrf

        {{-- Jenis Pelanggan --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pelanggan</label>
            <input type="text" name="Jenis_Plg" value="{{ old('Jenis_Plg') }}" required
                   placeholder="Contoh: Industri, Rumah Tangga"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        {{-- Tarif per kWh --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tarif / kWh</label>
            <div class="relative">
                <input type="number" step="0.01" name="tarifkwh" value="{{ old('tarifkwh') }}" required
                       placeholder="Contoh: 1350"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-14 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <span class="absolute right-4 top-2.5 text-sm text-gray-500">Rp</span>
            </div>
        </div>

        {{-- Biaya Beban --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Biaya Beban</label>
            <div class="relative">
                <input type="number" step="0.01" name="biayabeban" value="{{ old('biayabeban') }}" required
                       placeholder="Contoh: 50000"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-14 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <span class="absolute right-4 top-2.5 text-sm text-gray-500">Rp</span>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('tarif.index') }}"
               class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                Batal
            </a>
            <button type="submit"
                    class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
