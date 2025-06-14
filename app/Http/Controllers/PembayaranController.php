<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pemakaian;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $pelanggans = collect(); // Empty default

        if ($request->filled('search')) {
            $pelanggans = Pelanggan::where('NoKontrol', 'like', '%' . $request->search . '%')
                ->orWhere('Nama', 'like', '%' . $request->search . '%')
                ->paginate(10);
        }

        return view('pembayaran.index', compact('pelanggans'));
    }

    public function show($NoKontrol)
    {
        // Find the customer by NoKontrol
        $pelanggan = Pelanggan::where('NoKontrol', $NoKontrol)->firstOrFail();

        // Get all usage records for this customer
        $pemakaians = Pemakaian::where('NoKontrol', $NoKontrol)
                              ->orderBy('tahun', 'desc')
                              ->orderBy('bulan', 'desc')
                              ->get();

        // Calculate total unpaid amount
        $totalBelumBayar = $pemakaians->where('status', 'Belum Bayar')
                                     ->sum('jumlahbayar');

        return view('pembayaran.show', compact('pelanggan', 'pemakaians', 'totalBelumBayar'));
    }

    public function bayar(Request $request, $id)
    {
        $pemakaian = Pemakaian::findOrFail($id);

        $request->validate([
            'jumlah_dibayar' => 'required|numeric|min:' . $pemakaian->jumlahbayar,
        ]);

        // Check for previous unpaid bills
        $previousUnpaid = Pemakaian::where('NoKontrol', $pemakaian->NoKontrol)
            ->where('status', 'Belum Bayar')
            ->where(function($query) use ($pemakaian) {
                $query->where('tahun', '<', $pemakaian->tahun)
                    ->orWhere(function($q) use ($pemakaian) {
                        $q->where('tahun', $pemakaian->tahun)
                          ->where('bulan', '<', $pemakaian->bulan);
                    });
            })
            ->exists();

        if ($previousUnpaid) {
            return back()->withErrors(['message' => 'Tidak dapat membayar tagihan ini sebelum melunasi tagihan bulan-bulan sebelumnya.']);
        }

        // Create payment record
        $pembayaran = Pembayaran::create([
            'pelanggan_id' => Pelanggan::where('NoKontrol', $pemakaian->NoKontrol)->first()->id,
            'petugas_id' => auth()->id(),
            'tanggal' => now(),
            'bulan_tagihan' => $pemakaian->bulan . ' ' . $pemakaian->tahun,
            'jumlah_tagihan' => $pemakaian->jumlahbayar,
            'jumlah_bayar' => $request->jumlah_dibayar,
            'metode_pembayaran' => 'Cash',
            'tanggal_bayar' => now(),
            'catatan' => 'Pembayaran untuk ' . $pemakaian->bulan . ' ' . $pemakaian->tahun,
        ]);

        // Update the payment status and link to payment
        $pemakaian->status = 'Lunas';
        $pemakaian->pembayaran_id = $pembayaran->id; // Store the payment ID
        $pemakaian->save();

        return redirect()->route('pembayaran.show', $pemakaian->NoKontrol)
                         ->with('success', 'Pembayaran berhasil diproses.');
    }

    public function bayarBulk(Request $request)
    {
        // Validate request
        $request->validate([
            'pemakaian_ids' => 'required|string',
            'jumlah_dibayar' => 'required|numeric',
            'NoKontrol' => 'required|string'
        ]);

        // Get IDs array
        $ids = explode(',', $request->pemakaian_ids);

        // Get all pemakaian records
        $pemakaians = Pemakaian::whereIn('id', $ids)->get();

        // Validate that all selected bills are consecutive and include the oldest unpaid bill
        $allUnpaidBills = Pemakaian::where('NoKontrol', $request->NoKontrol)
            ->where('status', 'Belum Bayar')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        if ($allUnpaidBills->count() > 0) {
            // Get the oldest unpaid bill
            $oldestUnpaid = $allUnpaidBills->first();

            // Check if the oldest unpaid bill is included in the selection
            $oldestIncluded = $pemakaians->contains(function($pemakaian) use ($oldestUnpaid) {
                return $pemakaian->id == $oldestUnpaid->id;
            });

            if (!$oldestIncluded) {
                return back()->withErrors(['message' => 'Anda harus membayar tagihan tertua terlebih dahulu (Bulan ' . $oldestUnpaid->bulan . ' ' . $oldestUnpaid->tahun . ').']);
            }

            // Check if all selected bills are consecutive
            $selectedIds = $pemakaians->pluck('id')->toArray();
            $selectedBillsOrdered = $allUnpaidBills->filter(function($bill) use ($selectedIds) {
                return in_array($bill->id, $selectedIds);
            });

            // If the count is different, it means there are gaps in the selection
            if ($selectedBillsOrdered->count() != count($selectedIds)) {
                return back()->withErrors(['message' => 'Anda harus membayar tagihan secara berurutan tanpa melewatkan bulan.']);
            }
        }

        // Calculate total amount
        $totalAmount = $pemakaians->sum('jumlahbayar');

        // Validate payment amount
        if ($request->jumlah_dibayar < $totalAmount) {
            return back()->withErrors(['jumlah_dibayar' => 'Jumlah pembayaran kurang dari total tagihan']);
        }

        // Begin transaction
        DB::beginTransaction();

        try {
            // Create single payment record for all bills
            $pelangganId = Pelanggan::where('NoKontrol', $request->NoKontrol)->first()->id;

            $periodes = $pemakaians->map(function($item) {
                return $item->bulan . ' ' . $item->tahun;
            })->implode(', ');

            $pembayaran = Pembayaran::create([
                'pelanggan_id' => $pelangganId,
                'petugas_id' => auth()->id(),
                'tanggal' => now(),
                'bulan_tagihan' => $periodes,
                'jumlah_tagihan' => $totalAmount,
                'jumlah_bayar' => $request->jumlah_dibayar,
                'metode_pembayaran' => 'Cash',
                'tanggal_bayar' => now(),
                'catatan' => 'Pembayaran bulk untuk periode: ' . $periodes,
            ]);

            // Update all pemakaian records with the payment ID
            foreach ($pemakaians as $pemakaian) {
                $pemakaian->status = 'Lunas';
                $pemakaian->pembayaran_id = $pembayaran->id; // Store the payment ID
                $pemakaian->save();
            }

            DB::commit();

            return redirect()->route('pembayaran.show', $request->NoKontrol)
                             ->with('success', 'Pembayaran untuk ' . count($ids) . ' tagihan berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    public function receipt($id)
{
    // Get pemakaian data
    $pemakaian = Pemakaian::findOrFail($id);

    // Get pelanggan data
    $pelanggan = Pelanggan::where('NoKontrol', $pemakaian->NoKontrol)->first();

    // Get payment data
    $pembayaran = null;
    $isBulkPayment = false;
    $bulkDetails = [];

    // Check if we have a direct payment link
    if ($pemakaian->pembayaran_id) {
        $pembayaran = Pembayaran::find($pemakaian->pembayaran_id);

        // Check if this is a bulk payment
        if ($pembayaran && strpos($pembayaran->bulan_tagihan, ',') !== false) {
            $isBulkPayment = true;

            // Get all pemakaian records for this payment
            $relatedPemakaians = Pemakaian::where('pembayaran_id', $pembayaran->id)
                                         ->orderBy('tahun')
                                         ->orderBy('bulan')
                                         ->get();

            foreach ($relatedPemakaians as $related) {
                $bulkDetails[] = [
                    'periode' => $related->bulan . ' ' . $related->tahun,
                    'jumlahpakai' => $related->jumlahpakai,
                    'jumlahbayar' => $related->jumlahbayar
                ];
            }
        }
    } else {
        // Fallback to the old method if no direct link
        $pembayaran = Pembayaran::where('bulan_tagihan', 'like', '%' . $pemakaian->bulan . ' ' . $pemakaian->tahun . '%')
                                ->where('pelanggan_id', $pelanggan->id)
                                ->first();
    }

    // Format the date safely
    $tanggalBayar = now()->format('d-m-Y H:i:s');
    if ($pembayaran) {
        try {
            // Try to format the date if it's a Carbon instance
            if ($pembayaran->tanggal_bayar instanceof \Carbon\Carbon) {
                $tanggalBayar = $pembayaran->tanggal_bayar->format('d-m-Y H:i:s');
            } else if (is_string($pembayaran->tanggal_bayar)) {
                // If it's a string, try to parse it
                $tanggalBayar = \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d-m-Y H:i:s');
            }
        } catch (\Exception $e) {
            // If there's an error, use the current date
            $tanggalBayar = now()->format('d-m-Y H:i:s');
        }
    }

    // Combine data
    $data = [
        'id' => $pemakaian->id,
        'NoKontrol' => $pelanggan->NoKontrol,
        'Nama' => $pelanggan->Nama,
        'bulan' => $pemakaian->bulan,
        'tahun' => $pemakaian->tahun,
        'meterawal' => $pemakaian->meterawal,
        'meterakhir' => $pemakaian->meterakhir,
        'jumlahpakai' => $pemakaian->jumlahpakai,
        'jumlahbayar' => $pemakaian->jumlahbayar,
        'status' => $pemakaian->status,
        'tanggal_bayar' => $tanggalBayar,
        'jumlah_dibayar' => $pembayaran ? $pembayaran->jumlah_bayar : $pemakaian->jumlahbayar,
        'isBulkPayment' => $isBulkPayment,
        'bulkDetails' => $bulkDetails,
        'totalBulkAmount' => $isBulkPayment ? $pembayaran->jumlah_tagihan : $pemakaian->jumlahbayar
    ];

    return response()->json($data);
}
}
