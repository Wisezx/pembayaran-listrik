@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-white">Data Pemakaian</h1>
            <div class="flex gap-3">
                <button id="exportPdfBtn"
                    class="inline-flex items-center px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-md hover:bg-red-700 transition duration-150 ease-in-out">
                    Ekspor PDF
                </button>

                <a href="{{ route('pemakaian.create') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-green-400 text-white text-sm font-semibold  rounded-md hover:bg-green-500 transition duration-150 ease-in-out">
                    ✚ Tambah Pemakaian
                </a>
            </div>
        </div>

        {{-- Filter Form --}}
        <form id="filterForm" method="GET" action="{{ route('pemakaian.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Tahun --}}
            <div class="flex flex-col">
                <label for="tahun" class="text-sm font-medium text-white mb-1">Tahun</label>
                <input type="number" name="tahun" id="tahun" value="{{ request('tahun') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
            </div>

            {{-- Bulan --}}
            <div class="flex flex-col">
                <label for="bulan" class="text-sm font-medium text-white mb-1">Bulan</label>
                <select name="bulan" id="bulan"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                    <option value="">-- Semua Bulan --</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- Status --}}
            <div class="flex flex-col">
                <label for="status" class="text-sm font-medium text-white mb-1">Status</label>
                <select name="status" id="status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                    <option value="">-- Semua Status --</option>
                    <option value="Belum Bayar" {{ request('status') == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>

            <div class="flex gap-2 items-end">
                {{-- Tombol Filter --}}
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700 transition duration-150">
                    Filter
                </button>

                {{-- Tombol Reset --}}
                <a href="{{ route('pemakaian.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md shadow hover:bg-gray-600 transition duration-150">
                    Reset
                </a>
            </div>
        </form>

        <div class="overflow-x-auto bg-white shadow-lg rounded-xl ring-1 ring-gray-200">
            <table class="min-w-full text-sm text-gray-800">
                <thead class="bg-blue-100 sticky top-0 z-10">
                    <tr class="text-left">
                        <th class="px-6 py-4 font-semibold">ID</th>
                        <th class="px-6 py-4 font-semibold">Tahun</th>
                        <th class="px-6 py-4 font-semibold">Bulan</th>
                        <th class="px-6 py-4 font-semibold">No Kontrol</th>
                        <th class="px-6 py-4 font-semibold">Meter Awal</th>
                        <th class="px-6 py-4 font-semibold">Meter Akhir</th>
                        <th class="px-6 py-4 font-semibold">Jumlah Pakai</th>
                        <th class="px-6 py-4 font-semibold">Biaya Beban</th>
                        <th class="px-6 py-4 font-semibold">Biaya Pemakaian</th>
                        <th class="px-6 py-4 font-semibold">Jumlah Bayar</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pemakaians as $pemakaian)
                        <tr class="even:bg-gray-50 hover:bg-gray-100 transition">
                            <td class="px-6 py-4">{{ $pemakaian->id }}</td>
                            <td class="px-6 py-4">{{ $pemakaian->tahun }}</td>
                            <td class="px-6 py-4">{{ $pemakaian->bulan }}</td>
                            <td class="px-6 py-4">{{ $pemakaian->NoKontrol }}</td>
                            <td class="px-6 py-4">{{ $pemakaian->meterawal }}</td>
                            <td class="px-6 py-4">{{ $pemakaian->meterakhir }}</td>
                            <td class="px-6 py-4">{{ $pemakaian->jumlahpakai }}</td>
                            <td class="px-6 py-4">Rp{{ number_format($pemakaian->biayabebanpemakai, 2, ',', '.') }}</td>
                            <td class="px-6 py-4">Rp{{ number_format($pemakaian->biayapemakaian, 2, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                {{ $pemakaian->jumlahbayar ? 'Rp' . number_format($pemakaian->jumlahbayar, 2, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-4">{{ $pemakaian->status }}</td>
                            <td class="px-6 py-4 text-center space-x-2 flex">
                                <a href="{{ route('pemakaian.edit', $pemakaian->id) }}"
                                    class="flex items-center gap-2 px-4 py-2 bg-blue-400 text-white rounded hover:bg-blue-500 transition font-semibold">
                                     
                                     <span>Edit</span>
                                 </a>
                                 <form action="{{ route('pemakaian.destroy', $pemakaian->id) }}" method="POST"
                                       class="inline-block ml-2"
                                       onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                     @csrf
                                     @method('DELETE')
                                     <button type="submit"
                                             class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition font-semibold">
                                        
                                         <span>Hapus</span>
                                     </button>
                                 </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <!-- Export PDF Modal -->
        <div id="exportModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                <h2 class="text-lg font-semibold mb-4">Ekspor Data Pemakaian ke PDF</h2>

                <form id="exportForm" action="{{ route('pemakaian.exportPdf') }}" method="GET">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter Ekspor</label>

                        <div class="mb-3">
                            <label class="block text-sm text-gray-600 mb-1">Tahun</label>
                            <select name="tahun" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="">-- Semua Tahun --</option>
                                @php
                                    $currentYear = date('Y');
                                    $startYear = $currentYear - 5;
                                @endphp
                                @for($year = $currentYear; $year >= $startYear; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm text-gray-600 mb-1">Bulan</label>
                            <select name="bulan" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="">-- Semua Bulan --</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm text-gray-600 mb-1">Status</label>
                            <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="">-- Semua Status --</option>
                                <option value="Belum Bayar">Belum Bayar</option>
                                <option value="Lunas">Lunas</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" id="closeExportModal"
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Ekspor PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Export PDF Modal
        const exportPdfBtn = document.getElementById('exportPdfBtn');
        const exportModal = document.getElementById('exportModal');
        const closeExportModal = document.getElementById('closeExportModal');
        const exportForm = document.getElementById('exportForm');

        // Copy current filter values to export form
        exportPdfBtn.addEventListener('click', function() {
            const filterTahun = document.getElementById('tahun').value;
            const filterBulan = document.getElementById('bulan').value;
            const filterStatus = document.getElementById('status').value;

            // Set the export form values to match current filters
            if (filterTahun) {
                exportForm.querySelector('select[name="tahun"]').value = filterTahun;
            }

            if (filterBulan) {
                exportForm.querySelector('select[name="bulan"]').value = filterBulan;
            }

            if (filterStatus) {
                exportForm.querySelector('select[name="status"]').value = filterStatus;
            }

            exportModal.classList.remove('hidden');
        });

        closeExportModal.addEventListener('click', function() {
            exportModal.classList.add('hidden');
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === exportModal) {
                exportModal.classList.add('hidden');
            }
        });
    </script>
@endsection
