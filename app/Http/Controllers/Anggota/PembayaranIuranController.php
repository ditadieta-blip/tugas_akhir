<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PembayaranIuran;
use App\Models\JSenam;
use Midtrans\Snap;
use Midtrans\Config;

class PembayaranIuranController extends Controller
{
    public function index()
    {
        $senam = JSenam::all();
        $user = auth()->user();

        $pembayaran = PembayaranIuran::where('id_user', $user->id_user)->get();

        return view('anggota.iuran.index', compact('senam', 'pembayaran'));
    }

    public function bayar($id_senam)
    {
        $user = auth()->user();

        // CEK SUDAH BAYAR
        $cek = PembayaranIuran::where([
            'id_user' => $user->id_user,
            'id_senam' => $id_senam
        ])->first();

        if ($cek) {
            return response()->json(['error' => 'Sudah bayar']);
        }

        // MIDTRANS CONFIG
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;

        $order_id = 'IURAN-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => 2500,
            ],
            'customer_details' => [
                'first_name' => $user->nama_user,
                'email' => $user->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        PembayaranIuran::create([
            'id_user' => $user->id_user,
            'id_senam' => $id_senam,
            'nominal_bayar' => 2500,
            'status' => 'pending',
            'midtrans_order_id' => $order_id,
            'midtrans_snap_token' => $snapToken
        ]);

        return response()->json(['snap_token' => $snapToken]);
    }

    // CALLBACK MIDTRANS
    public function callback(Request $request)
    {
        $order_id = $request->order_id;
        $status = $request->transaction_status;

        $data = PembayaranIuran::where('midtrans_order_id', $order_id)->first();

        if (!$data) return;

        if ($status == 'settlement') {
            $data->status = 'success';
            $data->tanggal_bayar = now();
        } elseif ($status == 'pending') {
            $data->status = 'pending';
        } else {
            $data->status = 'failed';
        }

        $data->midtrans_transaction_status = $status;
        $data->save();
    }
}