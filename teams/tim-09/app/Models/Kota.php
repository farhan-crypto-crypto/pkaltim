<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    
     protected $primaryKey = 'id_kota';

    protected $fillable = ['nama_kota'];

    public function restos()
    {
        return $this->hasMany(Resto::class, 'id_kota', 'id_kota');
    }
}
