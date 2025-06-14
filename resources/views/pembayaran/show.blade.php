@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Detail Tagihan - {{ $pelanggan->Nama }} ({{ $pelanggan->NoKontrol }})</h1>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        <p class="mb-4 text-lg font-semibold text-gray-700">Total Belum Bayar:
            <span class="text-red-600">Rp {{ number_format($totalBelumBayar, 2, ',', '.') }}</span>
        </p>

        <!-- Add bulk payment button -->
        @if($pemakaians->where('status', 'Belum Bayar')->count() > 1)
        <div class="mb-4">
            <button onclick="openBulkBayarModal()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Bayar Semua Tagihan
            </button>
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-200">
                    <tr>
                        @if($pemakaians->where('status', 'Belum Bayar')->count() > 1)
                        <th class="px-4 py-2">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300" onchange="toggleAllCheckboxes()">
                        </th>
                        @endif
                        <th class="px-4 py-2">Tahun</th>
                        <th class="px-4 py-2">Bulan</th>
                        <th class="px-4 py-2">Meter Awal</th>
                        <th class="px-4 py-2">Meter Akhir</th>
                        <th class="px-4 py-2">Jumlah Pakai</th>
                        <th class="px-4 py-2">Jumlah Bayar</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pemakaians as $pemakaian)
                        <tr class="border-b">
                            @if($pemakaians->where('status', 'Belum Bayar')->count() > 1)
                            <td class="px-4 py-2">
                                @if($pemakaian->status == 'Belum Bayar')
                                <input type="checkbox" class="bill-checkbox rounded border-gray-300"
                                       data-id="{{ $pemakaian->id }}"
                                       data-amount="{{ $pemakaian->jumlahbayar }}"
                                       data-bulan="{{ $pemakaian->bulan }}"
                                       data-tahun="{{ $pemakaian->tahun }}">
                                @endif
                            </td>
                            @endif
                            <td class="px-4 py-2">{{ $pemakaian->tahun }}</td>
                            <td class="px-4 py-2">{{ $pemakaian->bulan }}</td>
                            <td class="px-4 py-2">{{ $pemakaian->meterawal }}</td>
                            <td class="px-4 py-2">{{ $pemakaian->meterakhir }}</td>
                            <td class="px-4 py-2">{{ $pemakaian->jumlahpakai }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($pemakaian->jumlahbayar, 2, ',', '.') }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-white
                                    {{ $pemakaian->status == 'Lunas' ? 'bg-green-500' : ($pemakaian->status == 'Sudah Bayar' ? 'bg-blue-500' : 'bg-red-500') }}">
                                    {{ $pemakaian->status }}
                                </span>

                                @if ($pemakaian->status == 'Belum Bayar')
                                    <button onclick="openBayarModal('{{ $pemakaian->id }}', '{{ $pemakaian->jumlahbayar }}')"
                                        class="ml-2 text-sm text-blue-600 underline">Bayar</button>
                                @endif

                                @if ($pemakaian->status == 'Lunas')
                                    <button onclick="printReceipt('{{ $pemakaian->id }}')"
                                        class="ml-2 text-sm text-green-600 underline">Cetak</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end gap-2">
            <button onclick="printAllData()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Cetak Semua
            </button>
            <a href="{{ route('pembayaran.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                Kembali
            </a>
        </div>
    </div>
</div>

<!-- Single Payment Modal -->
<div id="bayarModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h2 class="text-lg font-semibold mb-4">Pembayaran Tagihan</h2>

        <form id="formBayar" method="POST">
            @csrf
            <input type="hidden" name="pemakaian_id" id="pemakaian_id">

            <div class="mb-4">
                <label for="jumlah_tagihan" class="block text-sm font-medium text-gray-700">Jumlah Tagihan</label>
                <input type="text" id="jumlah_tagihan" class="mt-1 block w-full rounded border-gray-300 bg-gray-100" readonly>
            </div>

            <div class="mb-4">
                <label for="jumlah_dibayar" class="block text-sm font-medium text-gray-700">Jumlah Bayar</label>
                <input type="number" step="100" min="0" name="jumlah_dibayar" id="jumlah_bayar"
                       class="mt-1 block w-full rounded border-gray-300" required>
            </div>

            <div class="mb-4">
                <label for="kembalian" class="block text-sm font-medium text-gray-700">Kembalian</label>
                <input type="text" id="kembalian" class="mt-1 block w-full rounded border-gray-300 bg-gray-100" readonly>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeBayarModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Bayar</button>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Payment Modal -->
