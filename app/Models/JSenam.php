<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JSenam extends Model
{
    protected $table = 'senam';
    protected $primaryKey = 'id_senam';
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'tanggal',
        'tempat_senam',
        'keterangan_senam'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
