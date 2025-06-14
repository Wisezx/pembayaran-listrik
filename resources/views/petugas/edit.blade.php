@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-white mb-6">Edit Akun Petugas</h1>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg shadow-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('petugas.update', $petugas->id) }}" method="POST" class="bg-white p-8 rounded-xl shadow-lg space-y-6 ring-1 ring-gray-200">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Petugas</label>
            <input type="text" name="name" value="{{ old('name', $petugas->name) }}" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $petugas->email) }}" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Password <span class="text-xs text-gray-500">(Biarkan kosong jika tidak ingin diubah)</span>
            </label>
            <input type="password" name="password"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <input type="hidden" name="role" value="petugas">

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('petugas.index') }}"
               class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
               Batal
            </a>
            <button type="submit"
                    class="px-5 py-2 bg-green-400 text-white rounded-lg hover:bg-green-500 transition font-semibold">
                Perbarui
            </button>
        </div>
    </form>
</div>
@endsection