<div id="bulkBayarModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h2 class="text-lg font-semibold mb-4">Pembayaran Tagihan Bulanan</h2>

        <form id="formBulkBayar" method="POST" action="{{ url('/pembayaran/bayar-bulk') }}">
            @csrf
            <input type="hidden" name="pemakaian_ids" id="bulk_pemakaian_ids">
            <input type="hidden" name="NoKontrol" value="{{ $pelanggan->NoKontrol }}">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Tagihan yang Dipilih</label>
                <div id="selected_bills" class="mt-1 p-2 border rounded bg-gray-50 min-h-[60px]">
                    <!-- Selected bills will be displayed here -->
                </div>
            </div>

            <div class="mb-4">
                <label for="bulk_jumlah_tagihan" class="block text-sm font-medium text-gray-700">Total Tagihan</label>
                <input type="text" id="bulk_jumlah_tagihan" class="mt-1 block w-full rounded border-gray-300 bg-gray-100" readonly>
            </div>

            <div class="mb-4">
                <label for="bulk_jumlah_dibayar" class="block text-sm font-medium text-gray-700">Jumlah Bayar</label>
                <input type="number" step="100" min="0" name="jumlah_dibayar" id="bulk_jumlah_dibayar"
                       class="mt-1 block w-full rounded border-gray-300" required>
            </div>

            <div class="mb-4">
                <label for="bulk_kembalian" class="block text-sm font-medium text-gray-700">Kembalian</label>
                <input type="text" id="bulk_kembalian" class="mt-1 block w-full rounded border-gray-300 bg-gray-100" readonly>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeBulkBayarModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Bayar Semua</button>
            </div>
        </form>
    </div>
</div>

