<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika sesuai konvensi Laravel)
    protected $table = 'pelanggans';

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'NoKontrol',
        'Nama',
        'Alamat',
        'NoTelp',
        'Jenis_Plg',
    ];
    public function tarif()
{
    return $this->belongsTo(Tarif::class, 'Jenis_Plg', 'Jenis_Plg');
}

}
