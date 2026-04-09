<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JWisata extends Model
{
    protected $table = 'jwisata';
    protected $primaryKey = 'id_wisata';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_wisata',
        'nama_wisata',
        'lokasi_wisata',
        'keterangan_wisata',
        'tanggal_wisata',
        'biaya_wisata',
        'is_open',
        'created_at'
    ];
}
