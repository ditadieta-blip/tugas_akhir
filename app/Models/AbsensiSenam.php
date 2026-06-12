<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiSenam extends Model
{
    protected $table = 'absensi_senam';
    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'id_senam',
        'id_user',
        'status',
        'is_confirmed'
    ];

    // relasi ke jadwal
    public function senam()
    {
        return $this->belongsTo(JSenam::class, 'id_senam');
    }

    // relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}