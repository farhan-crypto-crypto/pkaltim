<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kota;
use Illuminate\Http\Request;
use App\Models\Resto;

class RestoController extends Controller
{
    // Tampilkan daftar restoran
    public function index()
    {
        // Ambil data kota untuk filter dan data resto dengan relasi kota
        $kotas = Kota::all();
        $restos = Resto::with('kota')->latest()->paginate(10);
        return view('admin.resto.index', compact('restos', 'kotas'));
    }

    // Tampilkan form tambah restoran
    public function create()
    {
        $kotas = Kota::all();
        return view('admin.resto.create', compact('kotas'));
    }

    // Proses simpan restoran baru
    public function store(Request $request)
    {
        //Validasi Input
        $request->validate([
            'nama_resto' => 'required|string|max:255',
            'alamat'     => 'required|string',
            'id_kota'    => 'required|exists:kotas,id_kota',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
        ], [
            'nama_resto.required' => 'Nama restoran wajib diisi',
            'id_kota.required'    => 'Silakan pilih kota',
        ]);

        //Simpan Data
        Resto::create([
            'nama_resto' => $request->nama_resto,
            'alamat'     => $request->alamat,
            'id_kota'    => $request->id_kota,
            'latitude'   => $request->latitude,
            'longitude'  => $request->longitude,
        ]);
        return redirect()->route('admin.resto.index')
                         ->with('success', 'Restoran berhasil ditambahkan!');
    }

    // Tampilkan form edit restoran
    public function edit($id)
    {
        $resto = Resto::findOrFail($id);
        $kotas = Kota::all();

        return view('admin.resto.edit', compact('resto', 'kotas'));
    }

    // Proses update restoran
    public function update(Request $request, $id)
    {
        //Validasi Input
        $request->validate([
            'nama_resto' => 'required|string|max:255',
            'alamat'     => 'required|string',
            'id_kota'    => 'required|exists:kotas,id_kota',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
        ]);

        //Update Data
        $resto = Resto::findOrFail($id);
        $resto->update([
            'nama_resto' => $request->nama_resto,
            'alamat'     => $request->alamat,
            'id_kota'    => $request->id_kota,
            'latitude'   => $request->latitude,
            'longitude'  => $request->longitude,
        ]);

        return redirect()->route('admin.resto.index')
                         ->with('success', 'Data restoran berhasil diperbarui!');
    }

    // Hapus restoran
    public function destroy($id)
    {
        // Hapus data restoran berdasarkan ID
        $resto = Resto::findOrFail($id);
        $resto->delete();
        return redirect()->route('admin.resto.index')
                         ->with('success', 'Restoran berhasil dihapus.');
    }
}