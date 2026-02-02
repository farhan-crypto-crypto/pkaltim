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
    'image',        // <--- Pastikan ini ada juga
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
        return $this->belongsToMany(Facility::class, 'destination_facility');
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
}