<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranWisata extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_wisata';
    protected $primaryKey = 'id_pembayaran_wisata';

    protected $fillable = [
        'id_daftar_wisata',
        'jumlah_bayar',
        'total_terbayar',
        'sisa_tagihan',
        'cicilan_ke',
        'status',
        'metode_pembayaran',
        'midtrans_order_id',
        'midtrans_snap_token',
    ];

    public function pendaftaranWisata()
    {
        return $this->belongsTo(PendaftaranWisata::class, 'id_daftar_wisata', 'id_daftar_wisata');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wisata()
    {
        return $this->belongsTo(JWisata::class);
    }
}