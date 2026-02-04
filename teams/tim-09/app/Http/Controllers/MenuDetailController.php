<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Review; // Jangan lupa import Model Review
use Illuminate\Http\Request;

class MenuDetailController extends Controller
{
    // Tampilkan detail menu beserta reviewnya
    public function show($id_menu)
    {
        // Ambil data menu beserta resto dan reviewnya
        $menu = Menu::with(['resto', 'reviews']) 
            ->withAvg('reviews', 'rating')
            ->where('id_menu', $id_menu)
            ->firstOrFail();

        return view('menu.menudetail', compact('menu'));
    }

    // Proses tambah review untuk menu tertentu
    public function storeReview(Request $request, $id_menu)
    {
        // Validasi Input
        $request->validate([
            'nama_user' => 'required|string|max:50',
            'rating'    => 'required|integer|min:1|max:5',
            'komentar'  => 'required|string|max:500',
        ]);

        // Simpan ke Database
        Review::create([
            'id_menu'   => $id_menu,
            'nama_user' => $request->nama_user,
            'rating'    => $request->rating,
            'komentar'  => $request->komentar,
        ]);

        // Kembali ke halaman menu dengan pesan sukses
        return redirect()->back()->with('success', 'Terima kasih! Review Anda berhasil ditambahkan.');
    }
}