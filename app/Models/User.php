<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user'; 

    protected $primaryKey = 'id_user'; 

    public $timestamps = false; 

    protected $fillable = [
        'nama_user',
        'alamat',
        'no_hp',
        'email',
        'password',
        'foto',
        'id_role'
    ];

    protected $hidden = [
        'password',
    ];

    public function jadwal()
    {
        return $this->hasMany(JSenam::class, 'id_user', 'id_user');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }
    public function pendaftaran()
    {
        return $this->hasMany(PendaftaranWisata::class, 'id_user', 'id_user');
    }
}
