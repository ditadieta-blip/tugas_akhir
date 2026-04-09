<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{
    protected $table = 'voting';
    protected $primaryKey = 'id_voting';

    protected $fillable = [
        'judul',
        'is_active',
        'mulai',
        'selesai'
    ];

    public function votes()
    {
        return $this->hasMany(Vote::class, 'id_voting');
    }
}