<!-- Receipt Print Template (hidden) -->
<div id="receiptTemplate" class="hidden">
    <div id="receiptContent" class="p-8 bg-white max-w-md mx-auto">
        <div class="text-center mb-6">
            <h2 class="text-xl font-bold">STRUK PEMBAYARAN LISTRIK</h2>
            <p>{{ config('app.name', 'Laravel') }}</p>
        </div>

        <div class="mb-4">
            <p><strong>No. Kontrol:</strong> <span id="receipt_nokontrol"></span></p>
            <p><strong>Nama:</strong> <span id="receipt_nama"></span></p>
            <p><strong>Tanggal Bayar:</strong> <span id="receipt_tanggal"></span></p>
        </div>

        <div id="receipt_single_details" class="mb-4">
            <p><strong>Periode:</strong> <span id="receipt_periode"></span></p>
            <p><strong>Meter Awal:</strong> <span id="receipt_meterawal"></span></p>
            <p><strong>Meter Akhir:</strong> <span id="receipt_meterakhir"></span></p>
            <p><strong>Pemakaian:</strong> <span id="receipt_pemakaian"></span></p>
        </div>

        <div id="receipt_bulk_details" class="mb-4">
            <p><strong>Detail Tagihan:</strong></p>
            <table class="w-full text-sm mt-2">
                <thead>
                    <tr>
                        <th class="text-left">Periode</th>
                        <th class="text-right">Pemakaian</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody id="receipt_bulk_items">
                    <!-- Bulk items will be inserted here -->
                </tbody>
            </table>
        </div>

        <div class="border-t pt-4 mb-4">
            <p><strong>Jumlah Tagihan:</strong> <span id="receipt_tagihan"></span></p>
            <p><strong>Jumlah Bayar:</strong> <span id="receipt_bayar"></span></p>
            <p><strong>Kembalian:</strong> <span id="receipt_kembalian"></span></p>
        </div>

        <div class="text-center text-sm mt-6">
            <p>Terima kasih atas pembayaran Anda</p>
            <p>Simpan struk ini sebagai bukti pembayaran</p>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
   function openBayarModal(id, jumlahTagihan) {
        document.getElementById('pemakaian_id').value = id;
        document.getElementById('jumlah_tagihan').value = jumlahTagihan;
        document.getElementById('jumlah_bayar').value = jumlahTagihan; // Set default value to the bill amount
        document.getElementById('kembalian').value = 'Rp 0';

        // Set the form action with the correct absolute URL
        document.getElementById('formBayar').action = "{{ url('/pembayaran/bayar') }}/" + id;

        document.getElementById('bayarModal').classList.remove('hidden');
    }

    function closeBayarModal() {
        document.getElementById('bayarModal').classList.add('hidden');
    }

    document.getElementById('jumlah_bayar').addEventListener('input', function () {
        const bayar = parseFloat(this.value || 0);
        const tagihan = parseFloat(document.getElementById('jumlah_tagihan').value || 0);
        const kembali = bayar - tagihan;
        document.getElementById('kembalian').value = kembali > 0
            ? kembali.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })
            : 'Rp 0';
    });

    // Bulk payment functions
    function toggleAllCheckboxes() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.bill-checkbox');

        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
    }

    function openBulkBayarModal() {
        const checkboxes = document.querySelectorAll('.bill-checkbox:checked');

        if (checkboxes.length === 0) {
            alert('Silakan pilih tagihan yang akan dibayar');
            return;
        }

        let totalAmount = 0;
        let selectedIds = [];
        let selectedBillsHtml = '';

        checkboxes.forEach(checkbox => {
            const id = checkbox.getAttribute('data-id');
            const amount = parseFloat(checkbox.getAttribute('data-amount'));
            const bulan = checkbox.getAttribute('data-bulan');
            const tahun = checkbox.getAttribute('data-tahun');

            selectedIds.push(id);
            totalAmount += amount;
            selectedBillsHtml += `<div class="text-sm py-1">${bulan} ${tahun} - Rp ${amount.toLocaleString('id-ID')}</div>`;
        });

        document.getElementById('bulk_pemakaian_ids').value = selectedIds.join(',');
        document.getElementById('bulk_jumlah_tagihan').value = totalAmount;
        document.getElementById('bulk_jumlah_dibayar').value = totalAmount;
        document.getElementById('bulk_kembalian').value = 'Rp 0';
        document.getElementById('selected_bills').innerHTML = selectedBillsHtml;

        document.getElementById('bulkBayarModal').classList.remove('hidden');
    }

    function closeBulkBayarModal() {
        document.getElementById('bulkBayarModal').classList.add('hidden');
    }

    document.getElementById('bulk_jumlah_dibayar').addEventListener('input', function () {
        const bayar = parseFloat(this.value || 0);
        const tagihan = parseFloat(document.getElementById('bulk_jumlah_tagihan').value || 0);
        const kembali = bayar - tagihan;
        document.getElementById('bulk_kembalian').value = kembali > 0
            ? kembali.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })
            : 'Rp 0';
    });

    // Receipt printing function
    function printReceipt(id) {
        // Fetch payment data
        fetch(`{{ url('/pembayaran/receipt') }}/${id}`)
            .then(response => response.json())
            .then(data => {
                // Populate receipt template
                document.getElementById('receipt_nokontrol').textContent = data.NoKontrol;
                document.getElementById('receipt_nama').textContent = data.Nama;
                document.getElementById('receipt_tanggal').textContent = data.tanggal_bayar;

                // Handle bulk vs single payment display
                if (data.isBulkPayment) {
                    // Show bulk details, hide single details
                    document.getElementById('receipt_single_details').style.display = 'none';
                    document.getElementById('receipt_bulk_details').style.display = 'block';

                    // Populate bulk items
                    let bulkItemsHtml = '';
                    data.bulkDetails.forEach(item => {
                        bulkItemsHtml += `
                            <tr>
                                <td>${item.periode}</td>
                                <td class="text-right">${item.jumlahpakai}</td>
                                <td class="text-right">Rp ${parseFloat(item.jumlahbayar).toLocaleString('id-ID')}</td>
                            </tr>
                        `;
                    });
                    document.getElementById('receipt_bulk_items').innerHTML = bulkItemsHtml;

                    // Set total amount for bulk payment
                    document.getElementById('receipt_tagihan').textContent = `Rp ${parseFloat(data.totalBulkAmount).toLocaleString('id-ID')}`;
                } else {
                    // Show single details, hide bulk details
                    document.getElementById('receipt_single_details').style.display = 'block';
                    document.getElementById('receipt_bulk_details').style.display = 'none';

                    // Populate single payment details
                    document.getElementById('receipt_periode').textContent = `${data.bulan} ${data.tahun}`;
                    document.getElementById('receipt_meterawal').textContent = data.meterawal;
                    document.getElementById('receipt_meterakhir').textContent = data.meterakhir;
                    document.getElementById('receipt_pemakaian').textContent = data.jumlahpakai;
                    document.getElementById('receipt_tagihan').textContent = `Rp ${parseFloat(data.jumlahbayar).toLocaleString('id-ID')}`;
                }

                // Common fields for both types
                document.getElementById('receipt_bayar').textContent = `Rp ${parseFloat(data.jumlah_dibayar).toLocaleString('id-ID')}`;

                const kembalian = data.jumlah_dibayar - (data.isBulkPayment ? data.totalBulkAmount : data.jumlahbayar);
                document.getElementById('receipt_kembalian').textContent = `Rp ${Math.max(0, kembalian).toLocaleString('id-ID')}`;

                // Print the receipt
                const printWindow = window.open('', '_blank');
                printWindow.document.write('<html><head><title>Struk Pembayaran</title>');
                printWindow.document.write('<style>');
                printWindow.document.write(`
                    body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
                    .receipt { width: 80mm; margin: 0 auto; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .details { margin-bottom: 20px; }
                    .details p { margin: 5px 0; }
                    .total { border-top: 1px dashed #000; padding-top: 10px; margin-bottom: 20px; }
                    .footer { text-align: center; font-size: 12px; margin-top: 30px; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { padding: 4px; }
                    th { border-bottom: 1px solid #ddd; }
                `);
                printWindow.document.write('</style></head><body>');
                printWindow.document.write('<div class="receipt">');
                printWindow.document.write(document.getElementById('receiptContent').innerHTML);
                printWindow.document.write('</div></body></html>');
                printWindow.document.close();

                // Wait for content to load then print
                setTimeout(() => {
                    printWindow.print();
                    printWindow.close();
                }, 500);
            })
            .catch(error => {
                console.error('Error fetching receipt data:', error);
                alert('Gagal mencetak struk. Silakan coba lagi.');
            });
    }
