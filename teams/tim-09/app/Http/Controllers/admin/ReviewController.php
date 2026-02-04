<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    // Tampilkan daftar review
    public function index()
    {
        $reviews = Review::with('menu.resto')->latest()->paginate(7);
        return view('admin.review.index', compact('reviews'));
    }

    // Hapus review
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('admin.review.index')
                         ->with('success', 'Review berhasil dihapus.');
    }
}