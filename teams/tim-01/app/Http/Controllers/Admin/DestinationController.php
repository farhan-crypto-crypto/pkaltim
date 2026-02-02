<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destination;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DestinationController extends Controller
{
    public function index()
    {
        // Pastikan relation di Model Destination bernama 'category' (singular) atau 'categories' (plural)
        // Sesuaikan dengan nama function di App\Models\Destination.php
        $destinations = Destination::with('categories')->latest()->paginate(10); 
        $categories = Category::all();

        $totalDestinations = Destination::count();
        // Hitung review jika nanti sudah ada fitur review, sementara 0 dulu
        $totalReviews = 0;   

        return view('admin.Index', compact('destinations', 'categories', 'totalDestinations', 'totalReviews'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input (Tambahkan latitude, longitude, status, opening_hours)
        $request->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id', // Pastikan ID kategori ada di tabel categories
            'description'   => 'required',
            'price'         => 'required|numeric',
            'address'       => 'required|string',
            'opening_hours' => 'nullable|string',
            'status'        => 'required|in:active,inactive', // Hanya boleh active atau inactive
            'latitude'      => 'nullable',
            'longitude'     => 'nullable',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Upload Gambar
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('destinations', 'public');
        }

        // 3. Buat Slug Unik (Sederhana)
        $slug = Str::slug($request->name);
        // Jika mau lebih aman dari duplikat, bisa tambahkan time(): $slug . '-' . time();

        // 4. Simpan ke Database
        Destination::create([
            'name'          => $request->name,
            'slug'          => $slug,
            'category_id'   => $request->category_id,
            'description'   => $request->description,
            'price'         => $request->price,
            'address'       => $request->address,
            'opening_hours' => $request->opening_hours, // BARU
            'status'        => $request->status,        // BARU
            'latitude'      => $request->latitude,      // BARU
            'longitude'     => $request->longitude,     // BARU
            'image'         => $imagePath,
        ]);

        return redirect()->route('admin.destinations.index')->with('success', 'Destinasi berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $destination = Destination::findOrFail($id);

        // 1. Validasi Update
        $request->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'description'   => 'required',
            'price'         => 'required|numeric',
            'address'       => 'required|string',
            'opening_hours' => 'nullable|string',
            'status'        => 'required|in:active,inactive',
            'latitude'      => 'nullable',
            'longitude'     => 'nullable',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Cek apakah nama berubah? Jika ya, update slug
        $slug = $destination->slug;
        if($request->name != $destination->name){
            $slug = Str::slug($request->name);
        }

        // 3. Siapkan data update
        $data = [
            'name'          => $request->name,
            'slug'          => $slug,
            'category_id'   => $request->category_id,
            'description'   => $request->description,
            'price'         => $request->price,
            'address'       => $request->address,
            'opening_hours' => $request->opening_hours, // BARU
            'status'        => $request->status,        // BARU
            'latitude'      => $request->latitude,      // BARU
            'longitude'     => $request->longitude,     // BARU
        ];

        // 4. Handle Gambar (Jika user upload baru)
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($destination->image && Storage::disk('public')->exists($destination->image)) {
                Storage::disk('public')->delete($destination->image);
            }
            // Simpan yang baru
            $data['image'] = $request->file('image')->store('destinations', 'public');
        }

        // 5. Eksekusi Update
        $destination->update($data);

        return redirect()->route('admin.destinations.index')->with('success', 'Destinasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $destination = Destination::findOrFail($id);
        
        if ($destination->image && Storage::disk('public')->exists($destination->image)) {
            Storage::disk('public')->delete($destination->image);
        }

        $destination->delete();

        return redirect()->back()->with('success', 'Destinasi berhasil dihapus!');
    }
}