<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Kota;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // Tampilkan halaman menu dengan daftar menu
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
            // Filter Search (Nama Menu ATAU Nama Restoran)
            ->when($request->search, function ($query) use ($request) {
                $query->where(function($q) use ($request) {
                    // Cari di Nama Menu
                    $q->where('nama_menu', 'like', '%' . $request->search . '%')
                      // ATAU Cari di Nama Restoran
                      ->orWhereHas('resto', function ($subQ) use ($request) {
                          $subQ->where('nama_resto', 'like', '%' . $request->search . '%');
                      });
                });
            })
            ->orderBy('nama_menu')
            ->paginate(8);

        return view('menu.menu', compact('menus', 'kotas'));
    }
}