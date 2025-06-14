@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <!-- Search Form -->
    <form method="GET" action="{{ route('pembayaran.index') }}" class="mb-4">
        <div class="flex items-center">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari berdasarkan No Kontrol"
                class="border border-gray-300 rounded px-4 py-2 w-full"
            >
            <button type="submit" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Cari
            </button>
        </div>
    </form>

    @if(request('search'))
        @if($pelanggans->count() > 0)
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-left">No Kontrol</th>
                        <th class="py-3 px-6 text-left">Nama</th>
                        <th class="py-3 px-6 text-left">Alamat</th>
                        <th class="py-3 px-6 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach($pelanggans as $trans)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left">{{ $trans->id }}</td>
                        <td class="py-3 px-6 text-left">{{ $trans->NoKontrol }}</td>
                        <td class="py-3 px-6 text-left">{{ $trans->nama }}</td>
                        <td class="py-3 px-6 text-left">{{ $trans->alamat }}</td>
                        <td class="py-3 px-6 text-left">
                            <a href="{{ route('pembayaran.show', $trans->NoKontrol) }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                Lihat
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-4">
                {{ $pelanggans->links() }}
            </div>
        </div>
        @else
            <p class="text-center text-gray-500">Tidak ada data ditemukan untuk No Kontrol: <strong>{{ request('search') }}</strong>.</p>
        @endif
    @else
        <p class="text-center text-gray-500">Silakan masukkan No Kontrol untuk menampilkan data pembayaran.</p>
    @endif
</div>
@endsection
