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
        return $this->belongsTo(Senam::class, 'id_senam');
    }
}