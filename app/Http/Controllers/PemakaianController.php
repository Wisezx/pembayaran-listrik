<?php

namespace App\Http\Controllers;

use App\Models\Pemakaian;
use App\Models\Pelanggan;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PemakaianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemakaian::query();

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pemakaians = $query->orderByDesc('id')->get();

        return view('pemakaian.index', compact('pemakaians'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::all();
        $currentMonth = date('n');
        $currentYear = date('Y');

        return view('pemakaian.create', compact('pelanggans', 'currentMonth', 'currentYear'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|integer',
            'bulan' => 'required|integer|between:1,12',
            'NoKontrol' => 'required|exists:pelanggans,NoKontrol',
            'meterawal' => 'required|integer',
            'meterakhir' => 'required|integer|gte:meterawal',
            'jumlahpakai' => 'required|integer',
            'biayabebanpemakai' => 'required|numeric',
            'biayapemakaian' => 'required|numeric',
            'status' => 'required|in:Lunas,Belum Bayar,Sudah Bayar',
            'jumlahbayar' => 'nullable|numeric',
        ]);

        // Check if there's already a record for this NoKontrol, month, and year
        $exists = Pemakaian::where('NoKontrol', $request->NoKontrol)
                           ->where('bulan', $request->bulan)
                           ->where('tahun', $request->tahun)
                           ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['message' => 'Data pemakaian untuk pelanggan, bulan, dan tahun ini sudah ada.'])->withInput();
        }

        // Check if this is the correct next month for this customer
        $lastRecord = Pemakaian::where('NoKontrol', $request->NoKontrol)
                              ->orderBy('tahun', 'desc')
                              ->orderBy('bulan', 'desc')
                              ->first();

        if ($lastRecord) {
            $nextMonth = $lastRecord->bulan == 12 ? 1 : $lastRecord->bulan + 1;
            $nextYear = $lastRecord->bulan == 12 ? $lastRecord->tahun + 1 : $lastRecord->tahun;

            if ($request->bulan != $nextMonth || $request->tahun != $nextYear) {
                return redirect()->back()->withErrors(['message' => 'Anda hanya dapat menambahkan data untuk bulan berikutnya.'])->withInput();
            }
        }

        Pemakaian::create($validated);

        return redirect()->route('pemakaian.index')->with('success', 'Data pemakaian berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pemakaian = Pemakaian::findOrFail($id);
        $pelanggans = Pelanggan::all();

        return view('pemakaian.edit', compact('pemakaian', 'pelanggans'));
    }

    public function update(Request $request, $id)
    {
        $pemakaian = Pemakaian::findOrFail($id);

        $validated = $request->validate([
            'tahun' => 'required|integer',
            'bulan' => 'required|integer|between:1,12',
            'NoKontrol' => 'required|exists:pelanggans,NoKontrol',
            'meterawal' => 'required|integer',
            'meterakhir' => 'required|integer|gte:meterawal',
            'jumlahpakai' => 'required|integer',
            'biayabebanpemakai' => 'required|numeric',
            'biayapemakaian' => 'required|numeric',
            'status' => 'required|in:Lunas,Belum Bayar,Sudah Bayar',
            'jumlahbayar' => 'nullable|numeric',
        ]);

        $pemakaian->update($validated);

        return redirect()->route('pemakaian.index')->with('success', 'Data pemakaian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pemakaian = Pemakaian::findOrFail($id);
        $pemakaian->delete();

        return redirect()->route('pemakaian.index')->with('success', 'Data pemakaian berhasil dihapus.');
    }

    // API endpoints for AJAX requests
    public function getPelangganInfo(Request $request)
    {
        $noKontrol = $request->input('NoKontrol');

        // Get pelanggan and associated tarif
        $pelanggan = Pelanggan::with('tarif')->where('NoKontrol', $noKontrol)->first();

        if (!$pelanggan) {
            return response()->json(['error' => 'Pelanggan tidak ditemukan'], 404);
        }

        // Get the last usage record for this customer
        $lastPemakaian = Pemakaian::where('NoKontrol', $noKontrol)
                                 ->orderBy('tahun', 'desc')
                                 ->orderBy('bulan', 'desc')
                                 ->first();

        $nextMonth = 1;
        $nextYear = date('Y');

        if ($lastPemakaian) {
            $nextMonth = $lastPemakaian->bulan == 12 ? 1 : $lastPemakaian->bulan + 1;
            $nextYear = $lastPemakaian->bulan == 12 ? $lastPemakaian->tahun + 1 : $lastPemakaian->tahun;
            $meterawal = $lastPemakaian->meterakhir;
        } else {
            $meterawal = 0; // Default for new customers
        }

        return response()->json([
            'pelanggan' => $pelanggan,
            'biayabeban' => $pelanggan->tarif->biayabeban ?? 0,
            'tarifkwh' => $pelanggan->tarif->tarifkwh ?? 0,
            'meterawal' => $meterawal,
            'nextMonth' => $nextMonth,
            'nextYear' => $nextYear
        ]);
    }

    public function exportPdf(Request $request)
    {
        $query = Pemakaian::query();

        // Apply filters
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pemakaians = $query->orderBy('tahun', 'desc')
                           ->orderBy('bulan', 'desc')
                           ->get();

        // Get filter information for the PDF title
        $filterInfo = [];

        if ($request->filled('tahun')) {
            $filterInfo[] = 'Tahun: ' . $request->tahun;
        }

        if ($request->filled('bulan')) {
            $bulanNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $filterInfo[] = 'Bulan: ' . $bulanNames[(int)$request->bulan];
        }

        if ($request->filled('status')) {
            $filterInfo[] = 'Status: ' . $request->status;
        }

        $filterText = !empty($filterInfo) ? implode(', ', $filterInfo) : 'Semua Data';

        // Generate PDF
        $pdf = Pdf::loadView('pemakaian.pdf', [
            'pemakaians' => $pemakaians,
            'filterText' => $filterText
        ]);

        // Generate filename based on filters
        $filename = 'data_pemakaian';
        if ($request->filled('tahun')) {
            $filename .= '_' . $request->tahun;
        }
        if ($request->filled('bulan')) {
            $filename .= '_' . $request->bulan;
        }
        if ($request->filled('status')) {
            $filename .= '_' . str_replace(' ', '_', $request->status);
        }
        $filename .= '.pdf';

        return $pdf->download($filename);
    }

    public function show($id)
    {
        $pemakaian = Pemakaian::findOrFail($id);
        return view('pemakaian.show', compact('pemakaian'));
    }

    public function home(Request $request)
{
    $query = Pemakaian::query();

    if ($request->filled('NoKontrol')) {
        $query->where('NoKontrol', $request->NoKontrol);
    }

    $pemakaians = $query->get();

    return view('welcome', compact('pemakaians'));
}

}
