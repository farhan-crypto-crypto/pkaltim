<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    // Menamakan primarykey untuk table Menu 
     protected $primaryKey = 'id_menu';

    //  
    protected $fillable = [
        'nama_menu',
        'deskripsi',
        'foto',
        'harga',
        'id_resto'
    ];

    public function resto()
    {
        return $this->belongsTo(Resto::class, 'id_resto', 'id_resto');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_menu', 'id_menu');
    }
}
