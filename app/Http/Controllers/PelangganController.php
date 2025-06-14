<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tarif;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::all();
        return view('pelanggan.index', compact('pelanggans'));
    }

    public function create()
{
    // Ambil data jenis pelanggan dari tabel tarif
    $tarifs = Tarif::all();

    // Kirimkan data tarif ke view create
    return view('pelanggan.create', compact('tarifs'));
}

public function store(Request $request)
{
    // Validasi input
    $validatedData = $request->validate([
        'Nama' => 'required|string|max:255',
        'Alamat' => 'required|string|max:255',
        'NoTelp' => 'required|string|max:15',
        'Jenis_Plg' => 'required|string|max:100', // Hapus exists kalau tidak pakai tabel jenis
    ]);

    // Menambahkan NoKontrol
    $validatedData['NoKontrol'] = 'PLG-' . strtoupper(uniqid());

    // Simpan data pelanggan
    Pelanggan::create($validatedData);

    // Redirect dengan pesan sukses
    return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
}


    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $validatedData = $request->validate([
            'Nama' => 'required|string|max:255',
            'Alamat' => 'required|string|max:255',
            'NoTelp' => 'required|string|max:15',
            'Jenis_Plg' => 'required|string|max:100', // Hapus exists kalau tidak pakai tabel jenis
        ]);

        $pelanggan->update($validatedData);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
