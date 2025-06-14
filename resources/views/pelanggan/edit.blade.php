@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-white mb-8">Edit Pelanggan</h1>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 text-red-700 border border-red-300 rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST" class="bg-white p-8 rounded-xl shadow-lg space-y-6 border border-gray-100">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama</label>
            <input type="text" name="Nama" value="{{ old('Nama', $pelanggan->Nama) }}"
                class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none border-gray-300" placeholder="Masukkan nama pelanggan">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat</label>
            <input type="text" name="Alamat" value="{{ old('Alamat', $pelanggan->Alamat) }}"
                class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none border-gray-300" placeholder="Masukkan alamat pelanggan">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">No Telepon</label>
            <input type="text" name="NoTelp" value="{{ old('NoTelp', $pelanggan->NoTelp) }}"
                class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none border-gray-300" placeholder="08xxxxxxxxxx">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Pelanggan</label>
            <input type="text" name="Jenis_Plg" value="{{ old('Jenis_Plg', $pelanggan->Jenis_Plg) }}"
                class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none border-gray-300" placeholder="Masukkan jenis pelanggan">
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('pelanggan.index') }}"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold transition">
                ← Batal
            </a>
            <button type="submit"
                class="px-5 py-2 bg-green-400 text-gray-800 rounded-lg hover:bg-green-500 font-semibold transition">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
