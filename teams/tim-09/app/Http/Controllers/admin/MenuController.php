<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Resto;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    // Tampilkan daftar menu
    public function index()
    {
        // Ambil menu, relasi resto, DAN hitung rata-rata kolom 'rating' dari tabel reviews
        $menus = Menu::with('resto')->withAvg('reviews', 'rating')->latest()->paginate(6);
        $restos = Resto::all();

        return view('admin.menu.index', compact('menus', 'restos'));
    }

    // Proses tambah menu
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'id_resto'  => 'required',
            'harga'     => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'foto'      => 'required|url',
        ]);

         // Simpan langsung karena foto cuma string link
        Menu::create($request->all());
        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    // Proses update menu
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'id_resto'  => 'required',
            'harga'     => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'foto'      => 'required|url',
        ]);

        // Update data menu
        $menu = Menu::findOrFail($id);
        $menu->update($request->all());

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui!');
    }

    // Hapus menu
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        //Hapus file foto dari penyimpanan server
        if ($menu->foto && Storage::disk('public')->exists($menu->foto)) {
            Storage::disk('public')->delete($menu->foto);
        }
        //Hapus data dari database
        $menu->delete();

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus.');
    }
}
