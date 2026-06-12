<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranWisata extends Model
{
    protected $table = 'pendaftaran_wisata';
    protected $primaryKey = 'id_daftar_wisata';

    protected $fillable = [
        'id_user',
        'id_wisata',
        'status_daftar', // menunggu, diterima, ditolak
    ];

    // Relasi ke tabel user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke tabel jwisata
    public function jwisata()
    {
        return $this->belongsTo(JWisata::class, 'id_wisata', 'id_wisata');
    }

    public function pembayaranWisata()
    {
        return $this->hasMany(PembayaranWisata::class, 'id_daftar_wisata', 'id_daftar_wisata');
    }

}