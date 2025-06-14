<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemakaian extends Model
{
    use HasFactory;

    protected $table = 'pemakaians';

    protected $fillable = [
        'tahun',
        'bulan',
        'NoKontrol',
        'meterawal',
        'meterakhir',
        'jumlahpakai',
        'biayabebanpemakai',
        'biayapemakaian',
        'status',
        'jumlahbayar',
        'pembayaran_id', // Add this line
    ];

    /**
     * Relasi ke model Pelanggan
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'NoKontrol', 'NoKontrol');
    }
}
