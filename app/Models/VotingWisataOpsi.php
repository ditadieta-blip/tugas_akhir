<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VotingWisataOpsi extends Model
{
    use HasFactory;

    protected $table = 'voting_wisata_opsi';
    protected $primaryKey = 'id_opsi';
    public $timestamps = true;

    protected $fillable = [
        'id_voting',
        'jenis_opsi',
        'nilai_opsi',
        'jumlah_vote',
    ];

    public function voting()
    {
        return $this->belongsTo(VotingWisata::class, 'id_voting', 'id_voting');
    }
}