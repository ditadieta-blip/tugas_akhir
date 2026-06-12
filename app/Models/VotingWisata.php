<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VotingWisata extends Model
{
    use HasFactory;

    protected $table = 'voting_wisata';
    protected $primaryKey = 'id_voting';
    public $timestamps = true;

    protected $fillable = [
        'judul_voting',
        'status',
    ];

    public function opsi()
    {
        return $this->hasMany(VotingWisataOpsi::class, 'id_voting', 'id_voting');
    }

    public function detailVoting()
    {
        return $this->hasMany(VotingWisataDetail::class, 'id_voting', 'id_voting');
    }
}