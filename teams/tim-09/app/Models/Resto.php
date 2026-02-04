<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resto extends Model
{
    protected $primaryKey = 'id_resto';

    protected $fillable = [
        'nama_resto',
        'alamat',
        'latitude',
        'longitude',
        'id_kota'
    ];

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'id_resto', 'id_resto');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_resto', 'id_resto');
    }
}
