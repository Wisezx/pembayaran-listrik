<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory;

    protected $table = 'tarifs';

    protected $fillable = [
        'Jenis_Plg',
        'tarifkwh',
        'biayabeban',
    ];

    // Relasi ke Pelanggan (menggunakan kolom Jenis_Plg)
    public function pelanggans()
    {
        return $this->hasMany(Pelanggan::class, 'Jenis_Plg', 'Jenis_Plg');
    }
}
