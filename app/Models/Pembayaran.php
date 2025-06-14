<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'pelanggan_id',
        'petugas_id',
        'tanggal',
        'bulan_tagihan',
        'jumlah_tagihan',
        'jumlah_bayar',
        'metode_pembayaran',
        'tanggal_bayar',
        'catatan',
    ];

    public function pelanggan()
{
    return $this->belongsTo(Pelanggan::class);
}

public function petugas()
{
    return $this->belongsTo(User::class, 'petugas_id');
}

}
