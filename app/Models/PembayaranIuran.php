<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranIuran extends Model
{
    protected $table = 'pembayaran_iuran_senam';
    protected $primaryKey = 'id_bayar_iuran';

    protected $fillable = [
        'id_user',
        'id_senam',
        'nominal_bayar',
        'nominal_dibayar',
        'metode',
        'status',
        'midtrans_order_id',
        'midtrans_snap_token',
        'midtrans_transaction_status',
        'tanggal_bayar'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function senam()
    {
        return $this->belongsTo(JSenam::class, 'id_senam');
    }

    public function absensi()
    {
        return $this->hasMany(AbsensiSenam::class, 'id_senam', 'id_senam');
    }
}