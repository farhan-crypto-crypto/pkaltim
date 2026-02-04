<?php

namespace App\Http\Controllers;

use App\Models\Resto;
use App\Models\Kota;
use Illuminate\Http\Request;

class RestoranController extends Controller
{
    // Tampilkan halaman restoran dengan daftar restoran
    public function index(Request $request)
    {
        // Ambil data kota untuk dropdown filter
        $kotas = Kota::orderBy('nama_kota')->get();
        // Ambil data restoran dengan filter jika ada
        $restos = Resto::with(['kota']) // 
            // Filter Berdasarkan Kota
            ->when($request->id_kota, function ($query) use ($request) {
                $query->where('id_kota', $request->id_kota);
            })
            // Filter Pencarian Nama Resto
            ->when($request->search, function ($query) use ($request) {
                $query->where('nama_resto', 'like', '%' . $request->search . '%');
            })
            ->orderBy('nama_resto')
            ->paginate(9);

        // Tampilkan ke view
        return view('restoran.index', compact('restos', 'kotas'));
    }
}