@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-white">Data Petugas</h1>
        <a href="{{ route('petugas.create') }}"
            class="px-5 py-2 bg-green-400 text-white rounded-lg hover:bg-green-500 font-semibold transition">
            ✚ Tambah Pengguna
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow-lg rounded-xl ring-1 ring-gray-200">
        <table class="min-w-full text-sm text-gray-800">
            <thead class="bg-blue-100 sticky top-0 z-10">
                <tr class="text-left">
                    <th class="px-6 py-4 font-semibold">ID</th>
                    <th class="px-6 py-4 font-semibold">Nama</th>
                    <th class="px-6 py-4 font-semibold">Email</th>
                    <th class="px-6 py-4 font-semibold">Role</th>
                    <th class="px-6 py-4 font-semibold">Dibuat</th>
                    <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($petugas as $user)
                    <tr class="even:bg-gray-50 hover:bg-gray-100 transition">
                        <td class="px-6 py-4">{{ $user->id }}</td>
                        <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4 capitalize">{{ $user->role ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $user->created_at->format('d-m-Y') }}</td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <a href="{{ route('petugas.edit', $user->id) }}"
                                class="inline-block px-4 py-2 bg-blue-400 text-white rounded hover:bg-blue-500 transition font-semibold">
                                Edit
                            </a>
                            <form action="{{ route('petugas.destroy', $user->id) }}" method="POST"
                                  class="inline-block"
                                  onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
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
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
