@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-white">Data Tarif</h1>
        <a href="{{ route('tarif.create') }}"
            class="px-5 py-2 bg-green-400 text-white rounded-lg hover:bg-green-500 font-semibold transition">
            ✚ Tambah Tarif
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow-lg rounded-xl ring-1 ring-gray-200">
        <table class="min-w-full text-sm text-gray-800">
            <thead class="bg-blue-100 sticky top-0 z-10">
                <tr class="text-left">
                    <th class="px-6 py-4 font-semibold">ID</th>
                    <th class="px-6 py-4 font-semibold">Jenis Pelanggan</th>
                    <th class="px-6 py-4 font-semibold">Tarif/kWh</th>
                    <th class="px-6 py-4 font-semibold">Biaya Beban</th>
                    <th class="px-6 py-4 font-semibold">Dibuat</th>
                    <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tarifs as $tarif)
                    <tr class="even:bg-gray-50 hover:bg-gray-100 transition">
                        <td class="px-6 py-4">{{ $tarif->id }}</td>
                        <td class="px-6 py-4 font-medium">{{ $tarif->Jenis_Plg }}</td>
                        <td class="px-6 py-4">{{ $tarif->tarifkwh }}</td>
                        <td class="px-6 py-4">{{ $tarif->biayabeban }}</td>
                        <td class="px-6 py-4">{{ $tarif->created_at->format('d-m-Y') }}</td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <a href="{{ route('tarif.edit', $tarif->id) }}"
                                class="inline-block px-4 py-2 bg-blue-400 text-white rounded hover:bg-green-500 transition font-semibold">
                                Edit
                            </a>
                            <form action="{{ route('tarif.destroy', $tarif->id) }}" method="POST"
                                  class="inline-block"
                                  onsubmit="return confirm('Yakin ingin menghapus tarif ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition font-semibold">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data tarif.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
