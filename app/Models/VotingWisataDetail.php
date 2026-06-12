<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VotingWisataDetail extends Model
{
    use HasFactory;

    protected $table = 'voting_wisata_detail';
    protected $primaryKey = 'id_detail_vote';
    public $timestamps = true;

    protected $fillable = [
        'id_voting',
        'id_user',
        'id_opsi_lokasi',
        'id_opsi_tanggal',
    ];

    public function voting()
    {
        return $this->belongsTo(VotingWisata::class, 'id_voting', 'id_voting');
    }

    public function opsiLokasi()
    {
        return $this->belongsTo(VotingWisataOpsi::class, 'id_opsi_lokasi', 'id_opsi');
    }

    public function opsiTanggal()
    {
        return $this->belongsTo(VotingWisataOpsi::class, 'id_opsi_tanggal', 'id_opsi');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}