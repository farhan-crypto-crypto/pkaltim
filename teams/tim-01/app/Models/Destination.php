<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'destinations';

    // 1. Kolom yang bisa diisi (Wajib sama dengan Controller)
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'address',      // <--- Pastikan ini ada
        'price',
        'status',
        'price_note',
        'latitude',
        'longitude',
        'opening_hours',
    ];

    // ==========================
    // RELASI DATABASE
    // ==========================

    // 2. Relasi ke Category
    // 2. Relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // 3. Relasi ke Facilities (Pivot)
    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'destination_facilities');
    }

    // 4. Relasi ke Images 
    // (Fungsi ini memanggil file DestinationImage.php di bawah)
    public function images()
    {
        return $this->hasMany(DestinationImage::class);
    }

    // 5. Relasi ke Reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function getHighlightsAttribute()
    {
        return [
            'Pemandangan Alam Memukau',
            'Suasana Tenang & Asri',
            'Cocok untuk Keluarga',
            'Spot Foto Instagramable'
        ];
    }

    /**
     * Helper untuk mengambil URL thumbnail (gambar primary)
     * Jika tidak ada, kembalikan gambar default dari field 'image' atau placeholder
     */
    public function getThumbnailAttribute()
    {
        // 1. Cek apakah ada gambar di tabel destination_images yang is_primary = 1
        $primaryImage = $this->images->where('is_primary', 1)->first();

        if ($primaryImage) {
            return asset('storage/' . $primaryImage->image_path);
        }

        // 2. Jika tidak ada primary, ambil gambar apa saja dari gallery
        $randomImage = $this->images->first();
        if ($randomImage) {
            return asset('storage/' . $randomImage->image_path);
        }

        // 3. Jika gallery kosong, gunakan fallback default
        return 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80';
    }
}