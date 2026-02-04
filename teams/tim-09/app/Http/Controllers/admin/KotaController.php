<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kota;
use Illuminate\Http\Request;

class KotaController extends Controller
{
    // Tampilkan daftar kota
    public function index()
    {
        $kotas = Kota::latest()->get(); 
        return view('admin.kota.index', compact('kotas'));
    }

    // Proses tambah kota
    public function store(Request $request)
    {
        $request->validate([
            'nama_kota' => 'required|string|max:255'
        ]);
        Kota::create([
            'nama_kota' => $request->nama_kota
        ]);
        return redirect()->route('admin.kota.dashboard')->with('success', 'Data Kota berhasil dibuat.');
    }

    // Tampilkan form edit kota
    public function edit($id)
    {
        $kota = Kota::findOrFail($id);
        return view('admin.kota.index', compact('kota'));
    }

    // Proses update kota
    public function update(Request $request, $id)
    {
        $request->validate(['nama_kota' => 'required|string|max:255']);
        $kota = Kota::findOrFail($id);
        $kota->update(['nama_kota' => $request->nama_kota]);    
        return redirect()->route('admin.kota.dashboard')->with('success', 'Data kota berhasil diperbarui!');
    }

    // Hapus Kota
    public function destroy($id)
    {
        $kota = Kota::findOrFail($id);
        $kota->delete();
        
        return redirect()->route('admin.kota.dashboard')
                         ->with('success', 'Kota berhasil dihapus.');
    }
}