</script>
<script>
    function printAllData() {
        const printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Cetak Semua Data</title>');
        printWindow.document.write('<style>');
        printWindow.document.write(`
            body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
            table { width: 100%; border-collapse: collapse; }
            th, td { padding: 4px; border: 1px solid #ddd; }
        `);
        printWindow.document.write('</style></head><body>');
        printWindow.document.write('<h1>Data Pembayaran</h1>');
        printWindow.document.write('<p><strong>Nama Pelanggan:</strong> {{ $pelanggan->Nama }}</p>'); // Tambahkan nama pelanggan
        printWindow.document.write('<table><thead><tr><th>Tahun</th><th>Bulan</th><th>Meter Awal</th><th>Meter Akhir</th><th>Jumlah Pakai</th><th>Jumlah Bayar</th><th>Status</th></tr></thead><tbody>');

<?php foreach ($pemakaians as $pemakaian): ?>
            printWindow.document.write('<tr>');
            printWindow.document.write('<td>{{ $pemakaian->tahun }}</td>');
            printWindow.document.write('<td>{{ $pemakaian->bulan }}</td>');
            printWindow.document.write('<td>{{ $pemakaian->meterawal }}</td>');
            printWindow.document.write('<td>{{ $pemakaian->meterakhir }}</td>');
            printWindow.document.write('<td>{{ $pemakaian->jumlahpakai }}</td>');
            printWindow.document.write('<td>Rp ' + '{{ number_format($pemakaian->jumlahbayar, 2, ",", ".") }}' + '</td>');
            printWindow.document.write('<td>{{ $pemakaian->status }}</td>');
            printWindow.document.write('</tr>');
    <?php endforeach; ?>

        printWindow.document.write('</tbody></table></body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>
@endsection
