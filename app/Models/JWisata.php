<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JWisata extends Model
{
    protected $table = 'jwisata';
    protected $primaryKey = 'id_wisata';
    public $incrementing = false; // Set false karena kita yang akan menyuplai angka ID-nya dari Controller
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_wisata', // Masukkan kembali ke fillable agar bisa diisi manual
        'nama_wisata',
        'lokasi_wisata',
        'keterangan_wisata',
        'tanggal_wisata',
        'biaya_wisata',
        'kuota',
        'is_open'
    ];

    public function pendaftaran()
    {
        return $this->hasMany(PendaftaranWisata::class, 'id_wisata', 'id_wisata');
    }
}