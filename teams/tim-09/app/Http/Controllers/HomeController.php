<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Kota;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Tampilkan halaman home dengan daftar menu
    public function index(Request $request)
    {
        // mengambil data kota
        $kotas = Kota::orderBy('nama_kota')->get();
        $menus = Menu::with(['resto.kota', 'reviews'])
            // Filter Kota
            ->when($request->id_kota, function ($query) use ($request) {
                $query->whereHas('resto', function ($q) use ($request) {
                    $q->where('id_kota', $request->id_kota);
                });
            })
            // Filter Search (Menu ATAU Resto)
            ->when($request->search, function ($query) use ($request) {
                $query->where(function($q) use ($request) {
                    // Cari Nama Menu
                    $q->where('nama_menu', 'like', '%' . $request->search . '%')
                      // ATAU Cari Nama Resto
                      ->orWhereHas('resto', function ($subQ) use ($request) {
                          $subQ->where('nama_resto', 'like', '%' . $request->search . '%');
                      });
                });
            })
            ->latest()
            ->orderBy('nama_menu')
            ->get();

        return view('home.index', compact('menus', 'kotas'));
    }
}