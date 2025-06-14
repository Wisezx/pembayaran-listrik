<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use Illuminate\Http\Request;

class TarifController extends Controller
{
    /**
     * Menampilkan daftar tarif.
     */
    public function index()
    {
        // Ambil semua data tarif
        $tarifs = Tarif::all();

        // Kembalikan ke tampilan dengan data tarif
        return view('tarif.index', compact('tarifs'));
    }

    /**
     * Menampilkan form untuk membuat tarif baru.
     */
    public function create()
    {
        return view('tarif.create');
    }

    /**
     * Menyimpan tarif baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'Jenis_Plg' => 'required|string|max:100|unique:tarifs,Jenis_Plg',
            'tarifkwh' => 'required|numeric',
            'biayabeban' => 'required|numeric',
        ]);

        // Simpan tarif baru
        Tarif::create([
            'Jenis_Plg' => $request->Jenis_Plg,
            'tarifkwh' => $request->tarifkwh,
            'biayabeban' => $request->biayabeban,
        ]);

        // Redirect ke halaman daftar tarif dengan pesan sukses
        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil dibuat.');
    }

    /**
     * Menampilkan form untuk mengedit tarif.
     */
    public function edit($id)
    {
        // Cari tarif berdasarkan ID
        $tarif = Tarif::findOrFail($id);

        // Tampilkan form edit dengan data tarif
        return view('tarif.edit', compact('tarif'));
    }

    /**
     * Memperbarui tarif yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        // Validasi data input
        $request->validate([
            'Jenis_Plg' => 'required|string|max:100|unique:tarifs,Jenis_Plg,' . $id,
            'tarifkwh' => 'required|numeric',
            'biayabeban' => 'required|numeric',
        ]);

        // Cari tarif berdasarkan ID
        $tarif = Tarif::findOrFail($id);

        // Update tarif
        $tarif->update([
            'Jenis_Plg' => $request->Jenis_Plg,
            'tarifkwh' => $request->tarifkwh,
            'biayabeban' => $request->biayabeban,
        ]);

        // Redirect ke halaman daftar tarif dengan pesan sukses
        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil diperbarui.');
    }

    /**
     * Menghapus tarif.
     */
    public function destroy($id)
    {
        // Cari tarif berdasarkan ID
        $tarif = Tarif::findOrFail($id);

        // Hapus tarif
        $tarif->delete();

        // Redirect ke halaman daftar tarif dengan pesan sukses
        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil dihapus.');
    }
}
